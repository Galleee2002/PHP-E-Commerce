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
        header('Location: ?seccion=productos');
        exit;
    }

    $errorLogin = 'Email o contraseña incorrectos.';
}

$adminBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/admin')), '/') . '/';
$sitioBase = rtrim(str_replace('\\', '/', dirname(rtrim($adminBase, '/'))), '/') . '/';

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
    <link rel="stylesheet" href="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>css/ingresar.css?v=20260606-9">
</head>

<body class="admin-login">
    <div class="admin-login__card">
        <aside
            class="admin-login__brand"
            aria-label="Galmir"
            style="--admin-login-brand-image: url('<?= htmlspecialchars($sitioBase, ENT_QUOTES, 'UTF-8') ?>imgs/login-img.webp');"
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
                <h2 class="admin-login__form-title">Inicia sesión en tu cuenta</h2>
                <p class="admin-login__form-subtitle">Accede para gestionar tus pedidos, productos y más.</p>
            </header>

            <?php if ($errorLogin !== ''): ?>
                <p class="admin-login__alert" role="alert"><?= htmlspecialchars($errorLogin, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>?seccion=ingresar" method="post">
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

                <div class="admin-login__options">
                    <label class="admin-login__remember">
                        <input type="checkbox" name="recordarme" value="1">
                        Recordarme
                    </label>
                    <a class="admin-login__forgot" href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button class="admin-login__submit" type="submit">Iniciar sesión</button>
            </form>

            <div class="admin-login__divider" aria-hidden="true">o inicia sesión con</div>

            <div class="admin-login__social">
                <button class="admin-login__social-btn" type="button" disabled aria-disabled="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuar con Google
                </button>
                <button class="admin-login__social-btn" type="button" disabled aria-disabled="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Continuar con Facebook
                </button>
            </div>

            <p class="admin-login__footer">
                ¿No tienes una cuenta? <a href="<?= htmlspecialchars($sitioBase, ENT_QUOTES, 'UTF-8') ?>index.php?seccion=home">Regístrate aquí</a>
            </p>
        </section>
    </div>

    <script>
        (function () {
            var toggle = document.querySelector('[data-toggle-password]');
            var input = document.getElementById('password');
            if (!toggle || !input) return;

            toggle.addEventListener('click', function () {
                var isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                toggle.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
            });
        })();
    </script>
</body>

</html>
