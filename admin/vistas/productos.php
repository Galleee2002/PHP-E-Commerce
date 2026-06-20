<?php

require_once __DIR__ . '/../../clases/Producto.php';

$producto = new Producto;
$productos = $producto->todas();

$usuarioEmail = $_SESSION[Usuario::SESSION_KEY_EMAIL] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Galmir Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/productos.css?v=20260607-6">
</head>

<body class="admin-productos">
    <header class="admin-productos__topbar">
        <div class="admin-productos__brand">
            <div>
                <p class="admin-productos__logo-name">GALMIR</p>
                <p class="admin-productos__logo-tagline">Juegos de mesa</p>
            </div>
        </div>
        <div class="admin-productos__session">
            <details class="admin-productos__profile">
                <summary class="admin-productos__profile-toggle">
                    <span class="admin-productos__avatar" aria-hidden="true">A</span>
                    <span class="admin-productos__profile-text">
                        <span class="admin-productos__profile-name">Administrador</span>
                        <span class="admin-productos__profile-email"><?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?></span>
                    </span>
                    <svg class="admin-productos__profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </summary>
                <div class="admin-productos__profile-menu">
                    <a class="admin-productos__profile-logout" href="index.php?seccion=salir">Cerrar sesión</a>
                </div>
            </details>
        </div>
    </header>

    <main class="admin-productos__main">
        <div class="admin-productos__card">
            <header class="admin-productos__header">
                <div>
                    <h1 class="admin-productos__title">Panel — Productos</h1>
                    <p class="admin-productos__subtitle">Gestiona todos los juegos de mesa de tu tienda.</p>
                </div>
                <a class="admin-productos__add" href="index.php?seccion=producto-alta">
                    <span class="admin-productos__add-icon" aria-hidden="true">+</span>
                    Agregar producto
                </a>
            </header>

            <div class="admin-productos__table-wrap">
                <table class="admin-productos__table">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($productos) === 0): ?>
                            <tr>
                                <td class="admin-productos__empty" colspan="4">No hay productos cargados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td>
                                        <div class="admin-productos__product">
                                            <img
                                                class="admin-productos__thumb"
                                                src="<?= htmlspecialchars('../' . $producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>"
                                                alt=""
                                            >
                                            <div class="admin-productos__product-info">
                                                <p class="admin-productos__product-name"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></p>
                                                <p class="admin-productos__product-desc"><?= htmlspecialchars($producto->getDescripcionCorta(), ENT_QUOTES, 'UTF-8') ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="admin-productos__categoria"><?= htmlspecialchars($producto->getCategoria(), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="admin-productos__precio">$<?= number_format($producto->getPrecio(), 0, ',', '.') ?></td>
                                    <td>
                                        <div class="admin-productos__actions">
                                            <a
                                                class="admin-productos__action admin-productos__action--edit"
                                                href="index.php?seccion=producto-editar&amp;id=<?= (int) $producto->getId() ?>"
                                                aria-label="Editar <?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <button
                                                type="button"
                                                class="admin-productos__action admin-productos__action--delete"
                                                data-delete-id="<?= (int) $producto->getId() ?>"
                                                data-delete-name="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                                                aria-label="Borrar <?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <footer class="admin-productos__footer">
                Mostrando <?= count($productos) ?> producto<?= count($productos) === 1 ? '' : 's' ?>.
            </footer>
        </div>
    </main>

    <dialog class="admin-productos__dialog" id="delete-dialog" aria-labelledby="delete-dialog-title">
        <form class="admin-productos__dialog-form" id="delete-form" method="post" action="">
            <input type="hidden" name="producto_id" id="delete-product-id" value="">
            <h2 class="admin-productos__dialog-title" id="delete-dialog-title">¿Eliminar producto?</h2>
            <p class="admin-productos__dialog-text">
                Estás por eliminar «<strong id="delete-product-name"></strong>».
                Esta acción no se puede deshacer.
            </p>
            <div class="admin-productos__dialog-actions">
                <button type="button" class="admin-productos__dialog-btn admin-productos__dialog-btn--cancel" id="delete-cancel">
                    Cancelar
                </button>
                <button type="submit" class="admin-productos__dialog-btn admin-productos__dialog-btn--confirm">
                    Sí, eliminar
                </button>
            </div>
        </form>
    </dialog>

    <script>
        (function () {
            var dialog = document.getElementById('delete-dialog');
            var form = document.getElementById('delete-form');
            var productIdInput = document.getElementById('delete-product-id');
            var productNameEl = document.getElementById('delete-product-name');
            var cancelBtn = document.getElementById('delete-cancel');

            document.querySelectorAll('.admin-productos__action--delete').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('data-delete-id');
                    var name = btn.getAttribute('data-delete-name');

                    productIdInput.value = id;
                    productNameEl.textContent = name;
                    form.action = 'index.php?seccion=producto-borrar&id=' + id;
                    dialog.showModal();
                });
            });

            cancelBtn.addEventListener('click', function () {
                dialog.close();
            });
        })();
    </script>
</body>

</html>
