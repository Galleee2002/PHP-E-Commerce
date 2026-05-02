<?php
// En vez de definir el array de manera local, podemos importarlo
// desde otro archivo.
// Esto funciona, pero no es ideal.
// Si solo vemos el código de este archivo, en ningún figura
// que existe una variable "$noticias".
// En general, suele ser una mejor idea que el código sea más
// "explícito" y menos "mágico".
// Es por eso que, en vez de importar el archivo que define el array
// en la variable '$noticias' directamente, vamos a importar un 
// archivo de biblioteca, que nos ofrezca una función para obtener
// las noticias.
// require_once __DIR__ . '/../data/noticias.php';
require_once __DIR__ . '/../bibliotecas/noticias.php';

$noticias = noticiasObtenerTodas();
?>
<div class="home-section">
    <h1 class="visually-hidden">Página principal de Saraza Basket</h1>
    <section class="ranking">
        <div class="ranking-lead">
            <h2>Ranking</h2>
            <p>La carrera por los playoffs.</p>
            <p>Este es la tabla de posiciones en la carrera hacia los playoffs.</p>
        </div>

        <div class="ranking-conferences">
            <section class="west-conference">
                <h3>Conferencia Oeste</h3>
                <ol class="positions">
                    <li>Houston Rockets</li>
                    <li>Golden State</li>
                    <li>Portland Trailblazers</li>
                    <li>Oklahoma City Thunder</li>
                    <li>Utah Jazz</li>
                    <li>New Orleans Pelicans</li>
                    <li>San Antonio Spurs</li>
                    <li>Minnesota Timberwolves</li>
                    <li>Denver Nuggets</li>
                    <li>Los Angeles Clippers</li>
                    <li>Los Angeles Lakers</li>
                    <li>Sacramento Kings</li>
                    <li>Dallas Mavericks</li>
                    <li>Memphis Grizzles</li>
                    <li>Phoenix Suns</li>
                </ol>
            </section>

            <section class="east-conference">
                <h3>Conferencia Este</h3>
                <ol class="positions">
                    <li>Toronto Raptors</li>
                    <li>Boston Celtics</li>
                    <li>Philadelphia 76ers</li>
                    <li>Cleveland Cavaliers</li>
                    <li>Indiana Pacers</li>
                    <li>Miami Heat</li>
                    <li>Milwaukee Bucks</li>
                    <li>Washinton Wizards</li>
                    <li>Detroit Pistons</li>
                    <li>Charlotte Hornets</li>
                    <li>New York Knicks</li>
                    <li>Brooklyn Nets</li>
                    <li>Chicago Bulls</li>
                    <li>Orlando Magic</li>
                    <li>Atlanta Hawks</li>
                </ol>
            </section>
        </div>
    </section>

    <section class="news-home">
        <div>
            <h2>Noticias</h2>
            <p class="news-lead">Qué está pasando.</p>
        </div>
        <div class="news-list">
            <?php
            foreach($noticias as $noticia):
            ?>
            <div class="card">
                <article class="news-item">
                    <div class="news-item_content card-body">
                        <a href="index.php?seccion=noticias-leer&id=<?= $noticia->getNoticiaId(); ?>"><h3><?= $noticia->getTitulo(); ?></h3></a>
                        <p><?= $noticia->getSinopsis(); ?></p>
                    </div>
                    <picture class="news-item_imagen">
                        <source srcset="imgs/big-<?= $noticia->getImagen(); ?>" media="all and (min-width: 46.875em)">
                        <img src="imgs/<?= $noticia->getImagen(); ?>" alt="<?= $noticia->getImagenDescripcion(); ?>">
                    </picture>
                </article>
            </div>
            <?php
            endforeach;
            ?>
        </div>
    </section>
</div>