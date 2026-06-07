<?php

/**
 * B3 — Listado admin (backend).
 * $productos: Producto[] cargado en admin/index.php vía Producto::todas().
 * Disponible para el frontend: getId(), getNombre(), getPrecio(), getCategoria(), etc.
 */

$usuarioId = Usuario::idEnSesion();
$usuarioEmail = $_SESSION[Usuario::SESSION_KEY_EMAIL] ?? '';

?>
<!-- TODO (frontend): diseño del listado ABM -->
<section>
    <h1>Panel — Productos</h1>
    <p>Sesión activa: <?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?> (id <?= (int) $usuarioId ?>)</p>
    <p><a href="index.php?seccion=producto-alta">Alta</a> · <a href="index.php?seccion=salir">Cerrar sesión</a></p>
</section>
