<?php
/*
En este archivo vamos a definir todas las funciones que tengan
que ver con noticias.

¿Qué es una función?
Es una estructura que nos permite definir un bloque de código
que quede asociado a un nombre.
A través de ese nombre, podemos invocar la ejecución de ese bloque
de código.
Las funciones son como "mini-programas" en nuestro código.
Una buena función tiene como objetivo una funcionalidad específica.

Sintaxis:
    function nombreFunction()
    {
        // código de la función...
    }

Nota: Cuando definimos la función la misma _no se ejecuta_. Se va
a ejecutar solo cuando sea invocada. Lo que hacemos es "registrarla".

Las funciones es común que se diseñen para procesar alguna
información y generar un resultado que el desarrollador que
invoca la función pueda utilizar.
Para hacer eso, simplemente llamamos a la instrucción "return" 
seguida del valor deseado:

    function nombreFunction()
    {
        // código de la función...
        return <resultado>;
    }

Nota: El return finaliza la ejecución de la función.

## docBlocks
Los docBlocks (bloques de documentación) son un tipo de comentario
especial en php que permiten agregar documentación que queremos
asociar a una determinada declaración (ej: función, variable, clase).
Solo sirven para documentar declaraciones de estructuras como las
mencionadas, y no para documentar bloques arbitrarios de código
(ej: un condicional).

Se escriben igual que un comentario multilínea, pero con dos
diferencias:
    1. Abre con dos "*" (sin comillas).
    2. Si abarca múltiples líneas, cada una debe empezar con otro
    "*" (sin comillas).

Un docBlock, salvo que se indique lo contrario, documenta la
declaración que está inmediatamente a continuación.

¿Qué beneficios me da usar un docBlock en vez de un comentario
común y corriente?
    1. Es más fácil identificar visualmente el objetivo del 
        comentario.
    2. Permiten agregar "atributos" o "etiquetas" para agregar
        "meta-data".
    3. Los editores (y otros programas) pueden parsear estos
        docBlocks para ofrecer mejores ayudas de autocompletar
        y de edición en el código.
    4. Podemos obtener desde php esta información, si así lo
        quisiéramos.


## Tipando el retorno de la función
php soporta que "tipemos" lo que una función retorna.
"Tipar" (type-hint) es cuando declaramos en el código el tipo
de dato que algo debe tener.
Hay lenguajes "fuertemente tipados" que requieren siempre para
toda variable o cualquier cosa que produce un valor explicitar
el tipo de dato.
php es un lenguaje "débilmente tipado", como sucede con JS.
Esto significa que no es necesario, o no es posible, "tipar" las 
variables ni las funciones.
En php, específicamente, se han ido agregando cada vez más lugares
donde se pueden, opcionalmente, tipar los datos.
Básicamente, salvo en la declaración de una variable, en todos lados
podemos tipar datos.

El "type-hinting" es algo que puede ser, en algunos casos, un poco
incómodo de usar. Hace el código más inflexible. Pero, por otro lado,
hace el código mucho más robusto.
Hay un montón de errores que en un lenguaje tipado simplemente no 
existen.

En la actualidad, la mayoría de los desarrolladores "tipan" todo
lo posible en php.

## Cómo tipar el retorno de una función
Con la siguiente sintaxis:

    function nombre(): <tipo-de-dato-retorno>
    {
        // Código de la función...
    }

Después de los paréntesis, y antes de la llave de apertura, escribimos
un ":" seguido del tipo de dato.

## Tipos de datos compuestos
php soporta hacer uniones de tipos de datos para el "type-hinting"
con ayuda del operador "|".

Por ejemplo, si queremos decir que una función retorna un array
o un string, escribimos:

    function nombre(): array|string
    { ... }

Se pueden unir tantos tipos de datos como se quieran:

    array|string|null|int|Saraza

Alternativamente, se puede usar la keyword "mixed" para indicar
que pueden ser múltiples tipos de datos diferentes.
Si una función no retorna nada, se puede usar el tipo de dato
void:

    function noRetornoValor(): void
    { ... }

Y, alternativamente, si queremos decir que el tipo es un valor
específico o null, lo podemos escribir:

    array|null

O como:

    ?array

El signo de pregunta delante indica que null es una posibilidad.


## Tipado de parámetros de la función
Dentro de lo posible, siempre es recomendable "tipar" también los
parámetros de la función:

    function nombre(int $miParametro, ?float $miOtroParametro) ...
*/
require_once __DIR__ . '/../clases/Noticia.php';

const NOTICIAS_JSON_ARCHIVO =  './data/noticias.json';

/**
 * Retorna un array con todas las noticias :D
 * 
 * @return Noticia[]
 */
function noticiasObtenerTodas(): array
{
    $json = file_get_contents(NOTICIAS_JSON_ARCHIVO);
    // return json_decode($json, true);
    // return json_decode(file_get_contents(NOTICIAS_JSON_ARCHIVO), true);
    
    $dataNoticias = json_decode($json, true);

    // Vamos a armar un array con los objetos noticias.
    $noticias = [];

    // Recorremos el array de noticias que leimos del JSON y 
    // con eso creamos los objetos Noticia.
    foreach($dataNoticias as $dataNoticia) {
        $noticia = new Noticia;
        $noticia->setNoticiaId($dataNoticia['noticia_id']);
        $noticia->setTitulo($dataNoticia['titulo']);
        $noticia->setSinopsis($dataNoticia['sinopsis']);
        $noticia->setCuerpo($dataNoticia['cuerpo']);
        $noticia->setImagen($dataNoticia['imagen']);
        $noticia->setImagenDescripcion($dataNoticia['imagen_descripcion']);

        // Guardamos el objeto en el array de objetos Noticia.
        // Para hacer un "push" en un array dentro de php, podemos
        // usar corchetes vacíos en la asignación.
        $noticias[] = $noticia;
        // array_push($noticias, $noticia);
    }

    return $noticias;
}


// function noticiasObtenerPorId($id): Noticia|null
function noticiasObtenerPorId(int $id): ?Noticia
{
    $noticias = noticiasObtenerTodas();

    foreach($noticias as $noticia) {
        if($noticia->getNoticiaId() == $id) {
            return $noticia;
        }
    }

    // Si no hay una noticia con ese id, retornamos "null".
    return null;
}

/*
Nota importante:
En los archivos que solo contienen código php (empiezan abriendo
un bloque de php que nunca termina), se recomienda no poner el
tag de cierre del bloque de php.
Es decir, no poner el "?>".

La principal razón es que no suma nada agregarlo y, en cambio,
puede evitar problemas en circunstancias muy específicas.
*/