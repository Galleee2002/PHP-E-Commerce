<?php
$idProducto = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$productoSeleccionado = null;

foreach ($productos as $producto) {
    if ($producto->id === $idProducto) {
        $productoSeleccionado = $producto;
        break;
    }
}
?>

<section aria-labelledby="titulo-detalle">
    <h1 id="titulo-detalle">Detalle del producto</h1>
    <?php if ($productoSeleccionado === null): ?>
        <p>El producto solicitado no existe o no fue seleccionado.</p>
    <?php else: ?>
        <article>
            <h2><?= htmlspecialchars($productoSeleccionado->nombre) ?></h2>
            <p><strong>Categoria:</strong> <?= htmlspecialchars($productoSeleccionado->categoria) ?></p>
            <p><strong>Precio:</strong> $<?= number_format($productoSeleccionado->precio, 0, ',', '.') ?></p>
            <p><?= htmlspecialchars($productoSeleccionado->descripcion) ?></p>
        </article>
    <?php endif; ?>
    <p>
        <a href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>