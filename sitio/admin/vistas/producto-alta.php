<?php

require_once __DIR__ . '/../../clases/Producto.php';

$producto = new Producto;
$categorias = $producto->todasCategorias();

$errorAlta = '';
$valoresAlta = [
    'nombre' => '',
    'precio' => '',
    'descripcion_corta' => '',
    'descripcion' => '',
    'imagen' => '',
    'categoria_id' => 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valoresAlta = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'precio' => trim($_POST['precio'] ?? ''),
        'descripcion_corta' => trim($_POST['descripcion_corta'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'imagen' => trim($_POST['imagen'] ?? ''),
        'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
    ];

    $precio = (float) str_replace(',', '.', $valoresAlta['precio']);

    if (
        $valoresAlta['nombre'] === ''
        || $valoresAlta['precio'] === ''
        || $precio <= 0
        || $valoresAlta['descripcion_corta'] === ''
        || $valoresAlta['descripcion'] === ''
        || $valoresAlta['imagen'] === ''
        || $valoresAlta['categoria_id'] <= 0
    ) {
        $errorAlta = 'Completá todos los campos obligatorios con valores válidos.';
    } else {
        try {
            $producto->crear(
                $valoresAlta['nombre'],
                $precio,
                $valoresAlta['descripcion_corta'],
                $valoresAlta['descripcion'],
                $valoresAlta['imagen'],
                Usuario::idEnSesion(),
                $valoresAlta['categoria_id']
            );

            header('Location: index.php?seccion=productos');
            exit;
        } catch (InvalidArgumentException $exception) {
            $errorAlta = $exception->getMessage();
        }
    }
}

?>
<?php
$usuarioEmail = $_SESSION[Usuario::SESSION_KEY_EMAIL] ?? '';
$adminBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/admin')), '/') . '/';
$sitioBase = rtrim(str_replace('\\', '/', dirname(rtrim($adminBase, '/'))), '/') . '/';
$imagenPreview = $valoresAlta['imagen'] !== ''
    ? $sitioBase . $valoresAlta['imagen']
    : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de producto | Galmir Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>css/producto-editar.css?v=20260607-3">
</head>

<body class="admin-editar">
    <header class="admin-editar__topbar">
        <div class="admin-editar__brand">
            <div>
                <p class="admin-editar__logo-name">GALMIR</p>
                <p class="admin-editar__logo-tagline">Juegos de mesa</p>
            </div>
        </div>
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
                    <a class="admin-editar__profile-logout" href="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>index.php?seccion=salir">Cerrar sesión</a>
                </div>
            </details>
        </div>
    </header>

    <main class="admin-editar__main">
        <div class="admin-editar__shell">
            <header class="admin-editar__page-head">
                <div class="admin-editar__page-head-text">
                    <h1 class="admin-editar__title">Alta de producto</h1>
                    <p class="admin-editar__subtitle">Completa la información para agregar un nuevo juego de mesa a tu tienda.</p>
                    <nav aria-label="Ruta de navegación">
                        <ol class="admin-editar__breadcrumb">
                            <li><a href="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>index.php?seccion=productos">Productos</a></li>
                            <li class="admin-editar__breadcrumb-sep" aria-hidden="true">›</li>
                            <li aria-current="page">Alta de producto</li>
                        </ol>
                    </nav>
                </div>
                <a class="admin-editar__back" href="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>index.php?seccion=productos">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Volver al listado
                </a>
            </header>

            <?php if ($errorAlta !== ''): ?>
                <p class="admin-editar__alert" role="alert"><?= htmlspecialchars($errorAlta, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form
                class="admin-editar__form"
                method="post"
                action="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>index.php?seccion=producto-alta"
            >
                <div class="admin-editar__grid">
                    <section class="admin-editar__card" aria-labelledby="alta-info-basica">
                        <h2 class="admin-editar__card-title" id="alta-info-basica">Información básica</h2>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="nombre">
                                Nombre del producto <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                class="admin-editar__input"
                                type="text"
                                name="nombre"
                                id="nombre"
                                placeholder="Ej: Catan"
                                value="<?= htmlspecialchars($valoresAlta['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="admin-editar__row">
                            <div class="admin-editar__field">
                                <label class="admin-editar__label" for="categoria_id">
                                    Categoría <span class="admin-editar__required" aria-hidden="true">*</span>
                                </label>
                                <select class="admin-editar__select" name="categoria_id" id="categoria_id" required>
                                    <option value="">Seleccionar…</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option
                                            value="<?= (int) $categoria['categoria_id'] ?>"
                                            <?= (int) $valoresAlta['categoria_id'] === (int) $categoria['categoria_id'] ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="admin-editar__field">
                                <label class="admin-editar__label" for="precio">
                                    Precio <span class="admin-editar__required" aria-hidden="true">*</span>
                                </label>
                                <input
                                    class="admin-editar__input"
                                    type="text"
                                    name="precio"
                                    id="precio"
                                    inputmode="decimal"
                                    placeholder="0"
                                    value="<?= htmlspecialchars($valoresAlta['precio'], ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >
                            </div>
                        </div>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="descripcion_corta">
                                Descripción corta <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                class="admin-editar__input"
                                type="text"
                                name="descripcion_corta"
                                id="descripcion_corta"
                                placeholder="Resumen breve para el listado"
                                value="<?= htmlspecialchars($valoresAlta['descripcion_corta'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="descripcion">
                                Descripción completa <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <textarea
                                class="admin-editar__textarea"
                                name="descripcion"
                                id="descripcion"
                                placeholder="Descripción detallada del juego de mesa"
                                required
                            ><?= htmlspecialchars($valoresAlta['descripcion'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </section>

                    <section class="admin-editar__card admin-editar__card--imagen" aria-labelledby="alta-imagen">
                        <h2 class="admin-editar__card-title" id="alta-imagen">Imagen del producto</h2>

                        <div class="admin-editar__field">
                            <label class="admin-editar__label" for="imagen">
                                Ruta de la imagen <span class="admin-editar__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                class="admin-editar__input"
                                type="text"
                                name="imagen"
                                id="imagen"
                                placeholder="imgs/teg.webp"
                                value="<?= htmlspecialchars($valoresAlta['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                            <p class="admin-editar__hint">Ruta relativa desde la raíz del sitio, por ejemplo <code>imgs/teg.webp</code>.</p>
                        </div>

                        <div class="admin-editar__image-preview">
                            <?php if ($imagenPreview !== ''): ?>
                                <img
                                    src="<?= htmlspecialchars($imagenPreview, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Vista previa de la imagen del producto"
                                >
                            <?php else: ?>
                                <div class="admin-editar__image-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    <span>Ingresá la ruta de la imagen para ver la vista previa</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <footer class="admin-editar__actions">
                    <a class="admin-editar__btn admin-editar__btn--ghost" href="<?= htmlspecialchars($adminBase, ENT_QUOTES, 'UTF-8') ?>index.php?seccion=productos">Cancelar</a>
                    <button class="admin-editar__btn admin-editar__btn--primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Guardar producto
                    </button>
                </footer>
            </form>
        </div>
    </main>

</body>

</html>
