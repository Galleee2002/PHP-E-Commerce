<?php

require_once __DIR__ . '/../../clases/Producto.php';

$producto = new Producto;
$categorias = $producto->todasCategorias();

$errorAlta = '';
$valoresAlta = [
    'nombre' => '',
    'precio' => '',
    'descripcion_corta' => '',
    'descripcion' => '',
    'imagen' => '',
    'categoria_id' => 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valoresAlta = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'precio' => trim($_POST['precio'] ?? ''),
        'descripcion_corta' => trim($_POST['descripcion_corta'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'imagen' => trim($_POST['imagen'] ?? ''),
        'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
    ];

    $precio = (float) str_replace(',', '.', $valoresAlta['precio']);

    if (
        $valoresAlta['nombre'] === ''
        || $valoresAlta['precio'] === ''
        || $precio <= 0
        || $valoresAlta['descripcion_corta'] === ''
        || $valoresAlta['descripcion'] === ''
        || $valoresAlta['imagen'] === ''
        || $valoresAlta['categoria_id'] <= 0
    ) {
        $errorAlta = 'Completá todos los campos obligatorios con valores válidos.';
    } else {
        try {
            $producto->crear(
                $valoresAlta['nombre'],
                $precio,
                $valoresAlta['descripcion_corta'],
                $valoresAlta['descripcion'],
                $valoresAlta['imagen'],
                Usuario::idEnSesion(),
                $valoresAlta['categoria_id']
            );

            header('Location: index.php?seccion=productos');
            exit;
        } catch (InvalidArgumentException $exception) {
            $errorAlta = $exception->getMessage();
        }
    }
}

?>
<!-- TODO (frontend + Fase 5): formulario de alta de producto -->
<section>
    <h1>Alta de producto</h1>
    <p><a href="index.php?seccion=productos">Volver al listado</a></p>
</section>
