<section class="contact-page" aria-labelledby="titulo-contacto">
    <div class="contact-page__hero">
        <div class="contact-page__hero-copy">
            <h1 class="contact-page__title" id="titulo-contacto">¿Tenes alguna duda o consulta?
                Escribinos y te responderemos a la brevedad</h1>
            <p class="contact-page__lead">Completa el formulario con tus datos y contanos en qué te podemos ayudar.</p>
        </div>
        <div class="contact-page__hero-art">
            <img src="imgs/formulario.png" alt="Ilustración del formulario de contacto">
        </div>
    </div>

    <div class="contact-page__content">
        <form class="contact-form" action="#" method="post" novalidate>
            <div class="contact-form__grid">
                <div class="contact-field">
                    <label class="contact-field__label" for="nombre">
                        <img class="contact-field__label-icon" src="imgs/nombre.png" alt="" aria-hidden="true">
                        <span>Nombre</span>
                    </label>
                    <div class="contact-field__control">
                        <input id="nombre" name="nombre" type="text" placeholder="Tu nombre" required>
                    </div>
                </div>

                <div class="contact-field">
                    <label class="contact-field__label" for="email">
                        <img class="contact-field__label-icon" src="imgs/correo-electronico.png" alt="" aria-hidden="true">
                        <span>Email</span>
                    </label>
                    <div class="contact-field__control">
                        <input id="email" name="email" type="email" placeholder="tu@email.com" required>
                    </div>
                </div>

                <div class="contact-field">
                    <label class="contact-field__label" for="asunto">
                       <img class="contact-field__label-icon" src="imgs/asunto.png" alt="" aria-hidden="true">
                        <span>Asunto</span>
                    </label>
                    <div class="contact-field__control">
                        <select id="asunto" name="asunto" required>
                            <option value="">Elegí un asunto</option>
                            <option value="consulta">Consulta general</option>
                            <option value="soporte">Soporte técnico</option>
                            <option value="sugerencia">Sugerencia</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="contact-field">
                    <label class="contact-field__label" for="motivo">
                        <img class="contact-field__label-icon" src="imgs/motivo-de-contacto.png" alt="" aria-hidden="true">
                        <span>Motivo de contacto</span>
                    </label>
                    <div class="contact-field__control">
                        <select id="motivo" name="motivo" required>
                            <option value="">Elegí un motivo</option>
                            <option value="pedido">Problema con pedido</option>
                            <option value="informacion">Necesito más información</option>
                            <option value="colaboracion">Propuesta o colaboración</option>
                            <option value="otro">Otro motivo</option>
                        </select>
                    </div>
                </div>

                <div class="contact-field contact-field--full">
                    <label class="contact-field__label" for="mensaje">
                        <img class="contact-field__label-icon" src="imgs/mensaje.png" alt="" aria-hidden="true">
                        <span>Mensaje</span>
                    </label>
                    <div class="contact-field__control">
                        <textarea id="mensaje" name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
                    </div>
                </div>
            </div>

            <div class="contact-form__actions">
                <button class="contact-btn contact-btn--accent" type="button" id="contacto-enviar">
                    <span>Enviar mensaje</span>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.contact-form');
    var enviar = document.getElementById('contacto-enviar');
    enviar.addEventListener('click', function () {
      if (form.reportValidity()) {
        alert('Tu mensaje se envió con éxito. ¡Gracias por contactarnos!');
      }
    });
  });
</script>
