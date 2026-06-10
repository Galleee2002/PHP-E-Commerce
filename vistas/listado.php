<?php
require_once __DIR__ . '/../clases/Producto.php';
$producto = new Producto;
$productos = $producto->todas();
?>
<section class="listado" aria-labelledby="titulo-listado">
    <h1 class="page-title" id="titulo-listado">Listado de productos</h1>
    <section aria-label="Catálogo de juegos" class="product-grid">
        <?php foreach ($productos as $producto): ?>
            <article class="product-card">
                <img class="product-card__img" src="<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
                <h2 class="product-card__title"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="product-card__meta"><strong>Categoría:</strong> <?= htmlspecialchars($producto->getCategoria(), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="product-card__meta product-card__meta--tight"><strong>Precio:</strong> $<?= number_format($producto->getPrecio(), 0, ',', '.') ?></p>
                <p class="product-card__desc"><?= htmlspecialchars($producto->getDescripcionCorta(), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="product-card__actions">
                    <a class="btn btn--accent" href="index.php?seccion=detalle&amp;id=<?= (int) $producto->getId() ?>">Ver detalle</a>
                </p>
            </article>
        <?php endforeach; ?>
    </section>
</section>
