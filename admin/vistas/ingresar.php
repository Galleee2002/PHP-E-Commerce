<?php

/** @var string $seccionActual */

$errorLogin = '';
$emailIngresado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailIngresado = trim($_POST['email'] ?? '');
    $passwordIngresado = $_POST['password'] ?? '';

    $usuario = (new Usuario)->verificarCredenciales($emailIngresado, $passwordIngresado);

    if ($usuario !== null) {
        Usuario::iniciarSesion($usuario);
        header('Location: index.php?seccion=productos');
        exit;
    }

    $errorLogin = 'Email o contraseña incorrectos.';
}

?>
<!-- TODO (frontend): reemplazar markup por diseño del panel admin -->
<section>
    <h1>Ingresar al panel</h1>

    <?php if ($errorLogin !== ''): ?>
        <p role="alert"><?= htmlspecialchars($errorLogin, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form action="index.php?seccion=ingresar" method="post">
        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($emailIngresado, ENT_QUOTES, 'UTF-8') ?>" required>
        </p>
        <p>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </p>
        <p>
            <button type="submit">Ingresar</button>
        </p>
    </form>
</section>
