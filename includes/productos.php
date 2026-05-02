<?php
require_once __DIR__ . '/../clases/Producto.php';

function cargarProductos()
{
    $rutaProductos = __DIR__ . '/../data/productos.json';

    if (!file_exists($rutaProductos)) {
        return [];
    }

    $json = file_get_contents($rutaProductos);

    if ($json === false) {
        return [];
    }

    $datos = json_decode($json, true);

    if (!is_array($datos)) {
        return [];
    }

    $productos = [];

    foreach ($datos as $item) {
        $productos[] = new Producto(
            isset($item['id']) ? (int) $item['id'] : 0,
            isset($item['nombre']) ? (string) $item['nombre'] : '',
            isset($item['precio']) ? (float) $item['precio'] : 0,
            isset($item['categoria']) ? (string) $item['categoria'] : '',
            isset($item['descripcion_corta']) ? (string) $item['descripcion_corta'] : '',
            isset($item['descripcion']) ? (string) $item['descripcion'] : '',
            isset($item['imagen']) ? (string) $item['imagen'] : ''
        );
    }

    return $productos;
}
