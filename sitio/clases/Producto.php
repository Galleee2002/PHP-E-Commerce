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

        $stmt = $db->prepare($consulta);
        $stmt->execute();
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

        if (!$producto) {
            return null;
        }

        return $producto;
    }

    public function todasCategorias(): array
    {
        $db = (new DBConexion)->getConexion();

        $consulta = "SELECT categoria_id, nombre FROM categorias ORDER BY nombre";
        $stmt = $db->prepare($consulta);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function crear(
        string $nombre,
        float $precio,
        string $descripcionCorta,
        string $descripcion,
        string $imagen,
        int $usuarioId,
        int $categoriaId
    ): int {
        if ($categoriaId <= 0) {
            throw new InvalidArgumentException('Debe indicar al menos una categoría.');
        }

        $db = (new DBConexion)->getConexion();

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

        $consulta = "
            INSERT INTO productos_tienen_categorias (producto_fk, categoria_fk)
            VALUES (:producto_fk, :categoria_fk)
        ";

        $stmt = $db->prepare($consulta);
        $stmt->execute([
            'producto_fk' => $productoId,
            'categoria_fk' => $categoriaId,
        ]);

        return $productoId;
    }

    public function actualizar(
        int $id,
        string $nombre,
        float $precio,
        string $descripcionCorta,
        string $descripcion,
        string $imagen,
        int $categoriaId
    ): bool {
        if ($this->porId($id) === null) {
            return false;
        }

        if ($categoriaId <= 0) {
            throw new InvalidArgumentException('Debe indicar al menos una categoría.');
        }

        $db = (new DBConexion)->getConexion();

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

        $consulta = "DELETE FROM productos_tienen_categorias WHERE producto_fk = :producto_fk";
        $stmt = $db->prepare($consulta);
        $stmt->execute(['producto_fk' => $id]);

        $consulta = "
            INSERT INTO productos_tienen_categorias (producto_fk, categoria_fk)
            VALUES (:producto_fk, :categoria_fk)
        ";

        $stmt = $db->prepare($consulta);
        $stmt->execute([
            'producto_fk' => $id,
            'categoria_fk' => $categoriaId,
        ]);

        return true;
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
