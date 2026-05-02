<?php
require_once __DIR__ . '/../clases/Producto.php';

$productoSeleccionado = (new Producto)->porId($_GET['id']);
?>

<section class="panel" aria-labelledby="titulo-detalle">
    <h1 class="page-title" id="titulo-detalle">Detalle del producto</h1>
    <article class="detail-article">
        <img class="detail-article__img" src="<?= $productoSeleccionado->getImagen() ?>" alt="<?= $productoSeleccionado->getNombre() ?>">
        <h2 class="detail-article__title"><?= $productoSeleccionado->getNombre() ?></h2>
        <p class="detail-article__meta"><strong>Categoria:</strong> <?= $productoSeleccionado->getCategoria() ?></p>
        <p class="detail-article__meta detail-article__meta--tight"><strong>Precio:</strong> $<?= $productoSeleccionado->getPrecio() ?></p>
        <p class="detail-article__text"><?= $productoSeleccionado->getDescripcionCorta() ?></p>
        <p class="detail-article__text"><?= $productoSeleccionado->getDescripcion() ?></p>
    </article>
    <p class="stack-top-lg">
        <a class="btn btn--primary-outline" href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>
