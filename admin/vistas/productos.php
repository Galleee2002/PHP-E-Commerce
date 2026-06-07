<?php

require_once __DIR__ . '/../../clases/Producto.php';

$producto = new Producto;
$productos = $producto->todas();

$usuarioId = Usuario::idEnSesion();
$usuarioEmail = $_SESSION[Usuario::SESSION_KEY_EMAIL] ?? '';

?>
<!-- TODO (frontend): diseño del listado ABM -->
<section>
    <h1>Panel — Productos</h1>
    <p>Sesión activa: <?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?> (id <?= (int) $usuarioId ?>)</p>
    <p><a href="index.php?seccion=producto-alta">Alta</a> · <a href="index.php?seccion=salir">Cerrar sesión</a></p>
</section>
