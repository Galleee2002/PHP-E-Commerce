<?php
$errorAuth = $errorAuth ?? '';
$exitoAuth = $exitoAuth ?? '';
$datosFormulario = $datosFormulario ?? [
    'email' => '',
];
?>
<section class="cuenta-page" aria-labelledby="titulo-login">
    <div class="cuenta-page__panel">
        <header class="cuenta-page__header">
            <h1 class="cuenta-page__title" id="titulo-login">Iniciá sesión</h1>
            <p class="cuenta-page__lead">Accedé a tu cuenta para comprar y ver tu perfil.</p>
        </header>

        <?php if ($exitoAuth !== ''): ?>
            <p class="cuenta-alert cuenta-alert--success" role="status"><?= htmlspecialchars($exitoAuth, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if ($errorAuth !== ''): ?>
            <p class="cuenta-alert cuenta-alert--error" role="alert"><?= htmlspecialchars($errorAuth, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form class="cuenta-form" action="index.php?seccion=iniciar-sesion" method="post" novalidate>
            <div class="cuenta-form__grid">
                <div class="cuenta-field cuenta-field--full">
                    <label class="cuenta-field__label" for="email">Email</label>
                    <input
                        class="cuenta-field__input"
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($datosFormulario['email'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="cuenta-field cuenta-field--full">
                    <label class="cuenta-field__label" for="password">Contraseña</label>
                    <input
                        class="cuenta-field__input"
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <div class="cuenta-form__actions">
                <button class="cuenta-btn" type="submit">Iniciar sesión</button>
                <p class="cuenta-form__hint">
                    ¿No tenés cuenta?
                    <a href="index.php?seccion=registro">Registrate</a>
                </p>
            </div>
        </form>
    </div>
</section>
