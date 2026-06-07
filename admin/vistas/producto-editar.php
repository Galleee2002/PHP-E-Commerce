<?php

/**
 * B5 — Edición de producto (backend).
 * $categorias: array cargado en admin/index.php vía Producto::todasCategorias().
 * GET: ?seccion=producto-editar&id=N
 * POST: producto_id, nombre, precio, descripcion_corta, descripcion, imagen, categoria_id.
 */

$errorEdicion = '';
$producto = null;
$categoriasProducto = [];
$valoresEdicion = [
    'producto_id' => 0,
    'nombre' => '',
    'precio' => '',
    'descripcion_corta' => '',
    'descripcion' => '',
    'imagen' => '',
    'categoria_id' => 0,
];

$idProducto = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = (int) ($_POST['producto_id'] ?? $idProducto);

    $valoresEdicion = [
        'producto_id' => $idProducto,
        'nombre' => trim($_POST['nombre'] ?? ''),
        'precio' => trim($_POST['precio'] ?? ''),
        'descripcion_corta' => trim($_POST['descripcion_corta'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'imagen' => trim($_POST['imagen'] ?? ''),
        'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
    ];

    $precio = (float) str_replace(',', '.', $valoresEdicion['precio']);

    if ($idProducto <= 0) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    if (
        $valoresEdicion['nombre'] === ''
        || $valoresEdicion['precio'] === ''
        || $precio <= 0
        || $valoresEdicion['descripcion_corta'] === ''
        || $valoresEdicion['descripcion'] === ''
        || $valoresEdicion['imagen'] === ''
        || $valoresEdicion['categoria_id'] <= 0
    ) {
        $errorEdicion = 'Completá todos los campos obligatorios con valores válidos.';
        $producto = (new Producto())->porId($idProducto);
        $categoriasProducto = [$valoresEdicion['categoria_id']];
    } else {
        try {
            $actualizado = (new Producto())->actualizar(
                $idProducto,
                $valoresEdicion['nombre'],
                $precio,
                $valoresEdicion['descripcion_corta'],
                $valoresEdicion['descripcion'],
                $valoresEdicion['imagen'],
                [$valoresEdicion['categoria_id']]
            );

            if (!$actualizado) {
                header('Location: index.php?seccion=productos');
                exit;
            }

            header('Location: index.php?seccion=productos');
            exit;
        } catch (InvalidArgumentException $exception) {
            $errorEdicion = $exception->getMessage();
            $producto = (new Producto())->porId($idProducto);
            $categoriasProducto = [$valoresEdicion['categoria_id']];
        }
    }
} else {
    if ($idProducto <= 0) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    $producto = (new Producto())->porId($idProducto);

    if ($producto === null) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    $categoriasProducto = (new Producto())->categoriasPorProducto($idProducto);

    $valoresEdicion = [
        'producto_id' => $producto->getId(),
        'nombre' => $producto->getNombre(),
        'precio' => (string) $producto->getPrecio(),
        'descripcion_corta' => $producto->getDescripcionCorta(),
        'descripcion' => $producto->getDescripcion(),
        'imagen' => $producto->getImagen(),
        'categoria_id' => $categoriasProducto[0] ?? 0,
    ];
}

?>
<!-- TODO (frontend + Fase 5): formulario pre-poblado de edición -->
<section>
    <h1>Editar producto #<?= (int) $valoresEdicion['producto_id'] ?></h1>
    <p><a href="index.php?seccion=productos">Volver al listado</a></p>
</section>
