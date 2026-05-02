<?php
require_once __DIR__ . '/clases/Producto.php';

$productos = (new Producto())->todas();

$seccionesPermitidas = [
	'home' => __DIR__ . '/vistas/home.php',
	'listado' => __DIR__ . '/vistas/listado.php',
	'detalle' => __DIR__ . '/vistas/detalle.php',
	'contacto' => __DIR__ . '/vistas/contacto.php',
];

$seccionActual = $_GET['seccion'] ?? 'home';
$rutaSeccion = $seccionesPermitidas[$seccionActual] ?? $seccionesPermitidas['home'];

include_once __DIR__ . '/includes/header.php';
include_once $rutaSeccion;
include_once __DIR__ . '/includes/footer.php';