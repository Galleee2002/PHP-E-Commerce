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
