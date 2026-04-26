<section aria-labelledby="titulo-listado">
    <h1 id="titulo-listado">Listado de productos</h1>
    <?php if (empty($productos)): ?>
        <p>No hay productos disponibles en este momento.</p>
    <?php else: ?>
        <section aria-label="Catalogo de juegos" class="catalogo-productos">
            <?php foreach ($productos as $producto): ?>
                <article class="tarjeta-producto">
                    <img src="<?= $producto->imagen ?>" alt="<?= $producto->nombre ?>">
                    <h2><?= $producto->nombre ?></h2>
                    <p><strong>Categoria:</strong> <?= $producto->categoria ?></p>
                    <p><strong>Precio:</strong> $<?= number_format($producto->precio, 0, ',', '.') ?></p>
                    <p><?= $producto->descripcionCorta ?></p>
                    <p>
                        <a class="enlace-detalle" href="index.php?seccion=detalle&id=<?= urlencode((string) $producto->id) ?>">Ver detalle</a>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</section>