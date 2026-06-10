<?php

$seccionesPermitidas = [
    'home',
    'listado',
    'detalle',
    'contacto',
];

$seccionActual = $_GET['seccion'] ?? 'home';

if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

include_once __DIR__ . '/includes/header.php';
require __DIR__ . '/vistas/' . $seccionActual . '.php';
include_once __DIR__ . '/includes/footer.php';
