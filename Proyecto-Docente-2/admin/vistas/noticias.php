<?php
// require_once __DIR__ . '/../bibliotecas/noticias.php';
// $noticias = noticiasObtenerTodas();
require_once __DIR__ . '/../../clases/Noticia.php';
$noticia = new Noticia;
$noticias = $noticia->todas();
?>
<div class="container">
    <h1>Administración de noticias</h1>

    <div class="mb-1">
        <!-- TODO: Hacer el alta :D -->
        <a href="#">Publicar una nueva noticia</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Fecha de publicación</th>
                <th>Título</th>
                <th>Sinopsis</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($noticias as $noticia): ?>
            <tr>
                <td><?= $noticia->getFechaPublicacion(); ?></td>
                <td><?= $noticia->getTitulo(); ?></td>
                <td><?= $noticia->getSinopsis(); ?></td>
                <td><img src="<?= "../imgs/" . $noticia->getSinopsis(); ?>" alt=""></td>
                <td>Coming soon&trade;</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>