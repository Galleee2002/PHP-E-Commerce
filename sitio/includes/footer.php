    </main>
    <footer class="site-footer">
        <p>Galmir - Programación II - Final</p>
        <p class="site-footer__secondary">Consultas: <a href="index.php?seccion=contacto">Formulario de contacto</a></p>
    </footer>
    <?php if (!empty($estaLogueado)): ?>
        <dialog class="confirm-dialog" id="logout-dialog" aria-labelledby="logout-dialog-title">
            <div class="confirm-dialog__content">
                <h2 class="confirm-dialog__title" id="logout-dialog-title">¿Desea cerrar sesión?</h2>
                <p class="confirm-dialog__text">Vas a salir de tu cuenta. Podés volver a iniciar sesión cuando quieras.</p>
                <div class="confirm-dialog__actions">
                    <button type="button" class="confirm-dialog__btn confirm-dialog__btn--no" id="logout-cancel">
                        No
                    </button>
                    <a class="confirm-dialog__btn confirm-dialog__btn--yes" id="logout-confirm" href="index.php?seccion=salir">
                        Sí
                    </a>
                </div>
            </div>
        </dialog>
        <script>
            (function () {
                const dialog = document.getElementById('logout-dialog');
                const cancelBtn = document.getElementById('logout-cancel');

                document.querySelectorAll('.js-confirm-logout').forEach(function (link) {
                    link.addEventListener('click', function (event) {
                        event.preventDefault();

                        const menu = link.closest('details.account-menu');
                        if (menu) {
                            menu.removeAttribute('open');
                        }

                        dialog.showModal();
                    });
                });

                cancelBtn.addEventListener('click', function () {
                    dialog.close();
                });
            })();
        </script>
    <?php endif; ?>
</body>
</html>
