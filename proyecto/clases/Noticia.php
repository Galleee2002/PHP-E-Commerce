<?php

// TODO: Reemplazar con el autoload.
require_once __DIR__ . '/DBConexion.php';

class Noticia
{
    /*
        Dentro de las clases podemos definir constantes.
        Estas constantes quedan asociadas a la clase, y no
        colisionan con lo son las constantes "globales".

        Para luego poder acceder a las constantes definidas en
        una clase, es necesario especificar primero la clase,
        seguida del "operador de resolución de ámbito" (scope
        resolution operator), seguido del nombre de la constante.
        Por ejemplo:

            Clase::CONSTANTE

        El "::" se llama, como mencionamos arriba, el operador
        de resolución de ámbito.
        Esto es porque este operador resuelve (en el sentido de
        "define") en qué ámbito está el valor.

        Trivia: En php, este operador tiene otro nombre que era
        originalmente el oficial: Paamayim Nekudotayim
    */
    public const NOTICIAS_JSON_ARCHIVO =  './data/noticias.json';

    private int $noticia_id = 0;
    private int $usuario_fk = 0;
    private string $titulo = "";
    private string $sinopsis = "";
    private string $cuerpo = "";
    private string $imagen = "";
    private string $imagen_descripcion = "";
    private string $fecha_publicacion = "";

    // public function __construct(
    //     private int $noticia_id = 0,
    //     private string $titulo = "",
    //     private string $sinopsis = "",
    //     private string $cuerpo = "",
    //     private string $imagen = "",
    //     private string $imagen_descripcion = "",
    // )
    // {}

    /**
     * Retorna un array con todas las noticias :D
     * 
     * @return self[]
     */
    public function todasVersionJson(): array
    {
        $json = file_get_contents(self::NOTICIAS_JSON_ARCHIVO);
        // return json_decode($json, true);
        // return json_decode(file_get_contents(NOTICIAS_JSON_ARCHIVO), true);
        
        $dataNoticias = json_decode($json, true);

        // Vamos a armar un array con los objetos noticias.
        $noticias = [];

        // Recorremos el array de noticias que leimos del JSON y 
        // con eso creamos los objetos Noticia.
        foreach($dataNoticias as $dataNoticia) {
            // $noticia = new Noticia;
            // "self" es una palabra clave que significa "la
            // clase en la que estoy".
            // Por ejempo, si estoy ahora en la clase Noticia,
            // self === Noticia
            // $noticia = new self;
            // $noticia->setNoticiaId($dataNoticia['noticia_id']);
            // $noticia->setTitulo($dataNoticia['titulo']);
            // $noticia->setSinopsis($dataNoticia['sinopsis']);
            // $noticia->setCuerpo($dataNoticia['cuerpo']);
            // $noticia->setImagen($dataNoticia['imagen']);
            // $noticia->setImagenDescripcion($dataNoticia['imagen_descripcion']);
            $noticia = new self(
                $dataNoticia['noticia_id'],
                $dataNoticia['titulo'],
                $dataNoticia['sinopsis'],
                $dataNoticia['cuerpo'],
                $dataNoticia['imagen'],
                $dataNoticia['imagen_descripcion']
            );

            // Guardamos el objeto en el array de objetos Noticia.
            // Para hacer un "push" en un array dentro de php, podemos
            // usar corchetes vacíos en la asignación.
            $noticias[] = $noticia;
            // array_push($noticias, $noticia);
        }

        return $noticias;
    }

    /**
     * @return self[]
     */
    public function todas(): array
    {
        // Traemos los datos de la base.
        $db = (new DBConexion)->getConexion();

        $consulta = "SELECT * FROM noticias";
        $stmt = $db->prepare($consulta);
        $stmt->execute();

        // Retornamos todos los registros del SELECT como 
        // instancias de esta clase.
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetchAll();
    }

    public function porId(int $id): ?self
    {
        $db = (new DBConexion)->getConexion();

        /*
            !! ADVERTENCIA !!
            El query como lo tenemos abajo es *inseguro*.
            Es completamente vulnerable a ataques de "inyección SQL".

        */
        // $consulta = "SELECT * FROM noticias
        //             WHERE noticia_id = " . $id;

        // Versión segura con "holders posicionales".
        // $consulta = "SELECT * FROM noticias
        //             WHERE noticia_id = ?";
        
        // $stmt = $db->prepare($consulta);
        // $stmt->execute([$id]);

        // Versión segura con "holders denominados".
        $consulta = "SELECT * FROM noticias
                    WHERE noticia_id = :id";
        
        $stmt = $db->prepare($consulta);
        $stmt->execute(['id' => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        // Como filtramos por PK, solo puede haber un único resultado.
        $noticia = $stmt->fetch();

        if(!$noticia) return null;

        return $noticia;
    }

    public function getNoticiaId(): int
    {
        return $this->noticia_id;
    }

    public function setNoticiaId(int $noticia_id): void
    {
        $this->noticia_id = $noticia_id;
    }

    public function getUsuarioFk(): int
    {
        return $this->usuario_fk;
    }

    public function setUsuarioFk(int $usuario_fk): void
    {
        $this->usuario_fk = $usuario_fk;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getSinopsis(): string
    {
        return $this->sinopsis;
    }

    public function setSinopsis(string $sinopsis): void
    {
        $this->sinopsis = $sinopsis;
    }

    public function getCuerpo(): string
    {
        return $this->cuerpo;
    }

    public function setCuerpo(string $cuerpo): void
    {
        $this->cuerpo = $cuerpo;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getImagenDescripcion(): string
    {
        return $this->imagen_descripcion;
    }

    public function setImagenDescripcion(string $imagen_descripcion): void
    {
        $this->imagen_descripcion = $imagen_descripcion;
    }

    public function getFechaPublicacion(): string
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(string $fecha_publicacion): void
    {
        $this->fecha_publicacion = $fecha_publicacion;
    }
}