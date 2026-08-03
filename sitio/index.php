<?php

session_start();

require_once __DIR__ . '/clases/Usuario.php';
require_once __DIR__ . '/clases/Carrito.php';
require_once __DIR__ . '/clases/Compra.php';

$seccionesPermitidas = [
    'home',
    'listado',
    'detalle',
    'contacto',
    'registro',
    'iniciar-sesion',
    'perfil',
    'carrito',
];

$seccionActual = $_GET['seccion'] ?? 'home';

$errorAuth = '';
$datosFormulario = [
    'nombre' => '',
    'apellido' => '',
    'email' => '',
];
$usuarioPerfil = null;
$carrito = new Carrito();

if ($seccionActual === 'salir') {
    Usuario::cerrarSesion();
    header('Location: index.php?seccion=home');
    exit;
}

if (
    ($seccionActual === 'registro' || $seccionActual === 'iniciar-sesion')
    && Usuario::estaLogueado()
) {
    header('Location: index.php?seccion=perfil');
    exit;
}

if (
    ($seccionActual === 'perfil' || $seccionActual === 'carrito')
    && !Usuario::estaLogueado()
) {
    header('Location: index.php?seccion=iniciar-sesion');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accionCarrito = $_POST['accion'] ?? '';

    if (
        $accionCarrito === 'agregar-carrito'
        || $accionCarrito === 'quitar-carrito'
        || $accionCarrito === 'completar-compra'
    ) {
        if (!Usuario::estaLogueado()) {
            header('Location: index.php?seccion=iniciar-sesion');
            exit;
        }
    }

    if ($accionCarrito === 'agregar-carrito') {
        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 1);
        if ($cantidad < 1) {
            $cantidad = 1;
        }

        $carrito->agregar($productoId, $cantidad);

        header('Location: index.php?seccion=detalle&id=' . $productoId);
        exit;
    }

    if ($accionCarrito === 'quitar-carrito') {
        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $carrito->quitar($productoId);
        header('Location: index.php?seccion=carrito');
        exit;
    }

    if ($accionCarrito === 'completar-compra') {
        $usuarioId = Usuario::idEnSesion();

        try {
            if ($usuarioId === null) {
                throw new RuntimeException('No se pudo identificar al usuario.');
            }

            (new Compra)->crearDesdeCarrito($usuarioId, $carrito);
        } catch (Throwable $e) {
        }

        header('Location: index.php?seccion=carrito');
        exit;
    }
}

if ($seccionActual === 'registro' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosFormulario['nombre'] = trim($_POST['nombre'] ?? '');
    $datosFormulario['apellido'] = trim($_POST['apellido'] ?? '');
    $datosFormulario['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        $datosFormulario['nombre'] === ''
        || $datosFormulario['apellido'] === ''
        || $datosFormulario['email'] === ''
        || $password === ''
    ) {
        $errorAuth = 'Completá todos los campos.';
    } elseif ((new Usuario)->porEmail($datosFormulario['email']) !== null) {
        $errorAuth = 'Ya existe una cuenta con ese email.';
    } else {
        (new Usuario)->registrar(
            $datosFormulario['email'],
            $password,
            $datosFormulario['nombre'],
            $datosFormulario['apellido']
        );

        header('Location: index.php?seccion=iniciar-sesion');
        exit;
    }
}

if ($seccionActual === 'iniciar-sesion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosFormulario['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $usuario = (new Usuario)->verificarCredenciales($datosFormulario['email'], $password);

    if ($usuario !== null) {
        Usuario::iniciarSesion($usuario);
        header('Location: index.php?seccion=perfil');
        exit;
    }

    $errorAuth = 'Email o contraseña incorrectos.';
}

if ($seccionActual === 'perfil') {
    $usuarioId = Usuario::idEnSesion();
    $usuarioPerfil = $usuarioId !== null ? (new Usuario)->porId($usuarioId) : null;

    if ($usuarioPerfil === null) {
        Usuario::cerrarSesion();
        header('Location: index.php?seccion=iniciar-sesion');
        exit;
    }
}

if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

include_once __DIR__ . '/includes/header.php';
require __DIR__ . '/vistas/' . $seccionActual . '.php';
include_once __DIR__ . '/includes/footer.php';
