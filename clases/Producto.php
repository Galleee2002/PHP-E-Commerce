<?php

require_once __DIR__ . '/DBConexion.php';

class Producto
{
    private int $producto_id = 0;
    private string $nombre = '';
    private float $precio = 0.0;
    private string $categoria = '';
    private string $descripcion_corta = '';
    private string $descripcion = '';
    private string $imagen = '';

    public function todas(): array
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "
            SELECT p.producto_id, p.nombre, p.precio, p.descripcion_corta, p.descripcion, p.imagen,
                   GROUP_CONCAT(c.nombre ORDER BY c.nombre SEPARATOR ', ') AS categoria
            FROM productos p
            LEFT JOIN productos_tienen_categorias ptc ON p.producto_id = ptc.producto_fk
            LEFT JOIN categorias c ON ptc.categoria_fk = c.categoria_id
            GROUP BY p.producto_id
            ORDER BY p.fecha_alta DESC
        ";

        $stmt = $db->query($consulta);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        return $stmt->fetchAll();
    }

    public function porId(int $id): ?self
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "
            SELECT p.producto_id, p.nombre, p.precio, p.descripcion_corta, p.descripcion, p.imagen,
                   GROUP_CONCAT(c.nombre ORDER BY c.nombre SEPARATOR ', ') AS categoria
            FROM productos p
            LEFT JOIN productos_tienen_categorias ptc ON p.producto_id = ptc.producto_fk
            LEFT JOIN categorias c ON ptc.categoria_fk = c.categoria_id
            WHERE p.producto_id = :id
            GROUP BY p.producto_id
        ";

        $stmt = $db->prepare($consulta);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        $producto = $stmt->fetch();

        return $producto === false ? null : $producto;
    }

    public static function buscarPorNombre(array $lista, string $nombre): ?self
    {
        foreach ($lista as $producto) {
            if ($producto->getNombre() === $nombre) {
                return $producto;
            }
        }

        return null;
    }

    /**
     * @return array<int, array{categoria_id: int, nombre: string}>
     */
    public function todasCategorias(): array
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "SELECT categoria_id, nombre FROM categorias ORDER BY nombre";
        $stmt = $db->query($consulta);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return int[]
     */
    public function categoriasPorProducto(int $productoId): array
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "
            SELECT categoria_fk
            FROM productos_tienen_categorias
            WHERE producto_fk = :producto_id
            ORDER BY categoria_fk
        ";

        $stmt = $db->prepare($consulta);
        $stmt->execute(['producto_id' => $productoId]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * @param int[] $categoriaIds
     */
    public function crear(
        string $nombre,
        float $precio,
        string $descripcionCorta,
        string $descripcion,
        string $imagen,
        int $usuarioId,
        array $categoriaIds
    ): int {
        $categoriaIds = $this->normalizarCategoriaIds($categoriaIds);

        if ($categoriaIds === []) {
            throw new InvalidArgumentException('Debe indicar al menos una categoría.');
        }

        $db = (new DBConexion)->getConexion();
        $db->beginTransaction();

        try {
            $consulta = "
                INSERT INTO productos (nombre, precio, descripcion_corta, descripcion, imagen, usuario_fk)
                VALUES (:nombre, :precio, :descripcion_corta, :descripcion, :imagen, :usuario_fk)
            ";

            $stmt = $db->prepare($consulta);
            $stmt->execute([
                'nombre' => $nombre,
                'precio' => $precio,
                'descripcion_corta' => $descripcionCorta,
                'descripcion' => $descripcion,
                'imagen' => $imagen,
                'usuario_fk' => $usuarioId,
            ]);

            $productoId = (int) $db->lastInsertId();
            $this->insertarCategorias($db, $productoId, $categoriaIds);

            $db->commit();

            return $productoId;
        } catch (\Throwable $th) {
            $db->rollBack();

            throw $th;
        }
    }

    /**
     * @param int[] $categoriaIds
     */
    public function actualizar(
        int $id,
        string $nombre,
        float $precio,
        string $descripcionCorta,
        string $descripcion,
        string $imagen,
        array $categoriaIds
    ): bool {
        if ($this->porId($id) === null) {
            return false;
        }

        $categoriaIds = $this->normalizarCategoriaIds($categoriaIds);

        if ($categoriaIds === []) {
            throw new InvalidArgumentException('Debe indicar al menos una categoría.');
        }

        $db = (new DBConexion)->getConexion();
        $db->beginTransaction();

        try {
            $consulta = "
                UPDATE productos
                SET nombre = :nombre,
                    precio = :precio,
                    descripcion_corta = :descripcion_corta,
                    descripcion = :descripcion,
                    imagen = :imagen
                WHERE producto_id = :id
            ";

            $stmt = $db->prepare($consulta);
            $stmt->execute([
                'nombre' => $nombre,
                'precio' => $precio,
                'descripcion_corta' => $descripcionCorta,
                'descripcion' => $descripcion,
                'imagen' => $imagen,
                'id' => $id,
            ]);

            $this->reemplazarCategorias($db, $id, $categoriaIds);

            $db->commit();

            return true;
        } catch (\Throwable $th) {
            $db->rollBack();

            throw $th;
        }
    }

    public function eliminar(int $id): bool
    {
        if ($this->porId($id) === null) {
            return false;
        }

        $db = (new DBConexion)->getConexion();

        $consulta = "DELETE FROM productos WHERE producto_id = :id";
        $stmt = $db->prepare($consulta);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * @param int[] $categoriaIds
     * @return int[]
     */
    private function normalizarCategoriaIds(array $categoriaIds): array
    {
        $ids = [];

        foreach ($categoriaIds as $categoriaId) {
            $id = (int) $categoriaId;

            if ($id > 0) {
                $ids[$id] = $id;
            }
        }

        return array_values($ids);
    }

    /**
     * @param int[] $categoriaIds
     */
    private function insertarCategorias(PDO $db, int $productoId, array $categoriaIds): void
    {
        $consulta = "
            INSERT INTO productos_tienen_categorias (producto_fk, categoria_fk)
            VALUES (:producto_fk, :categoria_fk)
        ";

        $stmt = $db->prepare($consulta);

        foreach ($categoriaIds as $categoriaId) {
            $stmt->execute([
                'producto_fk' => $productoId,
                'categoria_fk' => $categoriaId,
            ]);
        }
    }

    /**
     * @param int[] $categoriaIds
     */
    private function reemplazarCategorias(PDO $db, int $productoId, array $categoriaIds): void
    {
        $consulta = "DELETE FROM productos_tienen_categorias WHERE producto_fk = :producto_fk";
        $stmt = $db->prepare($consulta);
        $stmt->execute(['producto_fk' => $productoId]);

        $this->insertarCategorias($db, $productoId, $categoriaIds);
    }

    public function getId(): int
    {
        return $this->producto_id;
    }

    public function setId(int $id): void
    {
        $this->producto_id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function getDescripcionCorta(): string
    {
        return $this->descripcion_corta;
    }

    public function setDescripcionCorta(string $descripcionCorta): void
    {
        $this->descripcion_corta = $descripcionCorta;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }
}
