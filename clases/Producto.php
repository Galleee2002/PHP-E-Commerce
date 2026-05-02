<?php

const PRODUCTOS_JSON_ARCHIVO = './data/productos.json';

class Producto
{
    private int $id = 0;
    private string $nombre = '';
    private float $precio = 0.0;
    private string $categoria = '';
    private string $descripcionCorta = '';
    private string $descripcion = '';
    private string $imagen = '';

    public function todas(): array
    {
        $json = file_get_contents(PRODUCTOS_JSON_ARCHIVO);
        $datos = json_decode($json, true);

        $productos = [];

        foreach ($datos as $item) {
            $producto = new self;
            $producto->setId((int) $item['id']);
            $producto->setNombre((string) $item['nombre']);
            $producto->setPrecio((float) $item['precio']);
            $producto->setCategoria((string) $item['categoria']);
            $producto->setDescripcionCorta((string) $item['descripcion_corta']);
            $producto->setDescripcion((string) $item['descripcion']);
            $producto->setImagen((string) $item['imagen']);
            $productos[] = $producto;
        }

        return $productos;
    }

    public function porId(int $id): ?self
    {
        $productos = $this->todas();

        foreach ($productos as $producto) {
            if ($producto->getId() == $id) {
                return $producto;
            }
        }

        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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
        return $this->descripcionCorta;
    }

    public function setDescripcionCorta(string $descripcionCorta): void
    {
        $this->descripcionCorta = $descripcionCorta;
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
