# Programación II

## Primer Parcial

**Profesor:** Santiago Gallino.

## Importante

Este trabajo servirá como base del segundo TP (en el cual se implementará funcionalidad con la base de datos) y Final (donde se hará un carrito de compras). Es importante tener esto presente a la hora de elegir el tema y contenido que van a utilizar.

## Consigna

Realizar un sitio tipo e-commerce en php de al menos 4 secciones (home, listado de "ítems", detalle de cada ítem, y form de contacto). El sitio debe implementar inclusión de secciones vía include/require, para evitar duplicar HTML. Debe incluir manejo de arrays y clases. El sitio debe utilizar una estructura de HTML5 semántica y correcta, y presentar estilización en CSS con un diseño acorde a la temática abordada (no se aceptarán trabajos en HTML pelado).

La entrega debe cumplir:

## Sitio

Como se estipuló, el sitio debe constar de 4 secciones/pantallas (no implica que todas deben figurar en la barra de navegación):

1. **"Home":** Pantalla introductoria que contenga, al menos, de lo que se trata el sitio, de manera que sea informativo para un nuevo visitante.
2. **"Listado":** Pantalla donde se listen los "ítems" que se estén ofreciendo, detallando lo más importante. A cada ítem debe poder clickearse para entrar a otra pantalla donde ver más contenido. Estos ítems deben provenir de un array de objetos, cargados desde un JSON, que simula la info obtenida de la base de datos.
3. **"Detalle":** Pantalla donde se muestren los detalles expandidos de cada ítem.
4. **"Contacto":** Pantalla con un formulario de contacto conceptualmente integrado al sitio. Esto es, que tenga un uso claro y útil para el usuario según la temática de la página. No es requisito que el form envíe el email.

## HTML+CSS

El código HTML debe presentar una estructura semánticamente correcta, aprovechando las etiquetas de HTML5 creadas para tal fin. Esto no significa que tiene que haber ejemplos de uso de todas las etiquetas que hay, sino que deben usarse las que correspondan para el contenido que se presente. Adicionalmente, no significa tampoco que esté prohibido el uso de divs, sino que deben utilizarse para estilización o cuando no haya una etiqueta semántica que se ajuste mejor a la situación.

El sitio debe presentar estilización en CSS, con un diseño que esté acorde al contenido y target demográfico del sitio. Pueden utilizar frameworks de CSS para ayudarse (como Bootstrap), pero debe incluir estilización personalizada por parte del alumno/a.

## PHP

El sitio debe implementar una carga de secciones dinámica en un template de base vía parámetros por GET, como se vio en la cursada.

Cada ítem (ej: producto) debe estar representado por una clase que contenga todas sus propiedades.

La lista de ítems debe ser un array, compuesto por instancias de la clase recién mencionada. La información de estos objetos debe provenir de la lectura de un archivo JSON, y que sea reutilizado tanto en el propio listado como en los detalles de cada ítem. Este punto es indispensable para aprobar el TP.

Todas las rutas del TP deben ser relativas, o absolutas calculadas dinámicamente en base a donde se ubique el proyecto. Es decir, no debe haber ninguna ruta que asuma ningún tipo de estructura de archivos/carpetas, ya sea local de la PC del alumno o de algún entorno de desarrollo (ej: Docker, Vagrant).

**Ejemplo de ruta correcta:** "imagenes/foto.jpg"

**Ejemplo de ruta correcta:** `__DIR__ . "imagenes/foto.jpg"`

**Ejemplo de ruta incorrecta:** "C:\Users\santiago\imagenes\mi-foto.jpg"

**Ejemplo de ruta incorrecta:** "http://localhost/davinci/programacion2/tp1/imagenes\mi-foto.jpg"

## Se evaluará y tendrá impacto en la nota también

- Complejidad de la tarea realizada.
- Prolijidad en el código.
- Uso de funciones creadas por el alumno.
- Uso adecuado de constantes.
- Uso de filesystem (lectura/escritura de archivos, etc).
- Coherencia en los nombres de variables, funciones, etc.
- Uso correcto de las etiquetas semánticas de HTML.
- Estilización del sitio.
- Prolijidad en la organización de la carpeta del proyecto.

## Modalidad de entrega

La entrega se realizará de manera digital, subiendo un zip/rar con una copia del proyecto completo a la publicación del campus, con el formato de nombre de archivo:

- En caso de ser 1 miembro: "apellido-nombre.zip" (ej: gallino-santiago.rar).
- En caso de ser 2 o más: "apellido1-nombre1_apellido2-nombre2.zip" (ej: gallino-santiago_noto-federico.rar).

Adicionalmente, debe incluir un archivo datos.txt con todos los datos del estudiante:

Carrera, materia, cuatrimestre, año, turno, comisión, apellido y nombre, docente, carácter de entrega (1er parcial).

El incumplimiento de cualquiera de los puntos de la modalidad de entrega va a resultar en una reducción de al menos 1 punto sobre la nota final.

Quedará a discreción del profesor si los exámenes se corrigen en clase o no. También lo hará si es necesario realizar preguntas al alumno como parte de la evaluación, ya sean con respecto a cómo se encaró la entrega, como teóricas.

**DEBE ESTAR COMPLETA SIN BORRAR NADA.**
