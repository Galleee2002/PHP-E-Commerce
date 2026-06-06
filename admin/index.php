<?php

session_start();

require_once __DIR__ . '/../clases/Usuario.php';

$seccionesPermitidas = [
    'ingresar' => __DIR__ . '/vistas/ingresar.php',
    'productos' => __DIR__ . '/vistas/productos.php',
    'producto-alta' => __DIR__ . '/vistas/producto-alta.php',
    'producto-editar' => __DIR__ . '/vistas/producto-editar.php',
    'producto-borrar' => __DIR__ . '/vistas/producto-borrar.php',
];

$seccionActual = $_GET['seccion'] ?? 'ingresar';

if ($seccionActual === 'salir') {
    Usuario::cerrarSesion();
    header('Location: index.php?seccion=ingresar');
    exit;
}

$requiereSesion = $seccionActual !== 'ingresar';

if ($seccionActual === 'ingresar' && Usuario::estaLogueado()) {
    header('Location: index.php?seccion=productos');
    exit;
}

if ($requiereSesion && !Usuario::estaLogueado()) {
    header('Location: index.php?seccion=ingresar');
    exit;
}

$rutaSeccion = $seccionesPermitidas[$seccionActual] ?? __DIR__ . '/vistas/404.php';

require $rutaSeccion;
