<?php

require_once __DIR__ . '/../../clases/Producto.php';

$productoModel = new Producto;
$categorias = $productoModel->todasCategorias();

$errorGeneral = '';
$errores = [];
$producto = null;
$valoresEdicion = [
    'producto_id' => 0,
    'nombre' => '',
    'precio' => '',
    'descripcion_corta' => '',
    'descripcion' => '',
    'imagen' => '',
    'categoria_id' => 0,
];

$idProducto = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = (int) ($_POST['producto_id'] ?? $idProducto);

    $valoresEdicion = [
        'producto_id' => $idProducto,
        'nombre' => trim($_POST['nombre'] ?? ''),
        'precio' => trim($_POST['precio'] ?? ''),
        'descripcion_corta' => trim($_POST['descripcion_corta'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'imagen' => trim($_POST['imagen'] ?? ''),
        'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
    ];

    if ($idProducto <= 0) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    $errores = Producto::validarFormulario($valoresEdicion);

    if ($errores === []) {
        $precio = (float) str_replace(',', '.', $valoresEdicion['precio']);

        try {
            $actualizado = $productoModel->actualizar(
                $idProducto,
                $valoresEdicion['nombre'],
                $precio,
                $valoresEdicion['descripcion_corta'],
                $valoresEdicion['descripcion'],
                $valoresEdicion['imagen'],
                $valoresEdicion['categoria_id']
            );

            if (!$actualizado) {
                header('Location: index.php?seccion=productos');
                exit;
            }

            header('Location: index.php?seccion=productos');
            exit;
        } catch (InvalidArgumentException $exception) {
            $errorGeneral = $exception->getMessage();
        }
    }
} else {
    if ($idProducto <= 0) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    $producto = $productoModel->porId($idProducto);

    if ($producto === null) {
        header('Location: index.php?seccion=productos');
        exit;
    }

    $categoriasProducto = $productoModel->categoriasPorProducto($idProducto);

    $valoresEdicion = [
        'producto_id' => $producto->getId(),
        'nombre' => $producto->getNombre(),
        'precio' => (string) $producto->getPrecio(),
        'descripcion_corta' => $producto->getDescripcionCorta(),
        'descripcion' => $producto->getDescripcion(),
        'imagen' => $producto->getImagen(),
        'categoria_id' => (int) ($categoriasProducto[0] ?? 0),
    ];
}

$usuarioEmail = Usuario::emailEnSesion() ?? '';
$imagenPreview = $valoresEdicion['imagen'] !== ''
    ? '../' . $valoresEdicion['imagen']
    : '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto | Galmir Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/producto-editar.css">
</head>

<body class="admin-editar">
    <header class="admin-editar__topbar">
        <div class="admin-editar__brand">
            <div>
                <p class="admin-editar__logo-name">GALMIR</p>
                <p class="admin-editar__logo-tagline">Juegos de mesa</p>
            </div>
        </div>
        <nav class="admin-editar__nav" aria-label="Secciones del panel">
            <a class="admin-editar__nav-link admin-editar__nav-link--active" href="index.php?seccion=productos" aria-current="page">Productos</a>
            <a class="admin-editar__nav-link" href="index.php?seccion=usuarios">Usuarios</a>
        </nav>
        <div class="admin-editar__session">
            <details class="admin-editar__profile">
                <summary class="admin-editar__profile-toggle">
                    <span class="admin-editar__avatar" aria-hidden="true">A</span>
                    <span class="admin-editar__profile-text">
                        <span class="admin-editar__profile-name">Administrador</span>
                        <span class="admin-editar__profile-email"><?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?></span>
                    </span>
                    <svg class="admin-editar__profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </summary>
                <div class="admin-editar__profile-menu">
                    <a class="admin-editar__profile-logout" href="index.php?seccion=salir">Cerrar sesión</a>
                </div>
            </details>
        </div>
    </header>

    <main class="admin-editar__main">
        <div class="admin-editar__shell">
            <header class="admin-editar__page-head">
                <div class="admin-editar__page-head-text">
                    <h1 class="admin-editar__title">Editar producto</h1>
                    <nav aria-label="Ruta de navegación">
                        <ol class="admin-editar__breadcrumb">
                            <li><a href="index.php?seccion=productos">Productos</a></li>
                            <li class="admin-editar__breadcrumb-sep" aria-hidden="true">›</li>
                            <li aria-current="page">Editar producto</li>
                        </ol>
                    </nav>
                </div>
                <a class="admin-editar__back" href="index.php?seccion=productos">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Volver al listado
                </a>
            </header>

            <?php if ($errorGeneral !== ''): ?>
                <p class="admin-editar__alert" role="alert"><?= htmlspecialchars($errorGeneral, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form
                class="admin-editar__form"
                method="post"
                action="index.php?seccion=producto-editar&amp;id=<?= (int) $valoresEdicion['producto_id'] ?>"
            >
                <input type="hidden" name="producto_id" value="<?= (int) $valoresEdicion['producto_id'] ?>">

                <div class="admin-editar__grid">
                    <section class="admin-editar__card" aria-labelledby="editar-info-basica">
                        <h2 class="admin-editar__card-title" id="editar-info-basica">Información básica</h2>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="nombre">
                                Nombre del producto <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                class="admin-editar__input<?= isset($errores['nombre']) ? ' admin-editar__input--error' : '' ?>"
                                type="text"
                                name="nombre"
                                id="nombre"
                                value="<?= htmlspecialchars($valoresEdicion['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                                <?= isset($errores['nombre']) ? 'aria-invalid="true" aria-describedby="error-nombre"' : '' ?>
                            >
                            <?php if (isset($errores['nombre'])): ?>
                                <p class="admin-editar__field-error" id="error-nombre"><?= htmlspecialchars($errores['nombre'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="admin-editar__row">
                            <div class="admin-editar__field">
                                <label class="admin-editar__label" for="categoria_id">
                                    Categoría <span class="admin-editar__required" aria-hidden="true">*</span>
                                </label>
                                <select
                                    class="admin-editar__select<?= isset($errores['categoria_id']) ? ' admin-editar__select--error' : '' ?>"
                                    name="categoria_id"
                                    id="categoria_id"
                                    required
                                    <?= isset($errores['categoria_id']) ? 'aria-invalid="true" aria-describedby="error-categoria_id"' : '' ?>
                                >
                                    <option value="">Seleccionar…</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option
                                            value="<?= (int) $categoria['categoria_id'] ?>"
                                            <?= (int) $valoresEdicion['categoria_id'] === (int) $categoria['categoria_id'] ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errores['categoria_id'])): ?>
                                    <p class="admin-editar__field-error" id="error-categoria_id"><?= htmlspecialchars($errores['categoria_id'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="admin-editar__field">
                                <label class="admin-editar__label" for="precio">
                                    Precio <span class="admin-editar__required" aria-hidden="true">*</span>
                                </label>
                                <input
                                    class="admin-editar__input<?= isset($errores['precio']) ? ' admin-editar__input--error' : '' ?>"
                                    type="text"
                                    name="precio"
                                    id="precio"
                                    inputmode="decimal"
                                    value="<?= htmlspecialchars($valoresEdicion['precio'], ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                    <?= isset($errores['precio']) ? 'aria-invalid="true" aria-describedby="error-precio"' : '' ?>
                                >
                                <?php if (isset($errores['precio'])): ?>
                                    <p class="admin-editar__field-error" id="error-precio"><?= htmlspecialchars($errores['precio'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="descripcion_corta">
                                Descripción corta <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                class="admin-editar__input<?= isset($errores['descripcion_corta']) ? ' admin-editar__input--error' : '' ?>"
                                type="text"
                                name="descripcion_corta"
                                id="descripcion_corta"
                                value="<?= htmlspecialchars($valoresEdicion['descripcion_corta'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                                <?= isset($errores['descripcion_corta']) ? 'aria-invalid="true" aria-describedby="error-descripcion_corta"' : '' ?>
                            >
                            <?php if (isset($errores['descripcion_corta'])): ?>
                                <p class="admin-editar__field-error" id="error-descripcion_corta"><?= htmlspecialchars($errores['descripcion_corta'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="descripcion">
                                Descripción completa <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <textarea
                                class="admin-editar__textarea<?= isset($errores['descripcion']) ? ' admin-editar__input--error' : '' ?>"
                                name="descripcion"
                                id="descripcion"
                                required
                                <?= isset($errores['descripcion']) ? 'aria-invalid="true" aria-describedby="error-descripcion"' : '' ?>
                            ><?= htmlspecialchars($valoresEdicion['descripcion'], ENT_QUOTES, 'UTF-8') ?></textarea>
                            <?php if (isset($errores['descripcion'])): ?>
                                <p class="admin-editar__field-error" id="error-descripcion"><?= htmlspecialchars($errores['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="admin-editar__card admin-editar__card--imagen" aria-labelledby="editar-imagen">
                        <h2 class="admin-editar__card-title" id="editar-imagen">Imagen del producto</h2>

                        <input type="hidden" name="imagen" value="<?= htmlspecialchars($valoresEdicion['imagen'], ENT_QUOTES, 'UTF-8') ?>">

                        <?php if (isset($errores['imagen'])): ?>
                            <p class="admin-editar__field-error" id="error-imagen" role="alert"><?= htmlspecialchars($errores['imagen'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>

                        <div class="admin-editar__image-preview">
                            <?php if ($imagenPreview !== ''): ?>
                                <img
                                    src="<?= htmlspecialchars($imagenPreview, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Vista previa de <?= htmlspecialchars($valoresEdicion['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                            <?php else: ?>
                                <div class="admin-editar__image-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <path d="M21 15l-5-5L5 21"/>
                                    </svg>
                                    <span>Sin imagen</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <footer class="admin-editar__actions">
                    <a class="admin-editar__btn admin-editar__btn--ghost" href="index.php?seccion=productos">Cancelar</a>
                    <button class="admin-editar__btn admin-editar__btn--primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Guardar cambios
                    </button>
                </footer>
            </form>
        </div>
    </main>

</body>

</html>
