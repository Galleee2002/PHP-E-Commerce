<section class="listado" aria-labelledby="titulo-listado">
    <h1 class="page-title" id="titulo-listado">Listado de productos</h1>
    <section aria-label="Catalogo de juegos" class="product-grid">
        <?php foreach (($productos ?? []) as $producto): ?>
            <article class="product-card">
                <img class="product-card__img" src="<?= $producto->getImagen() ?>" alt="<?= $producto->getNombre() ?>">
                <h2 class="product-card__title"><?= $producto->getNombre() ?></h2>
                <p class="product-card__meta"><strong>Categoria:</strong> <?= $producto->getCategoria() ?></p>
                <p class="product-card__meta product-card__meta--tight"><strong>Precio:</strong> $<?= number_format($producto->getPrecio(), 0, ',', '.') ?></p>
                <p class="product-card__desc"><?= $producto->getDescripcionCorta() ?></p>
                <p class="product-card__actions">
                    <a class="btn btn--accent" href="index.php?seccion=detalle&id=<?= urlencode((string) $producto->getId()) ?>">Ver detalle</a>
                </p>
            </article>
        <?php endforeach; ?>
    </section>
</section>
