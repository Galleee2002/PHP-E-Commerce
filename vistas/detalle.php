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

<section class="panel" aria-labelledby="titulo-detalle">
    <h1 class="page-title" id="titulo-detalle">Detalle del producto</h1>
    <?php if ($idProducto <= 0): ?>
        <p class="alert">No se selecciono ningun producto para mostrar.</p>
    <?php elseif ($productoSeleccionado === null): ?>
        <p class="alert">El producto solicitado no existe.</p>
    <?php else: ?>
        <article class="detail-article">
            <img class="detail-article__img" src="<?= $productoSeleccionado->imagen ?>" alt="<?= $productoSeleccionado->nombre ?>">
            <h2 class="detail-article__title"><?= $productoSeleccionado->nombre ?></h2>
            <p class="detail-article__meta"><strong>Categoria:</strong> <?= $productoSeleccionado->categoria ?></p>
            <p class="detail-article__meta detail-article__meta--tight"><strong>Precio:</strong> $<?= $productoSeleccionado->precio ?></p>
            <p class="detail-article__text"><?= $productoSeleccionado->descripcionCorta ?></p>
            <p class="detail-article__text"><?= $productoSeleccionado->descripcion ?></p>
        </article>
    <?php endif; ?>
    <p class="stack-top-lg">
        <a class="btn btn--primary-outline" href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>
