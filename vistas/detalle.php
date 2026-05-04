<?php
require_once __DIR__ . "/../clases/Producto.php";

$idProducto = $_GET["id"] ?? 0;
$productoSeleccionado = (new Producto)->porId($idProducto);
?>

<?php if ($productoSeleccionado === null): ?>
<section class="panel">
    <h1 class="page-title">Producto no encontrado</h1>
    <p class="detail-article__text">No existe un producto para el id indicado.</p>
    <p class="stack-top-lg">
        <a class="btn btn--primary-outline" href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>
<?php else: ?>
<section class="panel">
    <h1 class="page-title">Detalle del producto</h1>
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
<?php endif; ?>
