<section class="panel" aria-labelledby="titulo-listado">
    <h1 class="page-title" id="titulo-listado">Listado de productos</h1>
    <?php if (empty($productos)): ?>
        <p class="alert">No hay productos disponibles en este momento.</p>
    <?php else: ?>
        <section aria-label="Catalogo de juegos" class="product-grid">
            <?php foreach ($productos as $producto): ?>
                <article class="product-card">
                    <img class="product-card__img" src="<?= $producto->imagen ?>" alt="<?= $producto->nombre ?>">
                    <h2 class="product-card__title"><?= $producto->nombre ?></h2>
                    <p class="product-card__meta"><strong>Categoria:</strong> <?= $producto->categoria ?></p>
                    <p class="product-card__meta product-card__meta--tight"><strong>Precio:</strong> $<?= number_format($producto->precio, 0, ',', '.') ?></p>
                    <p class="product-card__desc"><?= $producto->descripcionCorta ?></p>
                    <p class="product-card__actions">
                        <a class="btn btn--accent" href="index.php?seccion=detalle&id=<?= urlencode((string) $producto->id) ?>">Ver detalle</a>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</section>
