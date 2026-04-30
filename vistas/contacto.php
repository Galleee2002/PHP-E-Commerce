<section class="panel" aria-labelledby="titulo-contacto">
    <h1 class="page-title" id="titulo-contacto">Contacto</h1>
    <p class="lead">
        Si tenes dudas sobre un juego, disponibilidad o recomendaciones, escribinos.
    </p>

    <form class="contact-form" action="#" method="post">
        <p>
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </p>
        <p>
            <label for="asunto">Asunto</label>
            <input type="text" id="asunto" name="asunto" required>
        </p>
        <p>
            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
        </p>
        <p>
            <button class="btn btn--accent" type="submit">Enviar consulta</button>
        </p>
    </form>
</section>
