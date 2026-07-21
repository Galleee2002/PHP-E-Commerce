<?php
$errorAuth = $errorAuth ?? '';
$datosFormulario = $datosFormulario ?? [
    'nombre' => '',
    'apellido' => '',
    'email' => '',
];
?>
<section class="cuenta-page" aria-labelledby="titulo-registro">
    <div class="cuenta-page__panel">
        <header class="cuenta-page__header">
            <h1 class="cuenta-page__title" id="titulo-registro">Creá tu cuenta</h1>
            <p class="cuenta-page__lead">Registrate para poder realizar una compra con nosotros.</p>
        </header>

        <?php if ($errorAuth !== ''): ?>
            <p class="cuenta-alert cuenta-alert--error" role="alert"><?= htmlspecialchars($errorAuth, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form class="cuenta-form" action="index.php?seccion=registro" method="post" novalidate>
            <div class="cuenta-form__grid">
                <div class="cuenta-field">
                    <label class="cuenta-field__label" for="nombre">Nombre</label>
                    <input
                        class="cuenta-field__input"
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?= htmlspecialchars($datosFormulario['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="given-name"
                    >
                </div>

                <div class="cuenta-field">
                    <label class="cuenta-field__label" for="apellido">Apellido</label>
                    <input
                        class="cuenta-field__input"
                        type="text"
                        id="apellido"
                        name="apellido"
                        value="<?= htmlspecialchars($datosFormulario['apellido'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="family-name"
                    >
                </div>

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

                <div class="cuenta-field">
                    <label class="cuenta-field__label" for="password">Contraseña</label>
                    <input
                        class="cuenta-field__input"
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                </div>

                <div class="cuenta-field">
                    <label class="cuenta-field__label" for="password_confirmacion">Confirmar contraseña</label>
                    <input
                        class="cuenta-field__input"
                        type="password"
                        id="password_confirmacion"
                        name="password_confirmacion"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                </div>
            </div>

            <div class="cuenta-form__actions">
                <button class="cuenta-btn" type="submit">Registrarme</button>
                <p class="cuenta-form__hint">
                    ¿Ya tenés cuenta?
                    <a href="index.php?seccion=iniciar-sesion">Iniciá sesión</a>
                </p>
            </div>
        </form>
    </div>
</section>
