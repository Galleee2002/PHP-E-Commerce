<?php

const NOTICIAS_JSON_ARCHIVO =  './data/noticias.json';

class Noticia
{
    private int $noticia_id = 0;
    private string $titulo = "";
    private string $sinopsis = "";
    private string $cuerpo = "";
    private string $imagen = "";
    private string $imagen_descripcion = "";

    /**
     * Retorna un array con todas las noticias :D
     * 
     * @return self[]
     */
    public function todas(): array
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
            // $noticia = new Noticia;
            // "self" es una palabra clave que significa "la
            // clase en la que estoy".
            // Por ejempo, si estoy ahora en la clase Noticia,
            // self === Noticia
            $noticia = new self;
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

    public function porId(int $id): ?self
    {
        $noticias = $this->todas();

        foreach($noticias as $noticia) {
            if($noticia->getNoticiaId() == $id) {
                return $noticia;
            }
        }

        // Si no hay una noticia con ese id, retornamos "null".
        return null;
    }

    public function getNoticiaId(): int
    {
        return $this->noticia_id;
    }

    public function setNoticiaId(int $noticia_id): void
    {
        $this->noticia_id = $noticia_id;
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
}