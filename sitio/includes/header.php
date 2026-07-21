<?php
$seccionActual ??= 'home';
$estaLogueado = class_exists('Usuario') && Usuario::estaLogueado();
$esAdmin = class_exists('Usuario') && Usuario::esAdmin();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galmir | Tienda de Juegos de Mesa</title>
    <meta name="description" content="Tienda online de juegos de mesa modernos para familias, amigos y jugadores expertos.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css?v=20260721-1">
</head>
    
<body class="page">
    <header class="site-header">
        <nav class="site-nav" aria-label="Navegación principal">
            <a class="brand" href="index.php?seccion=home">
                <img class="brand__logo" src="imgs/logo-2.png" alt="Logo de Galmir">
                <span class="brand__name">Galmir</span>
            </a>

            <ul class="nav-list">
                <li>
                    <a class="nav-link<?= $seccionActual === 'home' ? ' nav-link--current' : '' ?>" href="index.php?seccion=home" <?= $seccionActual === 'home' ? ' aria-current="page"' : '' ?>>Inicio</a>
                </li>
                <li>
                    <a class="nav-link<?= $seccionActual === 'listado' ? ' nav-link--current' : '' ?>" href="index.php?seccion=listado" <?= $seccionActual === 'listado' ? ' aria-current="page"' : '' ?>>Listado</a>
                </li>
                <li>
                    <a class="nav-link<?= $seccionActual === 'contacto' ? ' nav-link--current' : '' ?>" href="index.php?seccion=contacto" <?= $seccionActual === 'contacto' ? ' aria-current="page"' : '' ?>>Contacto</a>
                </li>
            </ul>

            <div class="site-nav__actions">
                <?php if ($estaLogueado): ?>
                    <details class="account-menu">
                        <summary class="icon-link account-menu__toggle" aria-label="Menú de cuenta">
                            <img class="icon-link__img" src="imgs/usuario.png" alt="">
                        </summary>
                        <div class="account-menu__panel">
                            <a class="account-menu__link<?= $seccionActual === 'perfil' ? ' account-menu__link--current' : '' ?>" href="index.php?seccion=perfil">Mi perfil</a>
                            <a class="account-menu__link" href="index.php?seccion=salir">Cerrar sesión</a>
                        </div>
                    </details>
                <?php else: ?>
                    <a class="icon-link" href="index.php?seccion=iniciar-sesion" aria-label="Iniciar sesión">
                        <img class="icon-link__img" src="imgs/usuario.png" alt="">
                    </a>
                <?php endif; ?>
                <a class="icon-link" href="#" aria-label="Ver carrito de compras">
                    <img class="icon-link__img" src="imgs/carro.png" alt="">
                </a>
                <?php if ($esAdmin): ?>
                    <a class="admin-link" href="admin/index.php?seccion=productos" aria-label="Ir al panel de administración">
                        <svg class="admin-link__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M20 21a8 8 0 1 0-16 0"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="site-main<?= $seccionActual === 'home' ? ' site-main--home' : '' ?>">
