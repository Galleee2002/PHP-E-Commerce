<?php

require_once __DIR__ . '/../../clases/Usuario.php';
require_once __DIR__ . '/../../clases/Compra.php';

$usuarioEmail = Usuario::emailEnSesion() ?? '';

$idUsuario = (int) ($_GET['id'] ?? 0);
$usuario = null;
$errorDetalle = '';
$compras = [];
$detallesPorCompra = [];

if ($idUsuario <= 0) {
    $errorDetalle = 'No se indicó un usuario válido.';
} else {
    $usuario = (new Usuario)->porId($idUsuario);

    if ($usuario === null) {
        $errorDetalle = 'El usuario solicitado no existe.';
    } else {
        $compraModel = new Compra;
        $compras = $compraModel->porUsuario($idUsuario);

        foreach ($compras as $compraCabecera) {
            $compraId = (int) $compraCabecera['compra_id'];
            $detalle = $compraModel->porId($compraId);
            $detallesPorCompra[$compraId] = $detalle['productos'] ?? [];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de usuario | Galmir Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/productos.css">
</head>

<body class="admin-productos">
    <header class="admin-productos__topbar">
        <div class="admin-productos__brand">
            <div>
                <p class="admin-productos__logo-name">GALMIR</p>
                <p class="admin-productos__logo-tagline">Juegos de mesa</p>
            </div>
        </div>
        <nav class="admin-productos__nav" aria-label="Secciones del panel">
            <a class="admin-productos__nav-link" href="index.php?seccion=productos">Productos</a>
            <a class="admin-productos__nav-link admin-productos__nav-link--active" href="index.php?seccion=usuarios" aria-current="page">Usuarios</a>
        </nav>
        <div class="admin-productos__session">
            <details class="admin-productos__profile">
                <summary class="admin-productos__profile-toggle">
                    <span class="admin-productos__avatar" aria-hidden="true">A</span>
                    <span class="admin-productos__profile-text">
                        <span class="admin-productos__profile-name">Administrador</span>
                        <span class="admin-productos__profile-email"><?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?></span>
                    </span>
                    <svg class="admin-productos__profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </summary>
                <div class="admin-productos__profile-menu">
                    <a class="admin-productos__profile-logout" href="index.php?seccion=salir">Cerrar sesión</a>
                </div>
            </details>
        </div>
    </header>

    <main class="admin-productos__main">
        <div class="admin-productos__card">
            <header class="admin-productos__header">
                <div>
                    <h1 class="admin-productos__title">Detalle de usuario</h1>
                    <p class="admin-productos__subtitle">Datos de la cuenta e historial de compras.</p>
                    <nav aria-label="Ruta de navegación">
                        <ol class="admin-productos__breadcrumb">
                            <li><a href="index.php?seccion=usuarios">Usuarios</a></li>
                            <li class="admin-productos__breadcrumb-sep" aria-hidden="true">›</li>
                            <li aria-current="page">Detalle</li>
                        </ol>
                    </nav>
                </div>
                <a class="admin-productos__add" href="index.php?seccion=usuarios">Volver al listado</a>
            </header>

            <?php if ($errorDetalle !== ''): ?>
                <p class="admin-productos__alert" role="alert"><?= htmlspecialchars($errorDetalle, ENT_QUOTES, 'UTF-8') ?></p>
            <?php else: ?>
                <section class="admin-productos__user-block" aria-labelledby="usuario-datos-titulo">
                    <h2 id="usuario-datos-titulo" class="admin-productos__section-title">Datos del usuario</h2>
                    <dl class="admin-productos__user-dl">
                        <div>
                            <dt>ID</dt>
                            <dd><?= (int) $usuario->getId() ?></dd>
                        </div>
                        <div>
                            <dt>Nombre</dt>
                            <dd><?= htmlspecialchars($usuario->getNombre(), ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                        <div>
                            <dt>Apellido</dt>
                            <dd><?= htmlspecialchars($usuario->getApellido(), ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd><?= htmlspecialchars($usuario->getEmail(), ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                        <div>
                            <dt>Rol</dt>
                            <dd>
                                <span class="admin-productos__rol admin-productos__rol--<?= htmlspecialchars($usuario->getRol(), ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($usuario->getRol(), ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="admin-productos__compras-block" aria-labelledby="usuario-compras-titulo">
                    <h2 id="usuario-compras-titulo" class="admin-productos__section-title">Historial de compras</h2>

                    <?php if (count($compras) === 0): ?>
                        <p class="admin-productos__empty-msg">Sin compras.</p>
                    <?php else: ?>
                        <?php foreach ($compras as $compraCabecera): ?>
                            <?php
                            $compraId = (int) $compraCabecera['compra_id'];
                            $lineas = $detallesPorCompra[$compraId] ?? [];
                            $fecha = (string) ($compraCabecera['fecha'] ?? '');
                            $total = (float) ($compraCabecera['total'] ?? 0);
                            ?>
                            <article class="admin-productos__compra">
                                <header class="admin-productos__compra-head">
                                    <div class="admin-productos__compra-meta">
                                        <h3 class="admin-productos__compra-title">Compra #<?= $compraId ?></h3>
                                        <span class="admin-productos__compra-fecha">· <?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <p class="admin-productos__compra-total">
                                        Total: $<?= number_format($total, 2, ',', '.') ?>
                                    </p>
                                </header>

                                <div class="admin-productos__table-wrap admin-productos__table-wrap--nested">
                                    <table class="admin-productos__table admin-productos__table--datos">
                                        <thead>
                                            <tr>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Precio unitario</th>
                                                <th scope="col">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($lineas) === 0): ?>
                                                <tr>
                                                    <td class="admin-productos__empty" colspan="4">Sin ítems en esta compra.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($lineas as $linea): ?>
                                                    <?php
                                                    $cantidad = (int) ($linea['cantidad'] ?? 0);
                                                    $precioUnitario = (float) ($linea['precio_unitario'] ?? 0);
                                                    $subtotal = $cantidad * $precioUnitario;
                                                    ?>
                                                    <tr>
                                                        <td data-label="Producto"><?= htmlspecialchars((string) ($linea['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                                        <td data-label="Cantidad"><?= $cantidad ?></td>
                                                        <td data-label="Precio unitario">$<?= number_format($precioUnitario, 2, ',', '.') ?></td>
                                                        <td data-label="Subtotal">$<?= number_format($subtotal, 2, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
