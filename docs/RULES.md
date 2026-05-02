# Documento de Estándares: Desarrollo E-commerce PHP

**Materia:** Programación II  
**Docente:** Santiago Gallino

Este documento resume la "verdad absoluta" para el desarrollo del primer parcial, priorizando la simplicidad, legibilidad y las herramientas básicas vistas en clase. El objetivo es un código funcional que un alumno de segundo año pueda explicar y mantener con facilidad.

---

## 1. Estructura y Semántica (HTML5 y CSS3)

El sitio debe ser visualmente profesional y coherente con la temática elegida, evitando el "HTML pelado".

### Buenas Prácticas en HTML

- Uso de Etiquetas Semánticas: Utiliza etiquetas que describan el contenido en lugar de abusar de <div>. Implementa <header>, <nav>, <main>, <section>, <article> y <footer> según corresponda.
- Formularios Accesibles: Todo elemento <input> debe estar vinculado a un <label> mediante los atributos id y for para garantizar claridad y accesibilidad.
- Jerarquía de Títulos: Mantén un orden lógico con <h1>, <h2>, etc., para estructurar la información de las secciones.

### Buenas Prácticas en CSS

- Estilización Personalizada: Si bien se permite el uso de frameworks como Bootstrap para la grilla y componentes básicos, es obligatorio incluir estilos propios en un archivo estilos.css para dar identidad al sitio.
- Diseño Adaptable: Asegúrate de que las imágenes no desborden sus contenedores utilizando max-width: 100%.
- Organización Visual: Utiliza clases de utilidad para márgenes y rellenos (paddings) para que la interfaz sea limpia y legible.

### Código sin comentarios

En los archivos de código del proyecto entregado (PHP, HTML, CSS y JavaScript, si aplica) no deben incluirse comentarios del lenguaje: ningún comentario de bloque o de línea en PHP o JavaScript, ningún comentario en hojas CSS, ni comentarios en marcado HTML. El código debe leerse por sí mismo con nombres de variables y clases claros; las explicaciones van en la documentación de entrega, datos.txt o la defensa oral.

---

## 2. Lógica y Arquitectura (PHP)

La consigna exige un código dinámico pero con una sintaxis simple, evitando métodos complejos o funciones avanzadas no abordadas en la cursada.

### Estructura Dinámica

- Template Base: El proyecto debe centralizarse en un archivo index.php. Las secciones (Home, Listado, Detalle, Contacto) se cargan dinámicamente mediante parámetros por GET (ej. index.php?seccion=contacto).
- Inclusión de Archivos: Utiliza include o require (preferentemente include_once o require_once) para cargar el encabezado, pie de página y las diferentes vistas, evitando duplicar código HTML.

### Manejo de Datos y Objetos

- Representación por Clases: Cada ítem o producto debe ser una instancia de una Clase. La clase debe definir las propiedades del objeto (nombre, precio, imagen, etc.) de forma sencilla.
- Uso de JSON: La información de los productos no debe estar escrita directamente en el PHP. Debe leerse desde un archivo .json externo, convirtiéndose en un array de objetos mediante json_decode().
- Iteración Simple: Para mostrar los productos en el listado, utiliza el bucle foreach, que es la forma más clara de recorrer arrays asociativos y de objetos en PHP.

### Rutas y Portabilidad (Regla de Oro)

- Rutas Relativas o Dinámicas: Queda estrictamente prohibido el uso de rutas locales absolutas (ej: C:\Users\...). El proyecto debe funcionar en cualquier servidor.
- Uso de __DIR__: Para asegurar la portabilidad, utiliza la constante mágica __DIR__ para calcular rutas de manera dinámica.

---

## 3. Requisitos de Entrega y Organización

La prolijidad en la organización de los archivos es un factor clave en la evaluación.

### Estructura de Carpetas Recomendada

- /css: Para las hojas de estilo.
- /vistas: Para los archivos PHP de cada sección (home.php, listado.php, etc.).
- /imgs: Para las imágenes de productos y logos.
- /data: Para el archivo JSON de productos.

### El archivo datos.txt (Obligatorio)

Debe estar en la raíz del proyecto y contener los siguientes datos para evitar descuentos de puntos:

- Carrera y Materia.
- Cuatrimestre y Año.
- Turno y Comisión.
- Apellido y Nombre del alumno/s.
- Nombre del docente (Santiago Gallino).
- Carácter de la entrega (1er Parcial).

### Formato del Comprimido

El archivo de entrega debe ser un .zip o .rar nombrado según la cantidad de integrantes:

- 1 integrante: apellido-nombre.zip.
- 2 integrantes: apellido1-nombre1_apellido2-nombre2.zip.

---

## Consejo Final para el Estudiante

Simplicidad ante todo. Es mejor un código básico, con nombres de variables coherentes y sintaxis fácil de leer, que funcione correctamente y sea comprendido en su totalidad por el alumno, que un código complejo que no pueda explicar.