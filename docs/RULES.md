# Estándares del proyecto — Programación II (E-commerce PHP)

**Materia:** Programación II  
**Docente:** Santiago Gallino  

Este documento alinea el trabajo con la **consigna del primer parcial** (`docs/Programacion-II-Primer-Parcial.md`) y con los **lineamientos y patrones** presentados en cursada (referencia de implementación: material tipo `proyecto-profe/`). No sustituye la consigna publicada en el campus: ante cualquier duda, prevalece el texto oficial del parcial. Los patrones de cursada pueden incluir comentarios con fines didácticos; **en el código de este proyecto aplica la regla de cero comentarios** (sección 3.6).

**Contexto:** este TP es base del segundo parcial (base de datos) y del final (carrito). Conviene elegir temática y datos que puedan evolucionar sin rehacer todo el sitio.

---

## 1. Alcance obligatorio del sitio (consigna)

El sitio es un **e-commerce conceptual** en PHP con **al menos cuatro secciones o pantallas** (no es obligatorio que las cuatro estén todas en la barra de navegación):

| Sección | Requisito |
|--------|-----------|
| **Home** | Introducción al sitio: qué ofrece, para quién es útil un visitante nuevo. |
| **Listado** | Lista de ítems (productos u oferta) con lo esencial. Cada ítem debe poder enlazarse a la pantalla de detalle. Los ítems provienen de un **array de objetos** cargados desde **JSON** (simula datos que vendrían de una base). **Indispensable para aprobar.** |
| **Detalle** | Contenido ampliado del ítem elegido (misma entidad que en el listado). |
| **Contacto** | Formulario coherente con la temática y útil para el usuario. **No** es requisito que el formulario envíe correo real. |

Además:

- **include / require** para armar páginas sin duplicar el mismo HTML en cada archivo.
- Uso de **arrays** y **clases** donde corresponda (ver sección 3).
- **HTML5 semántico** y **CSS** acorde a la temática (no se acepta entrega en “HTML pelado” sin estilización).

---

## 2. HTML y CSS (alineado a consigna y cursada)

### HTML

- Usar etiquetas semánticas según el contenido: `header`, `nav`, `main`, `section`, `article`, `footer`, etc. No hace falta usar “todas” las etiquetas HTML5; sí las que correspondan. Los `div` están permitidos para layout o cuando no exista una etiqueta más precisa.
- Formularios: cada `input` (y controles equivalentes) con `label` asociado por `for` / `id` cuando aplique.
- Jerarquía de títulos coherente: `h1`, `h2`, … según la estructura de la página.

### CSS

- Estilización propia acorde al público y al tema del sitio.
- Se puede usar un framework CSS (por ejemplo Bootstrap) como apoyo, pero debe haber **estilos propios** (por ejemplo en `css/estilos.css` o archivos importados desde ahí) que den identidad al proyecto.
- Imágenes contenidas: por ejemplo `max-width: 100%` donde haga falta para evitar desbordes.
- Organización razonable de clases, espaciado y legibilidad.
- Sin comentarios en archivos `.html` / plantillas ni en `.css` del proyecto (detalle en sección 3.6).

---

## 3. PHP — arquitectura como en cursada

### 3.1 Punto de entrada y secciones por GET

- El flujo del sitio se concentra en **`index.php`** (o equivalente único de entrada).
- Las pantallas se eligen con **parámetro por query string**, como en clase: por ejemplo `index.php?seccion=home`, `index.php?seccion=listado`, etc.
- **Lista blanca (whitelist) de rutas:** se define explícitamente qué valores de `seccion` son válidos. No se debe poder incluir archivos arbitrarios a partir de entrada del usuario.
- **Valor por defecto** de sección si no viene en la URL: por ejemplo `$seccion = $_GET['seccion'] ?? 'home';` (operador de fusión null, visto en cursada).
- **Sección inválida:** el material de clase redirige el flujo a una vista de error (por ejemplo `404`). Es el patrón recomendado frente a valores no permitidos.
- **Orden del flujo:** toda la lógica PHP que prepara variables, rutas y datos debe ejecutarse **antes** de enviar HTML al navegador, como se enfatiza en el ejemplo del profesor.

**Dos formas válidas de armar el layout (evitar duplicar HTML):**

1. **Patrón del material de clase:** el documento HTML completo (DOCTYPE, `head`, `header`, `nav`, `footer`, etc.) vive en `index.php` y solo el contenido variable del `<main>` se resuelve con `require` / `require_once` apuntando a `vistas/<seccion>.php`.
2. **Patrón por partials:** `index.php` incluye primero un encabezado común, luego la vista de la sección, luego un pie común (`include_once` / `require_once` de archivos en `includes/` o similar), siempre respetando lista blanca y el mismo criterio de orden (lógica antes de salida).

En ambos casos, la consigna de **no duplicar** bloques enteros de marca y navegación se cumple con includes claros.

### 3.2 Inclusión dinámica de la vista de la sección

- Patrón de cursada: si la sección es válida, incluir el archivo de vista correspondiente, por ejemplo:
  - `require __DIR__ . '/vistas/' . $seccion . '.php';`
- El nombre del archivo de vista debe coincidir con la clave permitida en la lista blanca (por ejemplo `listado.php` para la sección `listado`).

### 3.3 Clase del ítem y JSON (patrón tipo `Noticia` / `Producto`)

Cada ítem de catálogo (producto, servicio, etc.) debe modelarse con una **clase** que concentre **todas** sus propiedades.

**Propiedades y encapsulación (como en el ejemplo de clase):**

- Propiedades **privadas** con tipado cuando se use la versión de PHP del entorno (por ejemplo `private int`, `private string`, `private float`).
- **Getters y setters** para acceder y asignar cada campo (`getNombre()`, `setNombre(string $nombre)`, etc.).
- **Constante de ruta al JSON** en el archivo de la clase, **antes** de la declaración de la clase, con ruta relativa al proyecto (mismo estilo que en cursada):

```php
const PRODUCTOS_JSON_ARCHIVO = './data/productos.json';

class Producto
{
}
```

- **Lectura del listado:** método `todas(): array` que:
  1. Lee el archivo con `file_get_contents(...)` usando la constante.
  2. Decodifica con `json_decode($json, true)`.
  3. Recorre el array resultante con `foreach`, instancia la clase (`new self` o `new Producto`), asigna campos con los **setters** y acumula instancias en un array de objetos.
- **Lectura de un ítem por identificador:** método `porId(...): ?self` que obtiene la lista con `$this->todas()`, recorre con `foreach` y compara el identificador con el patrón visto en clase (por ejemplo `==` sobre el id). Si no hay coincidencia, devuelve `null`.

La información de catálogo **no** debe estar hardcodeada en PHP como fuente principal: debe vivir en **`/data/*.json`** y convertirse en objetos en tiempo de ejecución. Ese mismo origen debe alimentar **listado** y **detalle** (misma clase y mismo JSON; en detalle se usa típicamente `porId` con el `id` recibido por GET, como en `noticias-leer.php` del material de clase).

**Vista de detalle:** incluir la clase con `require_once` y obtener el ítem con el patrón visto en cursada, por ejemplo `(new Producto)->porId($_GET['id']);`, accediendo a los datos solo con **getters** en el HTML.

**Listado:** iterar con `foreach` sobre el array de instancias devuelto por `todas()` (o sobre la variable que exponga `index.php` tras cargar el catálogo).

### 3.4 Funciones auxiliares y carpeta `bibliotecas/` (opcional)

En el material de clase también aparece el patrón de **funciones** en un archivo bajo `bibliotecas/` (por ejemplo `noticiasObtenerTodas()`), equivalente en lógica a lo que hace la clase. Es **opcional**: lo importante es **una** vía clara y coherente (clase **o** funciones bien delimitadas), sin duplicar la misma lógica en dos sitios sin motivo didáctico.

### 3.5 Criterios de estilo que impactan nota (según consigna)

La consigna explícita menciona, entre otros:

- Prolijidad y nombres coherentes (variables, funciones, métodos, archivos).
- **Funciones / métodos propios** que organicen el código.
- **Constantes** donde aporten claridad (por ejemplo ruta del JSON).
- Uso del **filesystem** (lectura del JSON como mínimo).

No se exige un framework PHP ni patrones avanzados no vistos en la materia: prioridad a código **simple, legible y defendible** en la entrega oral.

### 3.6 Código sin comentarios (vigente en este proyecto)

En **todo** el código fuente del proyecto entregable (PHP, HTML embebido en vistas, CSS y JavaScript si hubiera) **no** deben incluirse comentarios del lenguaje:

- Ningún comentario de línea (`//`) ni de bloque (`/* */`) en PHP o JavaScript.
- Ningún comentario en hojas CSS (`/* */`).
- Ningún comentario en marcado HTML (`<!-- -->`).
- Ningún docBlock ni PHPDoc en PHP (`/** ... */`).

El código debe explicarse por sí mismo mediante **nombres claros** de variables, funciones, métodos y clases. Las explicaciones para el docente van en **`datos.txt`**, en documentación en `/docs` si corresponde, o en la **defensa oral**.

El material de cursada (`proyecto-profe`, PDFs o código comentado en clase) sirve como **referencia de arquitectura y patrones**, no como modelo de estilo de comentarios para la entrega de este repositorio.

---

## 4. Rutas y portabilidad (consigna oficial)

Queda **prohibido** usar rutas fijas del disco de la máquina del alumno o URLs absolutas al entorno local del estilo:

- `C:\Users\...`
- `http://localhost/.../ruta\mezclada`

**Válido:**

- Rutas **relativas al proyecto** respecto del directorio de trabajo habitual al abrir el sitio (por ejemplo `./data/productos.json`, `imgs/logo.png`), como en el ejemplo de clase para el JSON.
- Rutas armadas con **`__DIR__`** cuando se prefiera anclar el archivo al directorio del script (la consigna del parcial cita explícitamente `__DIR__` como ejemplo correcto).

No debe asumirse una carpeta concreta del PC del docente ni de Docker/Vagrant más allá de que el proyecto se descomprime y se sirve desde la raíz del virtual host o carpeta del servidor.

---

## 5. Estructura de carpetas recomendada

Organización clara y predecible (ajustes menores son aceptables si se documentan):

| Ruta | Uso |
|------|-----|
| `/css` | Hojas de estilo del sitio. |
| `/vistas` | Una vista PHP por sección permitida (`home.php`, `listado.php`, `detalle.php`, `contacto.php`, `404.php` si aplica). |
| `/imgs` | Imágenes de productos, logos, favicon, etc. |
| `/data` | JSON de catálogo (y eventualmente otros datos estáticos del TP). |
| `/clases` | Clase del ítem (por ejemplo `Producto.php`) con constante de ruta y métodos `todas` / `porId`. |
| `/includes` | Opcional: fragmentos HTML/PHP reutilizables (cabecera, pie, etc.). |
| `/bibliotecas` | Opcional: funciones de apoyo alineadas a la cursada. |

---

## 6. Modalidad de entrega (obligatorio — consigna)

Incumplir cualquiera de estos puntos implica **al menos un punto de descuento** sobre la nota final, según texto del parcial.

### Archivo `datos.txt` (en la raíz del proyecto)

Debe incluir, como mínimo:

- Carrera y materia  
- Cuatrimestre y año  
- Turno y comisión  
- Apellido y nombre del alumno o integrantes  
- Docente: Santiago Gallino  
- Carácter de la entrega: **1er Parcial**

### Comprimido

- **Un integrante:** `apellido-nombre.zip` (o `.rar`).  
- **Dos o más integrantes:** `apellido1-nombre1_apellido2-nombre2.zip` (o `.rar`).

El archivo subido al campus debe contener el **proyecto completo** (sin borrar archivos requeridos por la consigna).

---

## 7. Relación con otros documentos del repo

| Documento | Rol |
|-----------|-----|
| `docs/Programacion-II-Primer-Parcial.md` | Texto oficial de consigna, criterios de evaluación y modalidad. |
| Material de referencia tipo `proyecto-profe/` | Patrones de **lista blanca**, **vistas**, **clase + JSON**, **constante de archivo**, **detalle por `id`**. |

---

## 8. Criterios que también evalúan la nota (resumen consigna)

Además del cumplimiento mínimo del sitio y de PHP/JSON:

- Complejidad razonable de la tarea.  
- Prolijidad del código y de la carpeta del proyecto.  
- Uso adecuado de funciones o métodos y de constantes.  
- Uso del filesystem (lectura del JSON).  
- HTML semántico y estilización del sitio.  

---

## 9. Consejo final

Priorizar **claridad** y **coherencia con lo visto en clase**: nombres explícitos, lista blanca de secciones, una clase modelo clara, un JSON bien formado y vistas que solo muestren lo que ya fue resuelto en PHP arriba del HTML. Es preferible un código que se pueda explicar línea por línea en la defensa que abstracciones que el equipo no domine.
