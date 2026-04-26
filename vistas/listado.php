<section aria-labelledby="titulo-listado">
    <h1 id="titulo-listado">Listado de productos</h1>
    <?php if (empty($productos)): ?>
        <p>No hay productos disponibles en este momento.</p>
    <?php else: ?>
        <section aria-label="Catalogo de juegos">
            <?php foreach ($productos as $producto): ?>
                <article>
                    <h2><?= htmlspecialchars($producto->nombre) ?></h2>
                    <p><strong>Categoria:</strong> <?= htmlspecialchars($producto->categoria) ?></p>
                    <p><strong>Precio:</strong> $<?= number_format($producto->precio, 0, ',', '.') ?></p>
                    <p><?= htmlspecialchars($producto->descripcionCorta) ?></p>
                    <p>
                        <a href="index.php?seccion=detalle&id=<?= urlencode((string) $producto->id) ?>">Ver detalle</a>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</section>