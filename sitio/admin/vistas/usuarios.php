<?php

require_once __DIR__ . '/../../clases/Usuario.php';

$usuarioModel = new Usuario;
$usuarios = $usuarioModel->todos();

$usuarioEmail = Usuario::emailEnSesion() ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | Galmir Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/productos.css?v=20260725-1">
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
                    <h1 class="admin-productos__title">Panel — Usuarios</h1>
                    <p class="admin-productos__subtitle">Consultá los usuarios registrados y su historial de compras.</p>
                </div>
            </header>

            <div class="admin-productos__table-wrap">
                <table class="admin-productos__table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($usuarios) === 0): ?>
                            <tr>
                                <td class="admin-productos__empty" colspan="6">No hay usuarios registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= (int) $usuario->getId() ?></td>
                                    <td><?= htmlspecialchars($usuario->getNombre(), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($usuario->getApellido(), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($usuario->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="admin-productos__rol admin-productos__rol--<?= htmlspecialchars($usuario->getRol(), ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($usuario->getRol(), ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a
                                            class="admin-productos__link-detalle"
                                            href="index.php?seccion=usuario-detalle&amp;id=<?= (int) $usuario->getId() ?>"
                                        >
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <footer class="admin-productos__footer">
                Mostrando <?= count($usuarios) ?> usuario<?= count($usuarios) === 1 ? '' : 's' ?>.
            </footer>
        </div>
    </main>
</body>

</html>
