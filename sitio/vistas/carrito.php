<?php
$items = $carrito->obtenerItems();
$total = $carrito->calcularTotal();
$productoModel = new Producto();
?>
<section class="panel panel--carrito" aria-labelledby="titulo-carrito">
    <header class="carrito-header">
        <h1 class="page-title" id="titulo-carrito">Carrito</h1>
        <p class="carrito-header__lead">Productos que agregaste para comprar.</p>
    </header>

    <?php if ($items === []): ?>
        <div class="carrito-vacio">
            <p>Tu carrito está vacío.</p>
            <p>
                <a class="btn btn--accent" href="index.php?seccion=listado">Ver listado</a>
            </p>
        </div>
    <?php else: ?>
        <div class="carrito-table-wrap">
            <table class="carrito-table">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $productoId => $item): ?>
                        <?php
                        $cantidad = (int) $item['cantidad'];
                        $precio = (float) $item['precio'];
                        $subtotal = $precio * $cantidad;
                        $nombre = (string) $item['nombre'];
                        $imagen = (string) ($item['imagen'] ?? '');

                        if ($imagen === '') {
                            $producto = $productoModel->porId((int) $productoId);
                            if ($producto !== null) {
                                $imagen = $producto->getImagen();
                            }
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="carrito-producto">
                                    <?php if ($imagen !== ''): ?>
                                        <img
                                            class="carrito-producto__img"
                                            src="<?= htmlspecialchars($imagen, ENT_QUOTES, 'UTF-8') ?>"
                                            alt=""
                                            width="56"
                                            height="56"
                                        >
                                    <?php else: ?>
                                        <span class="carrito-producto__img carrito-producto__img--empty" aria-hidden="true"></span>
                                    <?php endif; ?>
                                    <span class="carrito-producto__nombre"><?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </td>
                            <td class="carrito-precio">$<?= number_format($precio, 0, ',', '.') ?></td>
                            <td>
                                <div class="carrito-qty" role="group" aria-label="Cantidad de <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>">
                                    <form class="carrito-qty__form" method="post" action="index.php?seccion=carrito">
                                        <input type="hidden" name="accion" value="actualizar-cantidad">
                                        <input type="hidden" name="producto_id" value="<?= (int) $productoId ?>">
                                        <input type="hidden" name="cantidad" value="<?= $cantidad - 1 ?>">
                                        <button
                                            class="carrito-qty__btn"
                                            type="submit"
                                            <?= $cantidad <= 1 ? 'disabled' : '' ?>
                                            aria-label="Disminuir cantidad"
                                        >−</button>
                                    </form>
                                    <span class="carrito-qty__value" aria-live="polite"><?= $cantidad ?></span>
                                    <form class="carrito-qty__form" method="post" action="index.php?seccion=carrito">
                                        <input type="hidden" name="accion" value="actualizar-cantidad">
                                        <input type="hidden" name="producto_id" value="<?= (int) $productoId ?>">
                                        <input type="hidden" name="cantidad" value="<?= $cantidad + 1 ?>">
                                        <button
                                            class="carrito-qty__btn"
                                            type="submit"
                                            <?= $cantidad >= 99 ? 'disabled' : '' ?>
                                            aria-label="Aumentar cantidad"
                                        >+</button>
                                    </form>
                                </div>
                            </td>
                            <td class="carrito-subtotal">$<?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td>
                                <form method="post" action="index.php?seccion=carrito">
                                    <input type="hidden" name="accion" value="quitar-carrito">
                                    <input type="hidden" name="producto_id" value="<?= (int) $productoId ?>">
                                    <button class="carrito-quitar" type="submit" aria-label="Quitar <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?> del carrito">
                                        <svg class="carrito-quitar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                                            <path d="M9 3h6M4 7h16M7 7l1 13h8l1-13M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="carrito-footer">
            <a class="carrito-seguir" href="index.php?seccion=listado">← Seguir comprando</a>

            <div class="carrito-resumen">
                <p class="carrito-total">
                    <span class="carrito-total__label">Total</span>
                    <span class="carrito-total__value">$<?= number_format($total, 0, ',', '.') ?></span>
                </p>

                <form class="carrito-completar" method="post" action="index.php?seccion=carrito">
                    <input type="hidden" name="accion" value="completar-compra">
                    <button class="btn btn--accent carrito-completar__btn" type="submit">Completar compra</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</section>
