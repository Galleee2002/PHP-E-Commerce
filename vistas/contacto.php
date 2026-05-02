<?php
$jugadores = $_POST['jugadores'] ?? '';
$edad = $_POST['edad'] ?? '';
$duracion = $_POST['duracion'] ?? '';
$dificultad = $_POST['dificultad'] ?? '';
$estilo = $_POST['estilo'] ?? '';
$formularioEnviado = $_SERVER['REQUEST_METHOD'] === 'POST';
$productoRecomendado = null;
$motivoRecomendacion = '';

function productoPorNombre(array $productos, string $nombre): ?Producto
{
    foreach ($productos as $producto) {
        if ($producto->getNombre() === $nombre) {
            return $producto;
        }
    }

    return null;
}

if ($formularioEnviado) {
    if ($estilo === 'misterio') {
        $productoRecomendado = productoPorNombre($productos, 'Clue');
        $motivoRecomendacion = 'Tiene deduccion, pistas y una dinamica ideal para quienes disfrutan resolver casos.';
    } elseif ($estilo === 'estrategia' && $dificultad === 'alta') {
        $productoRecomendado = productoPorNombre($productos, 'T.E.G.');
        $motivoRecomendacion = 'Es una buena opcion para grupos que buscan planificar, competir y sostener una partida mas desafiante.';
    } elseif ($estilo === 'negociacion') {
        $productoRecomendado = productoPorNombre($productos, 'Monopoly');
        $motivoRecomendacion = 'Combina administracion, compra de propiedades y negociacion entre jugadores.';
    } elseif ($estilo === 'rompecabezas' || $jugadores === '1') {
        $productoRecomendado = productoPorNombre($productos, 'Rompecabezas Starry Sky');
        $motivoRecomendacion = 'Funciona muy bien para jugar solo o compartir una actividad tranquila y concentrada.';
    } elseif ($estilo === 'cartas' && $dificultad === 'media') {
        $productoRecomendado = productoPorNombre($productos, 'Burako');
        $motivoRecomendacion = 'Tiene estrategia liviana, combinaciones de cartas y partidas faciles de retomar.';
    } else {
        $productoRecomendado = productoPorNombre($productos, 'No Lo Testeamos Ni Un Poco');
        $motivoRecomendacion = 'Es rapido, simple de explicar y funciona muy bien para reuniones con amigos o familia.';
    }
}
?>

<section class="panel" aria-labelledby="titulo-contacto">
    <h1 class="page-title" id="titulo-contacto">Recomendador de juegos</h1>
    <p class="lead">
        Completá tu perfil de juego y te sugerimos una opción del catálogo.
    </p>

    <?php if ($formularioEnviado && $productoRecomendado !== null): ?>
        <article class="recommendation" aria-labelledby="titulo-recomendacion">
            <div>
                <p class="recommendation__eyebrow">Juego recomendado</p>
                <h2 class="recommendation__title" id="titulo-recomendacion"><?= $productoRecomendado->getNombre() ?></h2>
                <p class="recommendation__text"><?= $motivoRecomendacion ?></p>
                <p class="recommendation__meta">
                    <strong>Categoria:</strong> <?= $productoRecomendado->getCategoria() ?>
                </p>
                <p class="recommendation__meta">
                    <strong>Precio:</strong> $<?= number_format($productoRecomendado->getPrecio(), 0, ',', '.') ?>
                </p>
                <a class="btn btn--accent" href="index.php?seccion=detalle&id=<?= urlencode((string) $productoRecomendado->getId()) ?>">Ver detalle</a>
            </div>
            <img class="recommendation__img" src="<?= $productoRecomendado->getImagen() ?>" alt="<?= $productoRecomendado->getNombre() ?>">
        </article>
    <?php endif; ?>

    <form class="contact-form" action="index.php?seccion=contacto" method="post">
        <p>
            <label for="jugadores">Cantidad de jugadores</label>
            <select id="jugadores" name="jugadores" required>
                <option value="">Elegí una opción</option>
                <option value="1" <?= $jugadores === '1' ? 'selected' : '' ?>>1 jugador</option>
                <option value="2-4" <?= $jugadores === '2-4' ? 'selected' : '' ?>>2 a 4 jugadores</option>
                <option value="5+" <?= $jugadores === '5+' ? 'selected' : '' ?>>5 o más jugadores</option>
            </select>
        </p>
        <p>
            <label for="edad">Rango de edad</label>
            <select id="edad" name="edad" required>
                <option value="">Elegí una opción</option>
                <option value="ninos" <?= $edad === 'ninos' ? 'selected' : '' ?>>Niños</option>
                <option value="familia" <?= $edad === 'familia' ? 'selected' : '' ?>>Familia</option>
                <option value="adultos" <?= $edad === 'adultos' ? 'selected' : '' ?>>Adultos</option>
            </select>
        </p>
        <p>
            <label for="duracion">Duración estimada</label>
            <select id="duracion" name="duracion" required>
                <option value="">Elegí una opción</option>
                <option value="corta" <?= $duracion === 'corta' ? 'selected' : '' ?>>Hasta 30 minutos</option>
                <option value="media" <?= $duracion === 'media' ? 'selected' : '' ?>>30 a 60 minutos</option>
                <option value="larga" <?= $duracion === 'larga' ? 'selected' : '' ?>>Más de 60 minutos</option>
            </select>
        </p>
        <p>
            <label for="dificultad">Nivel de dificultad</label>
            <select id="dificultad" name="dificultad" required>
                <option value="">Elegí una opción</option>
                <option value="baja" <?= $dificultad === 'baja' ? 'selected' : '' ?>>Baja</option>
                <option value="media" <?= $dificultad === 'media' ? 'selected' : '' ?>>Media</option>
                <option value="alta" <?= $dificultad === 'alta' ? 'selected' : '' ?>>Alta</option>
            </select>
        </p>
        <p>
            <label for="estilo">Estilo de juego</label>
            <select id="estilo" name="estilo" required>
                <option value="">Elegí una opción</option>
                <option value="cartas" <?= $estilo === 'cartas' ? 'selected' : '' ?>>Cartas</option>
                <option value="estrategia" <?= $estilo === 'estrategia' ? 'selected' : '' ?>>Estrategia</option>
                <option value="misterio" <?= $estilo === 'misterio' ? 'selected' : '' ?>>Misterio</option>
                <option value="negociacion" <?= $estilo === 'negociacion' ? 'selected' : '' ?>>Negociación</option>
                <option value="rompecabezas" <?= $estilo === 'rompecabezas' ? 'selected' : '' ?>>Rompecabezas</option>
            </select>
        </p>
        <p class="contact-form__actions">
            <button class="btn btn--accent" type="submit">Recibir recomendación</button>
        </p>
    </form>
</section>
