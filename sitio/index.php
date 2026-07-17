<?php

session_start();

require_once __DIR__ . '/clases/Usuario.php';

$seccionesPermitidas = [
    'home',
    'listado',
    'detalle',
    'contacto',
    'registro',
    'iniciar-sesion',
    'perfil',
];

$seccionActual = $_GET['seccion'] ?? 'home';

$errorAuth = '';
$exitoAuth = '';
$datosFormulario = [
    'nombre' => '',
    'apellido' => '',
    'email' => '',
];
$usuarioPerfil = null;

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

if ($seccionActual === 'perfil' && !Usuario::estaLogueado()) {
    header('Location: index.php?seccion=iniciar-sesion');
    exit;
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

if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

include_once __DIR__ . '/includes/header.php';
require __DIR__ . '/vistas/' . $seccionActual . '.php';
include_once __DIR__ . '/includes/footer.php';
