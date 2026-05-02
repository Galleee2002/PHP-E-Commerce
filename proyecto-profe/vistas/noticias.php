<?php
// require_once __DIR__ . '/../bibliotecas/noticias.php';
// $noticias = noticiasObtenerTodas();
require_once __DIR__ . '/../clases/Noticia.php';
$noticia = new Noticia;
$noticias = $noticia->todas();
?>
<div class="container">
    <div>
        <h1>Últimas noticias</h1>
        <p class="news-lead">Qué está pasando.</p>
    </div>
    <div class="news-list">
        <?php
        foreach($noticias as $noticia):
        ?>
        <div class="card">
            <article class="news-item">
                <div class="news-item_content card-body">
                    <a href="index.php?seccion=noticias-leer&id=<?= $noticia->getNoticiaId(); ?>"><h2><?= $noticia->getTitulo(); ?></h2></a>
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
</div>