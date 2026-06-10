<?php
$listaRutas = [
    'ingresar' => [
        'title' => 'Ingresar a tu cuenta',
    ],
    'noticias' => [
        'title' => 'Administrar noticias',
    ],
    '404' => [
        'title' => 'Página no encontrada',
    ],
];

$seccion = $_GET['seccion'] ?? 'ingresar';

if( !isset($listaRutas[$seccion]) ) {
    $seccion = '404';
}

$configPagina = $listaRutas[$seccion];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../favicon.ico" sizes="any">
    <link rel="icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="../favicon-ios.png">
    <title><?= $configPagina['title']; ?> :: Panel de administración de Saraza Basket</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
    <div class="layout">
        <header class="main-header">
            <p class="brand">Panel de administración de Saraza Basket</p>
        </header>
        <nav class="main-nav">
            <div class="container-fixed">
                <ul>
                    <li><a href="index.php?seccion=noticias">Administrar noticias</a></li>
                </ul>
            </div>
        </nav>
        <main class="main-content">
            <?php
            require __DIR__ . '/vistas/' . $seccion . '.php';
            ?>
        </main>
        <footer class="main-footer">
            <p>&copy; Da Vinci - 2026</p>
        </footer>
    </div>
</body>

</html>