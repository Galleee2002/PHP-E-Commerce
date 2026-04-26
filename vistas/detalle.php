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

<section class="rounded-2xl border border-primary-dark bg-warm-white p-6" aria-labelledby="titulo-detalle">
    <h1 class="text-3xl font-bold text-soft-black md:text-4xl" id="titulo-detalle">Detalle del producto</h1>
    <?php if ($idProducto <= 0): ?>
        <p class="mt-4 rounded-xl border border-primary-dark bg-beige-light px-4 py-3 text-text-soft">No se selecciono ningun producto para mostrar.</p>
    <?php elseif ($productoSeleccionado === null): ?>
        <p class="mt-4 rounded-xl border border-primary-dark bg-beige-light px-4 py-3 text-text-soft">El producto solicitado no existe.</p>
    <?php else: ?>
        <article class="mt-5 rounded-xl border border-primary-dark bg-beige-light p-4 md:p-6">
            <img class="aspect-[16/10] w-full rounded-lg object-cover" src="<?= $productoSeleccionado->imagen ?>" alt="<?= $productoSeleccionado->nombre ?>">
            <h2 class="mt-4 text-2xl font-semibold text-soft-black"><?= $productoSeleccionado->nombre ?></h2>
            <p class="mt-3 text-text-soft"><strong class="font-semibold text-soft-black">Categoria:</strong> <?= $productoSeleccionado->categoria ?></p>
            <p class="mt-1 text-text-soft"><strong class="font-semibold text-soft-black">Precio:</strong> $<?= $productoSeleccionado->precio ?></p>
            <p class="mt-3 text-text-soft"><?= $productoSeleccionado->descripcionCorta ?></p>
            <p class="mt-3 text-text-soft"><?= $productoSeleccionado->descripcion ?></p>
        </article>
    <?php endif; ?>
    <p class="mt-5">
        <a class="inline-flex items-center rounded-lg border border-primary-dark bg-primary px-4 py-2 text-sm font-semibold text-warm-white hover:bg-primary-dark" href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>