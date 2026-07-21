<?php

session_start();

require_once __DIR__ . '/clases/Usuario.php';
require_once __DIR__ . '/clases/Carrito.php';

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
$exitoAuth = '';
$mensajeCarrito = '';
$errorCarrito = '';
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

    if ($accionCarrito === 'agregar-carrito') {
        if (!Usuario::estaLogueado()) {
            header('Location: index.php?seccion=iniciar-sesion');
            exit;
        }

        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 1);
        if ($cantidad < 1) {
            $cantidad = 1;
        }

        if ($carrito->agregar($productoId, $cantidad)) {
            $_SESSION[Carrito::FLASH_OK] = $cantidad === 1
                ? 'Producto añadido al carrito.'
                : $cantidad . ' unidades añadidas al carrito.';
        } else {
            $_SESSION[Carrito::FLASH_ERROR] = 'No se pudo añadir el producto al carrito.';
        }

        header('Location: index.php?seccion=detalle&id=' . $productoId);
        exit;
    }

    if ($accionCarrito === 'quitar-carrito') {
        if (!Usuario::estaLogueado()) {
            header('Location: index.php?seccion=iniciar-sesion');
            exit;
        }

        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $carrito->quitar($productoId);
        $_SESSION[Carrito::FLASH_OK] = 'Producto quitado del carrito.';
        header('Location: index.php?seccion=carrito');
        exit;
    }
}

if ($seccionActual === 'registro' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosFormulario['nombre'] = trim($_POST['nombre'] ?? '');
    $datosFormulario['apellido'] = trim($_POST['apellido'] ?? '');
    $datosFormulario['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirmacion = $_POST['password_confirmacion'] ?? '';

    if (
        $datosFormulario['nombre'] === ''
        || $datosFormulario['apellido'] === ''
        || $datosFormulario['email'] === ''
        || $password === ''
        || $passwordConfirmacion === ''
    ) {
        $errorAuth = 'Completá todos los campos.';
    } elseif (!filter_var($datosFormulario['email'], FILTER_VALIDATE_EMAIL)) {
        $errorAuth = 'Ingresá un email válido.';
    } elseif (strlen($password) < 8) {
        $errorAuth = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $passwordConfirmacion) {
        $errorAuth = 'Las contraseñas no coinciden.';
    } elseif ((new Usuario)->porEmail($datosFormulario['email']) !== null) {
        $errorAuth = 'Ya existe una cuenta con ese email.';
    } else {
        (new Usuario)->registrar(
            $datosFormulario['email'],
            $password,
            $datosFormulario['nombre'],
            $datosFormulario['apellido']
        );

        header('Location: index.php?seccion=iniciar-sesion&registro=ok');
        exit;
    }
}

if ($seccionActual === 'iniciar-sesion') {
    if (isset($_GET['registro']) && $_GET['registro'] === 'ok') {
        $exitoAuth = 'Tu cuenta se creó correctamente. Ya podés iniciar sesión.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datosFormulario['email'] = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $usuario = (new Usuario)->verificarCredenciales($datosFormulario['email'], $password);

        if ($usuario !== null) {
            Usuario::iniciarSesion($usuario);
            header('Location: index.php?seccion=perfil');
            exit;
        }

        $errorAuth = 'Email o contraseña incorrectos.';
        $exitoAuth = '';
    }
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

if ($seccionActual === 'detalle' || $seccionActual === 'carrito') {
    if (isset($_SESSION[Carrito::FLASH_OK])) {
        $mensajeCarrito = (string) $_SESSION[Carrito::FLASH_OK];
        unset($_SESSION[Carrito::FLASH_OK]);
    }

    if (isset($_SESSION[Carrito::FLASH_ERROR])) {
        $errorCarrito = (string) $_SESSION[Carrito::FLASH_ERROR];
        unset($_SESSION[Carrito::FLASH_ERROR]);
    }
}

if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

include_once __DIR__ . '/includes/header.php';
require __DIR__ . '/vistas/' . $seccionActual . '.php';
include_once __DIR__ . '/includes/footer.php';
