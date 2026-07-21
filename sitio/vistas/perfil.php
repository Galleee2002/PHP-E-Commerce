<?php
/** @var Usuario|null $usuarioPerfil */
$usuarioPerfil = $usuarioPerfil ?? null;

if ($usuarioPerfil === null) {
    return;
}

$nombreCompleto = trim($usuarioPerfil->getNombre() . ' ' . $usuarioPerfil->getApellido());
?>
<section class="cuenta-page" aria-labelledby="titulo-perfil">
    <div class="cuenta-page__panel">
        <header class="cuenta-page__header">
            <h1 class="cuenta-page__title" id="titulo-perfil">Tu perfil</h1>
            <p class="cuenta-page__lead">Información de tu cuenta.</p>
        </header>

        <dl class="cuenta-perfil">
            <div class="cuenta-perfil__item">
                <dt>Nombre</dt>
                <dd><?= htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') ?></dd>
            </div>
            <div class="cuenta-perfil__item">
                <dt>Email</dt>
                <dd><?= htmlspecialchars($usuarioPerfil->getEmail(), ENT_QUOTES, 'UTF-8') ?></dd>
            </div>
        </dl>

        <div class="cuenta-form__actions">
            <a class="cuenta-btn cuenta-btn--secondary" href="index.php?seccion=salir">Cerrar sesión</a>
        </div>
    </div>
</section>
