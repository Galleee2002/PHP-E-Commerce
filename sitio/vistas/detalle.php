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
                <p class="detail-article__text detail-article__text--lead"><?= htmlspecialchars($productoSeleccionado->getDescripcionCorta(), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="detail-article__text"><?= htmlspecialchars($productoSeleccionado->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
            </section>

            <div class="detail-actions">
                <form
                    class="detail-actions__form"
                    method="post"
                    action="index.php?seccion=detalle&amp;id=<?= (int) $productoSeleccionado->getId() ?>"
                    data-qty-form
                >
                    <input type="hidden" name="accion" value="agregar-carrito">
                    <input type="hidden" name="producto_id" value="<?= (int) $productoSeleccionado->getId() ?>">

                    <button class="btn btn--buy-now" type="submit">Añadir al carrito</button>

                    <div class="qty-stepper" role="group" aria-label="Cantidad">
                        <button
                            class="qty-stepper__btn"
                            type="button"
                            data-qty-dec
                            aria-label="Disminuir cantidad"
                        >−</button>
                        <label class="sr-only" for="cantidad">Cantidad</label>
                        <input
                            class="qty-stepper__value"
                            type="number"
                            id="cantidad"
                            name="cantidad"
                            value="1"
                            min="1"
                            max="99"
                            readonly
                            tabindex="-1"
                            aria-live="polite"
                        >
                        <button
                            class="qty-stepper__btn"
                            type="button"
                            data-qty-inc
                            aria-label="Aumentar cantidad"
                        >+</button>
                    </div>
                </form>
            </div>
            <script>
                (function () {
                    const form = document.querySelector('[data-qty-form]');
                    if (!form) {
                        return;
                    }

                    const input = form.querySelector('.qty-stepper__value');
                    const btnDec = form.querySelector('[data-qty-dec]');
                    const btnInc = form.querySelector('[data-qty-inc]');
                    const min = 1;
                    const max = 99;

                    function getCantidad() {
                        const n = parseInt(input.value, 10);
                        return Number.isFinite(n) && n >= min ? n : min;
                    }

                    function setCantidad(n) {
                        const cantidad = Math.min(max, Math.max(min, n));
                        input.value = String(cantidad);
                        btnDec.disabled = cantidad <= min;
                        btnInc.disabled = cantidad >= max;
                    }

                    btnDec.addEventListener('click', function () {
                        setCantidad(getCantidad() - 1);
                    });

                    btnInc.addEventListener('click', function () {
                        setCantidad(getCantidad() + 1);
                    });

                    setCantidad(getCantidad());
                })();
            </script>
        </div>
    </article>
</section>
<?php endif; ?>
