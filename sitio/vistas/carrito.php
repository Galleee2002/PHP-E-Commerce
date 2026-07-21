<?php
/** @var Carrito $carrito */
$carrito = $carrito ?? new Carrito();
$items = $carrito->obtenerItems();
$mensajeCarrito = $mensajeCarrito ?? '';
$errorCarrito = $errorCarrito ?? '';
$total = $carrito->calcularTotal();
?>
<section class="panel panel--carrito" aria-labelledby="titulo-carrito">
    <header class="carrito-header">
        <h1 class="page-title" id="titulo-carrito">Carrito</h1>
        <p class="carrito-header__lead">Productos que agregaste para comprar.</p>
    </header>

    <?php if ($mensajeCarrito !== ''): ?>
        <p class="cuenta-alert cuenta-alert--success" role="status"><?= htmlspecialchars($mensajeCarrito, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($errorCarrito !== ''): ?>
        <p class="cuenta-alert cuenta-alert--error" role="alert"><?= htmlspecialchars($errorCarrito, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

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
                        <th scope="col"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $productoId => $item): ?>
                        <?php
                        $cantidad = (int) $item['cantidad'];
                        $precio = (float) $item['precio'];
                        $subtotal = $precio * $cantidad;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $item['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>$<?= number_format($precio, 0, ',', '.') ?></td>
                            <td><?= $cantidad ?></td>
                            <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td>
                                <form method="post" action="index.php?seccion=carrito">
                                    <input type="hidden" name="accion" value="quitar-carrito">
                                    <input type="hidden" name="producto_id" value="<?= (int) $productoId ?>">
                                    <button class="carrito-quitar" type="submit">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="carrito-total">
            <span class="carrito-total__label">Total</span>
            <span class="carrito-total__value">$<?= number_format($total, 0, ',', '.') ?></span>
        </p>
    <?php endif; ?>
</section>
