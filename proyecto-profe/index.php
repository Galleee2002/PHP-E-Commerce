<?php
/*
    En php, siempre deberíamos poner toda la lógica *antes* de cualquier
    impresión de código de HTML o lo que sea.
    Esto incluye, pero no se limita a: 
    - Declaración de variables o constantes.
    - Definición de funciones.
    - Instrucciones de inicialización.
    - Cálculos.
    - Etc.
    Esto es importante porque facilita la compresión del flujo del proceso.
*/

/*
    # Definiendo de "rutas"
    En web, entendemos por rutas dentro de una aplicación como las
    URLs que el usuario puede visitar.
    Siempre es una buena asegurarnos de que se delimiten expresamente
    cuáles son las rutas que el usuario puede ver. Esto es importante
    para evitar problemas y riesgos de seguridad.
    La forma de lograr es usando una lista de rutas para registrar 
    qué se puede ver. Específicamente, una "lista blanca" (whitelist)
    de rutas.
    Cuando hablemos de listas de cosas que se permiten o no, vienen
    en 2 sabores:
    - Listas de inclusión, llamadas "whitelists" (listas blancas).
    - Listas de exclusión, llamadas "blacklists" (listas negras).

    Las whitelists listan cuáles son los valores que se permiten
    utilizar o acceder.
    Las blacklists listan cuáles son los valores que NO se permiten
    utilizar o acceder.
    Las listas blancas son mucho más seguras, pero no siempre son
    viables.

    Nuestra lista de "rutas" va a ser un array asociativo. Donde las
    claves van a ser los nombres de las rutas, y los valores van a
    ser un array de configuración.
    Por ejemplo:

    $listaRutas = [
        'home' => [
            // ... configuraciones
        ],
        'noticias' => [
            // ... configuraciones
        ],
    ];
*/
$listaRutas = [
    'home' => [
        'title' => 'Página principal',
    ],
    'noticias' => [
        'title' => 'Últimas noticias',
    ],
    'noticias-leer' => [
        'title' => 'Leer noticia',
    ],
    'ingresar' => [
        'title' => 'Ingresar a tu cuenta',
    ],
    'crear-cuenta' => [
        'title' => 'Crear una nueva cuenta',
    ],
    '404' => [
        'title' => 'Página no encontrada',
    ],
];

/*
    Obtenemos el valor de la seccion que el usuario pide ver.
    Como cuando el usuario ingrese por primera vez no va a haber un valor en
    query string que indique la sección, necesitamos poner algún valor por
    defecto. Por ejemplo, 'home'.
    Para lograrlo, pregutamos a php si existe la clave seccion del query string,
    con ayuda de la función isset().
    // if(isset($_GET['seccion'])) {
    //     $seccion = $_GET['seccion'];
    // } else {
    //     $seccion = 'home';
    // }

    El código anterior funciona bien. Pero es un poco "verboso".
    Sobretodo para algo que es relativamente común de hacer en php.
    Una forma de abreviarlo es un usando un operador ternario ( ? : ).
    // $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'home';

    Esto es una mejora. Pero es molesto tener que repretir dos veces
    la variable por la que estamos preguntando.
    Para hacerlo aún más breve y fácil de leer y escribir, php cuenta
    con el operador "null coalesce" (fusión de null): ??
*/
$seccion = $_GET['seccion'] ?? 'home';

// Verificamos si la ruta pedida es una ruta permitida. De no serlo,
// mostramos una pantalla de error.
if( !isset($listaRutas[$seccion]) ) {
    $seccion = '404';
}

// Obtenemos la configuración de la página.
$configPagina = $listaRutas[$seccion];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" sizes="any">
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="favicon-ios.png">
    <title><?= $configPagina['title']; ?> :: Saraza Basket</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <div class="layout">
        <header class="main-header">
            <p class="brand">Saraza Basket</p>
            <p>Enterate de todas las novedades sobre la NBA</p>
        </header>
        <nav class="main-nav">
            <div class="container-fixed">
                <ul>
                    <li><a href="index.php?seccion=home">Home</a></li>
                    <li><a href="index.php?seccion=noticias">Noticias</a></li>
                    <li><a href="index.php?seccion=ingresar">Ingresar</a></li>
                    <li><a href="index.php?seccion=crear-cuenta">Crear cuenta</a></li>
                </ul>
            </div>
        </nav>
        <main class="main-content">
            <?php
            require __DIR__ . '/vistas/' . $seccion . '.php';
            // require __DIR__ . '/vistas/home.php';
            // require __DIR__ . '/vistas/noticias.php';
            // require __DIR__ . '/vistas/ingresar.php';
            // require __DIR__ . '/vistas/crear-cuenta.php';
            ?>
        </main>
        <footer class="main-footer">
            <p>&copy; Da Vinci - 2026</p>
        </footer>
    </div>
</body>

</html>