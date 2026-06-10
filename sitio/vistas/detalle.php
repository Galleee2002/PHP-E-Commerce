<?php
require_once __DIR__ . "/../clases/Producto.php";

$idProducto = (int) ($_GET["id"] ?? 0);
$productoSeleccionado = (new Producto)->porId($idProducto);
?>

<?php if ($productoSeleccionado === null): ?>
<section class="panel panel--detalle">
    <h1 class="page-title">Producto no encontrado</h1>
    <p class="detail-article__text">No existe un producto para el id indicado.</p>
    <p class="stack-top-lg">
        <a class="btn btn--primary-outline" href="index.php?seccion=listado">Volver al listado</a>
    </p>
</section>
<?php else: ?>
<section class="panel panel--detalle">
    <p class="detail-back">
        <a class="detail-back__link" href="index.php?seccion=listado">
            <svg class="detail-back__icon" width="14" height="14" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M12.5 4.167L6.667 10l5.833 5.833" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Volver al listado
        </a>
    </p>
    <article class="detail-article">
        <figure class="detail-article__media">
            <img class="detail-article__img" src="<?= htmlspecialchars($productoSeleccionado->getImagen(), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($productoSeleccionado->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
        </figure>

        <div class="detail-article__content">
            <header class="detail-article__intro">
                <div class="detail-article__title-row">
                    <h1 class="detail-article__title"><?= htmlspecialchars($productoSeleccionado->getNombre(), ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="detail-article__price">$<?= number_format($productoSeleccionado->getPrecio(), 0, ',', '.') ?></p>
                </div>
                <p class="detail-article__category">
                    <span class="detail-article__category-label">Categoría</span>
                    <?= htmlspecialchars($productoSeleccionado->getCategoria(), ENT_QUOTES, 'UTF-8') ?>
                </p>
            </header>

            <section class="detail-article__section detail-article__section--description">
                <h2 class="detail-article__section-title">Descripción</h2>
                <p class="detail-article__text"><?= htmlspecialchars($productoSeleccionado->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
            </section>

            <div class="detail-actions">
                <a class="btn btn--buy-now" href="#" aria-label="Comprar ahora">Comprar ahora</a>
                <a class="btn btn--cta-icon" href="#" aria-label="Añadir a favoritos" title="Añadir a favoritos">
                    <svg class="btn__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12.001 20.327s-6.73-4.364-9.116-7.917a5.44 5.44 0 0 1 .648-6.955 5.213 5.213 0 0 1 7.521.657l.947 1.14.947-1.14a5.213 5.213 0 0 1 7.521-.657 5.44 5.44 0 0 1 .648 6.955c-2.386 3.553-9.116 7.917-9.116 7.917Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="sr-only">Añadir a favoritos</span>
                </a>
                <a class="btn btn--cta-icon" href="#" aria-label="Añadir al carrito" title="Añadir al carrito">
                    <svg class="btn__icon" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3.5 5h2.1l1.34 9.108a1 1 0 0 0 .99.855h9.89a1 1 0 0 0 .977-.79L20.2 8.5H7.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.4 19a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4Zm7.2 0a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4Z" fill="currentColor"/>
                    </svg>
                    <span class="sr-only">Añadir al carrito</span>
                </a>
            </div>
        </div>
    </article>
</section>
<?php endif; ?>
