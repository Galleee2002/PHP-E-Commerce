<?php
// require_once __DIR__ . '/../bibliotecas/noticias.php';

// $noticias = noticiasObtenerTodas();
// // $noticia = $noticias[0];

// // Traemos el id que nos piden ver que está en el query string, y
// // lo usamos para buscar la noticia.
// $id = $_GET['id'];

// foreach($noticias as $unaNoticia) {
//     if($unaNoticia['noticia_id'] == $id) {
//         $noticia = $unaNoticia;
//     }
// }

// $noticia = noticiasObtenerPorId($_GET['id']);

require_once __DIR__ . '/../clases/Noticia.php';
// $noticiaObj = new Noticia;
// $noticia = $noticiaObj->porId($_GET['id']);
$noticia = (new Noticia)->porId($_GET['id']);
?>
<div class="container">
    <article class="news-item">
        <div class="news-item_content card-body">
            <h1><?= $noticia->getTitulo(); ?></h1>
            <p><?= $noticia->getSinopsis(); ?></p>
        </div>
        <picture class="news-item_imagen">
            <source srcset="imgs/big-<?= $noticia->getImagen(); ?>" media="all and (min-width: 46.875em)">
            <img src="imgs/<?= $noticia->getImagen(); ?>" alt="<?= $noticia->getImagenDescripcion(); ?>">
        </picture>

        <div><?= $noticia->getCuerpo(); ?></div>
    </article>
</div>