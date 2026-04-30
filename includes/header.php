<?php
$seccionActual ??= 'home';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Mesa Ludica | Tienda de Juegos de Mesa</title>
    <meta name="description" content="Tienda online de juegos de mesa modernos para familias, amigos y jugadores expertos.">
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="page">
    <header class="site-header">
        <nav class="site-nav" aria-label="Navegacion principal">
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

            <form class="search-form" action="index.php" method="get" role="search" aria-label="Buscar productos">
                <input type="hidden" name="seccion" value="listado">
                <label class="sr-only" for="busqueda-header">Buscar</label>
                <input class="search-form__input" id="busqueda-header" name="q" type="search" placeholder="Buscar juego...">
                <button class="search-form__submit" type="submit">Buscar</button>
            </form>
        </nav>
    </header>
    <main class="site-main">
