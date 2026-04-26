<?php
$idProducto = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$productoSeleccionado = null;

if ($idProducto > 0) {
    foreach ($productos as $producto) {
        if ($producto->id === $idProducto) {
            $productoSeleccionado = $producto;
            break;
        }
    }
}
?>

<section aria-labelledby="titulo-detalle">
    <h1 id="titulo-detalle">Detalle del producto</h1>
    <?php if ($idProducto <= 0): ?>
        <p>No se selecciono ningun producto para mostrar.</p>
    <?php elseif ($productoSeleccionado === null): ?>
        <p>El producto solicitado no existe.</p>
    <?php else: ?>
        <article class="detalle-producto">
            <img src="<?= $productoSeleccionado->imagen ?>" alt="<?= $productoSeleccionado->nombre ?>">
            <h2><?= $productoSeleccionado->nombre ?></h2>
            <p><strong>Categoria:</strong> <?= $productoSeleccionado->categoria ?></p>
            <p><strong>Precio:</strong> $<?= $productoSeleccionado->precio ?></p>
            <p><?= $productoSeleccionado->descripcionCorta ?></p>
            <p><?= $productoSeleccionado->descripcion ?></p>
        </article>
    <?php endif; ?>
    <p>
        <a href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>