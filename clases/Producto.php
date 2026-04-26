<?php
class Producto
{
    public $id;
    public $nombre;
    public $precio;
    public $categoria;
    public $descripcionCorta;
    public $descripcion;
    public $imagen;

    public function __construct(
        $id,
        $nombre,
        $precio,
        $categoria,
        $descripcionCorta,
        $descripcion,
        $imagen
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->categoria = $categoria;
        $this->descripcionCorta = $descripcionCorta;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
    }
}
