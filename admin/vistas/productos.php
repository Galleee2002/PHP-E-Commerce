<?php

/** Stub Fase 4 — Fase 5 implementará el ABM. Variables de sesión disponibles para las vistas. */

$usuarioId = Usuario::idEnSesion();
$usuarioEmail = $_SESSION[Usuario::SESSION_KEY_EMAIL] ?? '';

?>
<!-- TODO (frontend): diseño del listado ABM -->
<section>
    <h1>Panel — Productos</h1>
    <p>Sesión activa: <?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?> (id <?= (int) $usuarioId ?>)</p>
    <p><a href="index.php?seccion=producto-alta">Alta</a> · <a href="index.php?seccion=salir">Cerrar sesión</a></p>
</section>
