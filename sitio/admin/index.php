<?php

session_start();

require_once __DIR__ . '/../clases/Usuario.php';

$seccionesPermitidas = [
    'ingresar',
    'productos',
    'producto-alta',
    'producto-editar',
    'producto-borrar',
];

$seccionActual = $_GET['seccion'] ?? 'ingresar';

if ($seccionActual === 'salir') {
    Usuario::cerrarSesion();
    header('Location: ?seccion=ingresar');
    exit;
}

if ($seccionActual === 'ingresar' && Usuario::estaLogueado()) {
    header('Location: ?seccion=productos');
    exit;
}

if ($seccionActual !== 'ingresar' && !Usuario::estaLogueado()) {
    header('Location: ?seccion=ingresar');
    exit;
}

if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

require __DIR__ . '/vistas/' . $seccionActual . '.php';
