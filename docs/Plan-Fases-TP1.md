# Plan por fases - TP1 Programacion II (E-commerce PHP)

## Objetivo final
Entregar un sitio e-commerce en PHP que cumpla al 100% la consigna del primer parcial:
- 4 secciones funcionales: home, listado, detalle y contacto.
- Carga dinamica por GET desde un template base.
- Uso de include/require para evitar duplicacion de HTML.
- Items modelados con clase PHP.
- Datos de items leidos desde JSON y reutilizados en listado + detalle.
- HTML5 semantico, CSS personalizado y estructura prolija de carpetas.
- Archivo datos.txt completo para la entrega.

## Regla de enfoque academico (obligatoria)
Este TP debe priorizar simplicidad academica:
- Codigo simple, legible y explicable por un alumno de segundo ano.
- Evitar soluciones rebuscadas, patrones avanzados innecesarios o sobreingenieria.
- Mantener nombres claros y estructura directa.
- No incluir comentarios dentro del codigo (PHP, HTML, CSS o JS). El codigo debe ser autoexplicativo por su claridad.

---

## Fase 0 - Diagnostico y estructura base
### Objetivo
Pasar del estado actual (HTML/CSS estatico) a una base preparada para PHP.

### Tareas
1. Crear estructura de carpetas recomendada:
   - vistas/
   - includes/
   - data/
   - clases/
   - imgs/ (si corresponde)
2. Renombrar o migrar la logica de index.html a index.php.
3. Separar cabecera y pie en includes reutilizables.
4. Definir convencion de rutas relativas y uso de __DIR__ en accesos por filesystem.

### Entregables
- index.php inicial funcionando.
- includes/header.php e includes/footer.php creados.
- Estructura de carpetas ordenada.

### Criterio de cierre
El sitio ya carga desde PHP y no depende de HTML estatico como entrypoint principal.

---

## Fase 1 - Enrutado por seccion (GET)
### Objetivo
Implementar template base con carga dinamica de vistas por parametro seccion.

### Tareas
1. En index.php leer seccion desde GET con valor por defecto home.
2. Crear mapa/whitelist de secciones permitidas.
3. Incluir la vista correspondiente con include o require.
4. Manejar secciones invalidas con fallback a home o vista 404 simple.

### Entregables
- vistas/home.php
- vistas/listado.php
- vistas/detalle.php
- vistas/contacto.php
- Navegacion apuntando a index.php?seccion=...

### Criterio de cierre
Las 4 secciones abren por URL GET sin romper, y no hay duplicacion de estructura general.

---

## Fase 2 - Modelo de dominio y carga de datos JSON
### Objetivo
Cumplir el requisito indispensable: items desde JSON convertidos a objetos de clase.

### Tareas
1. Definir clase Producto (o Item) en clases/Producto.php.
2. Crear data/productos.json con datos reales de catalogo.
3. Implementar funcion de carga:
   - leer archivo JSON
   - decodificar con json_decode
   - crear array de instancias Producto
4. Centralizar esa carga en una funcion reutilizable (por ejemplo en includes/helpers.php).

### Entregables
- clases/Producto.php
- data/productos.json
- Funcion reusable para obtener array de productos.

### Criterio de cierre
Existe un unico origen de datos (JSON) y el proyecto trabaja con objetos PHP, no arrays hardcodeados en vistas.

---

## Fase 3 - Seccion Listado
### Objetivo
Mostrar catalogo desde el array de objetos, con acceso al detalle de cada item.

### Tareas
1. En vistas/listado.php recorrer productos con foreach.
2. Mostrar campos clave (nombre, precio, categoria, imagen, breve descripcion).
3. Agregar enlace por item a detalle usando id por GET.
4. Cuidar semantica HTML (section/article, titulos, etc).

### Entregables
- Listado funcional con datos reales del JSON.
- Links al detalle por cada item.

### Criterio de cierre
El listado no tiene datos quemados y cada producto permite navegar a su detalle.

---

## Fase 4 - Seccion Detalle
### Objetivo
Mostrar informacion expandida de un item puntual usando el mismo dataset JSON.

### Tareas
1. Leer id por GET en vistas/detalle.php.
2. Buscar producto en el array de objetos cargados.
3. Renderizar detalle completo del producto.
4. Manejar id inexistente con mensaje claro para usuario.

### Entregables
- Detalle dinamico por id.
- Manejo de errores basico para item no encontrado.

### Criterio de cierre
El detalle se alimenta del mismo origen de datos que listado y responde al id de la URL.

---

## Fase 5 - Seccion Contacto (integrada al sitio)
### Objetivo
Incorporar formulario de contacto util para la tematica del e-commerce.

### Tareas
1. Diseñar formulario con campos utiles (nombre, email, asunto, mensaje).
2. Asociar cada input con su label (accesibilidad).
3. Integrar visualmente con la identidad del sitio.
4. (Opcional valor agregado) validar campos y/o guardar consultas en archivo local.

### Entregables
- vistas/contacto.php funcional a nivel UI/UX.
- Form integrado y coherente con la pagina.

### Criterio de cierre
La seccion contacto tiene uso claro para el visitante y cumple la estructura semantica.

---

## Fase 6 - Calidad, prolijidad y cierre de entrega
### Objetivo
Asegurar cumplimiento academico y preparar paquete final.

### Tareas
1. Revisar consistencia de nombres (variables, funciones, archivos).
2. Extraer logica repetida a funciones propias.
3. Verificar rutas relativas y __DIR__ en lecturas de archivo.
4. Revisar semantica HTML5 y responsive CSS.
5. Revisar que la implementacion mantenga simplicidad academica (facil de explicar en clase).
6. Verificar que no haya comentarios en el codigo fuente.
7. Crear datos.txt obligatorio con toda la informacion pedida.
8. Chequeo final de navegacion completa (home, listado, detalle, contacto).
9. Preparar zip/rar con formato de nombre correcto.

### Entregables
- Proyecto final listo para corregir.
- datos.txt completo.
- Archivo comprimido segun formato de catedra.

### Criterio de cierre
Checklist de consigna completado al 100%, sin puntos criticos faltantes.

---

## Checklist rapido de aprobacion
- [ ] index.php con carga dinamica por GET.
- [ ] include/require usados correctamente.
- [ ] Clase Producto implementada.
- [ ] Productos cargados desde JSON.
- [ ] Listado y detalle reutilizan la misma fuente de datos.
- [ ] Contacto integrado al contexto del sitio.
- [ ] HTML semantico + CSS personalizado.
- [ ] Rutas portables (sin rutas absolutas locales).
- [ ] Codigo simple y explicable (sin sobreingenieria).
- [ ] Sin comentarios en el codigo fuente.
- [ ] datos.txt presente y completo.
- [ ] Proyecto comprimido con nombre correcto.

---

## Orden recomendado de ejecucion
1. Fase 0
2. Fase 1
3. Fase 2
4. Fase 3
5. Fase 4
6. Fase 5
7. Fase 6

No conviene saltar de fase: listado y detalle dependen de tener bien resuelto modelo + JSON.
