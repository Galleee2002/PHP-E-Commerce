<section class="rounded-2xl border border-primary-dark bg-warm-white p-6" aria-labelledby="titulo-contacto">
    <h1 class="text-3xl font-bold text-soft-black md:text-4xl" id="titulo-contacto">Contacto</h1>
    <p class="mt-4 text-text-soft">
        Si tenes dudas sobre un juego, disponibilidad o recomendaciones, escribinos.
    </p>

    <form class="mt-5 space-y-4 rounded-xl border border-primary-dark bg-beige-light p-4 md:p-6" action="#" method="post">
        <p>
            <label class="mb-1 block text-sm font-semibold text-soft-black" for="nombre">Nombre</label>
            <input class="w-full rounded-lg border border-primary-dark bg-warm-white px-3 py-2 text-soft-black" type="text" id="nombre" name="nombre" required>
        </p>
        <p>
            <label class="mb-1 block text-sm font-semibold text-soft-black" for="email">Email</label>
            <input class="w-full rounded-lg border border-primary-dark bg-warm-white px-3 py-2 text-soft-black" type="email" id="email" name="email" required>
        </p>
        <p>
            <label class="mb-1 block text-sm font-semibold text-soft-black" for="asunto">Asunto</label>
            <input class="w-full rounded-lg border border-primary-dark bg-warm-white px-3 py-2 text-soft-black" type="text" id="asunto" name="asunto" required>
        </p>
        <p>
            <label class="mb-1 block text-sm font-semibold text-soft-black" for="mensaje">Mensaje</label>
            <textarea class="w-full rounded-lg border border-primary-dark bg-warm-white px-3 py-2 text-soft-black" id="mensaje" name="mensaje" rows="5" required></textarea>
        </p>
        <p>
            <button class="inline-flex items-center rounded-lg border border-accent bg-accent px-4 py-2 text-sm font-semibold text-warm-white hover:bg-primary-dark" type="submit">Enviar consulta</button>
        </p>
    </form>
</section>