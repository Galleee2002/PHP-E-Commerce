<?php

require_once __DIR__ . '/../../clases/Producto.php';

$productoModel = new Producto;
$producto = null;
$idProducto = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = (int) ($_POST['producto_id'] ?? $idProducto);

    if ($idProducto <= 0) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    $productoModel->eliminar($idProducto);

    header('Location: index.php?seccion=productos');
    exit;
}

if ($idProducto <= 0) {
    header('Location: index.php?seccion=productos');
    exit;
}

$producto = $productoModel->porId($idProducto);

if ($producto === null) {
    header('Location: index.php?seccion=productos');
    exit;
}

?>

<section>
    <h1>Borrar producto #<?= (int) $producto->getId() ?></h1>
    <p><a href="index.php?seccion=productos">Volver al listado</a></p>
</section>
