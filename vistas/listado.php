<section class="rounded-2xl border border-primary-dark bg-warm-white p-6" aria-labelledby="titulo-listado">
    <h1 class="text-3xl font-bold text-soft-black md:text-4xl" id="titulo-listado">Listado de productos</h1>
    <?php if (empty($productos)): ?>
        <p class="mt-4 rounded-xl border border-primary-dark bg-beige-light px-4 py-3 text-text-soft">No hay productos disponibles en este momento.</p>
    <?php else: ?>
        <section aria-label="Catalogo de juegos" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <?php foreach ($productos as $producto): ?>
                <article class="flex h-full flex-col rounded-xl border border-primary-dark bg-beige-light p-4">
                    <img class="aspect-[4/3] w-full rounded-lg object-cover" src="<?= $producto->imagen ?>" alt="<?= $producto->nombre ?>">
                    <h2 class="mt-3 text-xl font-semibold text-soft-black"><?= $producto->nombre ?></h2>
                    <p class="mt-2 text-sm text-text-soft"><strong class="font-semibold text-soft-black">Categoria:</strong> <?= $producto->categoria ?></p>
                    <p class="mt-1 text-sm text-text-soft"><strong class="font-semibold text-soft-black">Precio:</strong> $<?= number_format($producto->precio, 0, ',', '.') ?></p>
                    <p class="mt-2 text-sm text-text-soft"><?= $producto->descripcionCorta ?></p>
                    <p class="mt-4">
                        <a class="inline-flex items-center rounded-lg border border-accent bg-accent px-3 py-2 text-sm font-semibold text-warm-white hover:bg-primary-dark" href="index.php?seccion=detalle&id=<?= urlencode((string) $producto->id) ?>">Ver detalle</a>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</section>