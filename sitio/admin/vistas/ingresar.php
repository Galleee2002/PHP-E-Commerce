<?php

$errorLogin = '';
$emailIngresado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailIngresado = trim($_POST['email'] ?? '');
    $passwordIngresado = $_POST['password'] ?? '';

    $usuario = (new Usuario)->verificarCredenciales($emailIngresado, $passwordIngresado);

    if ($usuario !== null && $usuario->getRol() === Usuario::ROL_ADMIN) {
        Usuario::iniciarSesion($usuario);
        header('Location: ?seccion=productos');
        exit;
    }

    $errorLogin = 'Email o contraseña incorrectos.';
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar | Galmir Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/ingresar.css?v=20260620-2">
</head>

<body class="admin-login">
    <a class="admin-login__back" href="../index.php?seccion=home">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M19 12H5"/>
            <path d="m12 19-7-7 7-7"/>
        </svg>
        Volver al sitio
    </a>

    <div class="admin-login__card">
        <aside
            class="admin-login__brand"
            aria-label="Galmir"
            style="--admin-login-brand-image: url('../imgs/login-img.webp');"
        >
            <div class="admin-login__brand-top">
                <div class="admin-login__logo">
                    <div>
                        <p class="admin-login__logo-name">GALMIR</p>
                        <p class="admin-login__logo-tagline">Juegos de mesa</p>
                    </div>
                    <svg class="admin-login__logo-dice" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <rect x="4" y="4" width="18" height="18" rx="3" stroke="currentColor" stroke-width="2" fill="none"/>
                        <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
                        <circle cx="16" cy="16" r="1.5" fill="currentColor"/>
                        <rect x="26" y="26" width="18" height="18" rx="3" stroke="currentColor" stroke-width="2" fill="none"/>
                        <circle cx="32" cy="32" r="1.5" fill="currentColor"/>
                        <circle cx="38" cy="38" r="1.5" fill="currentColor"/>
                        <circle cx="35" cy="35" r="1.5" fill="currentColor"/>
                    </svg>
                </div>

                <div class="admin-login__brand-copy">
                    <p class="admin-login__description">Descubrí, comprá y disfrutá los mejores juegos de mesa para compartir momentos inolvidables.</p>
                </div>
            </div>
        </aside>

        <section class="admin-login__form-panel">
            <header class="admin-login__form-header">
                <h2 class="admin-login__form-title">Iniciá sesión en tu cuenta</h2>
                <p class="admin-login__form-subtitle">Accedé para gestionar tus pedidos, productos y más.</p>
            </header>

            <?php if ($errorLogin !== ''): ?>
                <p class="admin-login__alert" role="alert"><?= htmlspecialchars($errorLogin, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form action="index.php?seccion=ingresar" method="post">
                <div class="admin-login__field">
                    <label class="admin-login__label" for="email">Email</label>
                    <div class="admin-login__input-wrap">
                        <svg class="admin-login__input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m2 7 10 7 10-7"/>
                        </svg>
                        <input
                            class="admin-login__input"
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($emailIngresado, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="admin@galmir.local"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                <div class="admin-login__field">
                    <label class="admin-login__label" for="password">Contraseña</label>
                    <div class="admin-login__input-wrap">
                        <svg class="admin-login__input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            class="admin-login__input admin-login__input--password"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        <button class="admin-login__toggle-password" type="button" aria-label="Mostrar contraseña" data-toggle-password>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button class="admin-login__submit" type="submit">Iniciar sesión</button>
            </form>
        </section>
    </div>

    <script>
        (function () {
            const toggle = document.querySelector('[data-toggle-password]');
            const input = document.getElementById('password');
            if (!toggle || !input) return;

            toggle.addEventListener('click', function () {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                toggle.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
            });
        })();
    </script>
</body>

</html>
