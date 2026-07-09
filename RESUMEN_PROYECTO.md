# RESUMEN TÉCNICO DEL PROYECTO — Galmir (PHP E-Commerce)

**Materia:** Programación II — 2.º Parcial  
**Alumnos:** Milagros Sol Kuringhian / Gael García Núñez  
**Comisión:** DWN3AP — Turno Noche — 3.er Cuatrimestre 2026  
**Docente:** Santiago Gallino  
**Base de datos:** `dw3_kuringhian_garcia`  
**Entorno previsto:** XAMPP o MAMP (PHP + MySQL)

> **Nota sobre alcance:** La aplicación desplegable vive en la carpeta `sitio/`. El resto del repositorio contiene artefactos de soporte (`db/`, `der/`, `docs/`, `datos.txt`). La consigna del **Final** (`docs/Programación II - Final.pdf`) queda **pendiente de documentar** en este archivo.

---

## 1. Objetivo del proyecto

### Qué hace el sistema

**Galmir** es una tienda online de juegos de mesa con dos partes:

1. **Sitio público** (`sitio/`): catálogo navegable donde cualquier visitante puede ver el inicio, el listado de productos, el detalle de cada juego y un formulario de contacto.
2. **Panel de administración** (`sitio/admin/`): área restringida donde un usuario autenticado puede gestionar el catálogo (listar, crear, editar y eliminar productos).

Los datos de productos, categorías y usuarios se almacenan en **MySQL** mediante **PDO**.

### Qué problema resuelve

- Centraliza la información del catálogo en una base de datos relacional en lugar de un JSON estático.
- Permite al administrador mantener productos sin tocar código ni archivos de datos.
- Ofrece una vitrina pública con navegación por secciones y presentación visual profesional.
- Modela la relación muchos-a-muchos entre productos y categorías (aunque la interfaz admin actual solo permite asignar **una** categoría por producto).

### Flujo principal de uso

**Visitante público:**
1. Entra a `sitio/index.php` → ve el home.
2. Navega al listado → consulta productos desde MySQL.
3. Abre el detalle de un producto por su `id`.
4. Opcionalmente completa el formulario de contacto (simulado en el cliente).

**Administrador:**
1. Entra a `sitio/admin/index.php?seccion=ingresar`.
2. Inicia sesión con email y contraseña.
3. Accede al panel de productos.
4. Puede dar de alta, editar o eliminar productos.
5. Cierra sesión con `?seccion=salir`.

---

## 2. Estructura del proyecto

```
PHP-E-Commerce/
├── sitio/                    ← Aplicación web (document root en el servidor)
│   ├── index.php             ← Front controller del sitio público
│   ├── admin/
│   │   ├── index.php         ← Front controller del panel admin
│   │   ├── css/              ← Estilos exclusivos del admin
│   │   └── vistas/           ← Pantallas del admin
│   ├── clases/               ← Modelo / capa de datos (PDO)
│   ├── includes/             ← Layout compartido (header/footer público)
│   ├── vistas/               ← Vistas del sitio público
│   ├── css/                  ← Estilos del sitio público
│   ├── imgs/                 ← Imágenes de productos y UI
│   └── data/
│       └── productos.json    ← Backup legado (ya no lo consume el código)
├── db/
│   └── dw3_kuringhian_garcia.sql  ← Script de creación y datos iniciales
├── der/
│   └── dw3_kuringhian_garcia.png  ← Diagrama entidad-relación
├── docs/
│   ├── Programación II - Primer Parcial.pdf
│   ├── Programación II - Segundo Parcial.pdf
│   └── Programación II - Final.pdf   ← PENDIENTE de documentar
└── datos.txt                 ← Metadatos de la entrega y credenciales de prueba
```

### Carpeta `sitio/` — aplicación principal

| Archivo / carpeta | Función | Por qué existe |
|---|---|---|
| `index.php` | Punto de entrada único del sitio público. Lee `?seccion=` y carga la vista correspondiente. | Evita multiplicar archivos PHP en la raíz; centraliza el enrutamiento. |
| `admin/index.php` | Punto de entrada del panel. Gestiona sesión, autenticación y enrutamiento admin. | Separa la lógica de seguridad del sitio público. |
| `clases/DBConexion.php` | Configura y devuelve una conexión PDO a MySQL. | Encapsula credenciales y DSN en un solo lugar. |
| `clases/Producto.php` | Modelo de productos: consultas y CRUD. | Concentra toda la lógica SQL de productos. |
| `clases/Usuario.php` | Modelo de usuarios y gestión de sesión. | Separa autenticación del resto del sistema. |
| `includes/header.php` | Apertura HTML, `<head>`, navegación y apertura de `<main>`. | Reutiliza el layout en todas las secciones públicas. |
| `includes/footer.php` | Cierre de `<main>`, pie de página y cierre HTML. | Complementa el header para un layout consistente. |
| `vistas/*.php` | Contenido de cada sección pública. | Cada pantalla es un archivo independiente, fácil de mantener. |
| `admin/vistas/*.php` | Pantallas del panel (login, listado, alta, edición, borrado). | El admin tiene layout propio (no usa `includes/` público). |
| `css/` | Hojas de estilo modulares importadas por `estilos.css`. | Organiza estilos por sección sin un único archivo gigante. |
| `admin/css/` | Estilos del login y del CRUD admin. | El admin tiene identidad visual distinta. |
| `imgs/` | Imágenes de productos, banners, iconos SVG/PNG/WebP. | Recursos estáticos referenciados por rutas relativas. |
| `data/productos.json` | Copia estática de los 6 productos iniciales. | Respaldo de la migración JSON → MySQL; **no se lee en runtime**. |

### Carpeta `db/`

Contiene el volcado SQL completo: creación de tablas, datos semilla, índices y claves foráneas. Es la fuente de verdad del esquema relacional.

### Carpeta `der/`

Imagen del diagrama entidad-relación (`dw3_kuringhian_garcia.png`) para documentación académica y defensa del diseño de base de datos.


---

## 3. Arquitectura general

### Organización

El proyecto sigue una arquitectura **monolítica PHP clásica** con separación en capas inspirada en **MVC simplificado**:

| Capa | Ubicación | Rol |
|---|---|---|
| **Controlador / router** | `index.php`, `admin/index.php` | Recibe la petición HTTP, valida la sección, aplica reglas de acceso y delega a la vista. |
| **Modelo** | `clases/*.php` | Acceso a datos con PDO, reglas de negocio mínimas. |
| **Vista** | `vistas/`, `admin/vistas/`, `includes/` | HTML de presentación; en algunos casos mezcla lógica de formulario (patrón típico de proyectos académicos). |

No hay framework (Laravel, Symfony, etc.). Es PHP procedural-orientado-a-objetos con clases de modelo.

### Patrón de enrutamiento: Front Controller

Ambos `index.php` implementan el mismo patrón:

1. Definen un array de secciones permitidas (lista blanca).
2. Leen `$_GET['seccion']`.
3. Si la sección no está permitida → redirigen a `404`.
4. Incluyen el archivo de vista correspondiente.

Esto evita exponer rutas arbitrarias del filesystem y mantiene un único punto de entrada por subsistema.

### Separación de responsabilidades

- **DBConexion:** solo conecta.
- **Producto / Usuario:** solo hablan con la base y encapsulan reglas (validación de categoría, verificación de credenciales).
- **Vistas públicas:** muestran datos; `listado.php` y `detalle.php` instancian `Producto`.
- **Vistas admin:** además procesan formularios POST (login, alta, edición, borrado) en el mismo archivo — patrón "fat view" aceptable en el alcance del parcial.
- **CSS:** separado por módulo visual, sin lógica.

## 4. Flujo completo del sistema

### 4.1. Sitio público — cualquier visita

```
Navegador → sitio/index.php
```

**Paso a paso:**

1. **Recepción:** `sitio/index.php` recibe la petición GET.
2. **Enrutamiento:** Lee `$_GET['seccion']` (default: `home`). Comprueba que esté en `$seccionesPermitidas` (`home`, `listado`, `detalle`, `contacto`). Si no → `$seccionActual = '404'`.
3. **Layout:** `include_once includes/header.php` — genera `<!DOCTYPE html>`, navegación y abre `<main>`. La variable `$seccionActual` marca el enlace activo.
4. **Vista:** `require vistas/{seccion}.php` — contenido específico.
5. **Cierre:** `include_once includes/footer.php` — cierra `</main>`, footer y `</html>`.

**Datos que circulan:**
- Entrada: `$_GET['seccion']`, y en detalle también `$_GET['id']`.
- Salida: HTML completo al navegador.

---

### 4.2. Home (`?seccion=home`)

```
index.php → header.php → vistas/home.php → footer.php
```

- **Sin PHP de negocio:** `home.php` es HTML estático con enlaces hardcodeados a productos por id (`detalle&id=1`, `id=3`, `id=6`).
- **No consulta la base de datos.**
- Muestra hero, categorías destacadas, beneficios y FAQ con `<details>`.

---

### 4.3. Listado (`?seccion=listado`)

```
index.php → header.php → vistas/listado.php → Producto::todas() → footer.php
```

1. `listado.php` hace `require_once clases/Producto.php`.
2. Instancia `Producto` y llama `todas()`.
3. `todas()` abre PDO, ejecuta JOIN con categorías, devuelve array de objetos `Producto`.
4. La vista itera con `foreach` y renderiza tarjetas con `htmlspecialchars()` en cada campo.
5. Cada tarjeta enlaza a `index.php?seccion=detalle&id={producto_id}`.

**SQL ejecutado (resumido):** SELECT de `productos` con LEFT JOIN a `productos_tienen_categorias` y `categorias`, GROUP_CONCAT de nombres de categoría, ORDER BY `fecha_alta DESC`.

---

### 4.4. Detalle (`?seccion=detalle&id=N`)

```
index.php → header.php → vistas/detalle.php → Producto::porId(N) → footer.php
```

1. Lee `$idProducto = (int) ($_GET['id'] ?? 0)`.
2. Llama `(new Producto)->porId($idProducto)`.
3. Si devuelve `null` → muestra "Producto no encontrado".
4. Si existe → muestra imagen, nombre, precio, categoría, descripción completa.
5. Botones "Comprar ahora", favoritos y carrito apuntan a `#` — **son decorativos**, no implementan lógica.

---

### 4.5. Contacto (`?seccion=contacto`)

```
index.php → header.php → vistas/contacto.php (+ JS inline) → footer.php
```

1. Renderiza formulario con `action="#"` y `method="post"`.
2. El botón "Enviar" es `type="button"` (no envía el formulario al servidor).
3. JavaScript valida con `form.reportValidity()` y muestra `alert()` de éxito.
4. **No hay procesamiento PHP ni persistencia.**

---

### 4.6. 404 pública (`?seccion=invalida`)

```
index.php → header.php → vistas/404.php → footer.php
```

Mensaje de sección inexistente con enlace al home.

---

### 4.7. Panel admin — flujo general

```
Navegador → sitio/admin/index.php
```

1. `session_start()` — inicia o reanuda la sesión PHP.
2. `require_once clases/Usuario.php`.
3. Lee `$_GET['seccion']` (default: `ingresar`).
4. **Caso `salir`:** `Usuario::cerrarSesion()` → redirect a `ingresar`.
5. **Si está en `ingresar` y ya logueado:** redirect a `productos`.
6. **Si NO está en `ingresar` y NO logueado:** redirect a `ingresar`.
7. Valida sección contra lista blanca admin.
8. `require admin/vistas/{seccion}.php` — **sin** header/footer compartido (cada vista admin es documento HTML completo, salvo `producto-borrar` y `404` que son fragmentos mínimos).

---

### 4.8. Login admin (`?seccion=ingresar`)

```
admin/index.php → admin/vistas/ingresar.php
```

**GET:** Muestra formulario de login.

**POST:**
1. Lee `$_POST['email']` y `$_POST['password']`.
2. `(new Usuario)->verificarCredenciales($email, $password)`.
3. Internamente: `porEmail()` → SELECT en `usuarios` → `password_verify()`.
4. Si OK: `Usuario::iniciarSesion($usuario)` guarda `usuario_id` y `usuario_email` en `$_SESSION`.
5. `header('Location: ?seccion=productos')` + `exit`.
6. Si falla: mensaje "Email o contraseña incorrectos" y re-render del formulario.

---

### 4.9. Listado admin (`?seccion=productos`)

```
admin/index.php (auth OK) → admin/vistas/productos.php → Producto::todas()
```

- Tabla con miniatura, nombre, categoría, precio, acciones editar/borrar.
- Email del admin desde `$_SESSION['usuario_email']`.
- Diálogo `<dialog>` para confirmar borrado; JS configura `form.action` hacia `producto-borrar&id=X`.

---

### 4.10. Alta de producto (`?seccion=producto-alta`)

```
admin/index.php → admin/vistas/producto-alta.php
```

**GET:**
1. `Producto::todasCategorias()` para poblar el `<select>`.
2. Muestra formulario vacío.

**POST:**
1. Recoge y sanitiza campos con `trim()`.
2. Convierte precio: `str_replace(',', '.')` → `(float)`.
3. Valida que ningún campo obligatorio esté vacío y precio > 0.
4. `Producto::crear(...)` con `Usuario::idEnSesion()` como `usuario_fk`.
5. Redirect a `productos` si éxito; mensaje de error si falla.

---

### 4.11. Edición (`?seccion=producto-editar&id=N`)

```
admin/index.php → admin/vistas/producto-editar.php
```

**GET con id válido:**
1. `porId($id)` — si no existe, redirect a productos.
2. `categoriasPorProducto($id)` — toma la primera categoría para el select.
3. Pre-llena el formulario.

**POST:**
1. Lee `producto_id` del hidden field.
2. Valida igual que alta.
3. `Producto::actualizar(...)` — actualiza producto y reemplaza categoría en tabla intermedia.
4. Redirect a productos.

**Nota:** La imagen se envía como campo oculto; **no se puede cambiar la imagen desde la edición** en la UI actual.

---

### 4.12. Borrado (`?seccion=producto-borrar&id=N`)

```
admin/vistas/productos.php (dialog POST) → admin/vistas/producto-borrar.php
```

**POST (flujo real):**
1. Lee `producto_id` de POST o GET.
2. `Producto::eliminar($id)` → DELETE en `productos`.
3. CASCADE elimina filas en `productos_tienen_categorias`.
4. Redirect a productos.

**GET (flujo alternativo / residual):**
- Muestra página mínima "Borrar producto #N" — prácticamente no se usa porque el borrado real ocurre por POST desde el diálogo.

---

## 5. Relaciones entre archivos

### Sitio público

```
sitio/index.php
    ├── include_once → includes/header.php
    │       └── usa variable $seccionActual (definida en index.php)
    ├── require → vistas/{seccion}.php
    │       ├── listado.php → require_once → clases/Producto.php
    │       │       └── Producto.php → require_once → clases/DBConexion.php
    │       ├── detalle.php → require_once → clases/Producto.php → DBConexion.php
    │       ├── home.php (sin dependencias PHP)
    │       ├── contacto.php (sin dependencias PHP, solo JS)
    │       └── 404.php (sin dependencias)
    └── include_once → includes/footer.php
```

**Justificación de cada enlace:**
- `index.php` necesita `header`/`footer` para layout uniforme sin repetir HTML.
- `listado` y `detalle` necesitan `Producto` porque muestran datos dinámicos.
- `Producto` necesita `DBConexion` para no repetir credenciales en cada consulta.
- `home` y `contacto` son autónomos porque no consumen BD.

### Panel admin

```
sitio/admin/index.php
    ├── session_start()
    ├── require_once → clases/Usuario.php → DBConexion.php
    └── require → admin/vistas/{seccion}.php
            ├── ingresar.php → Usuario (verificarCredenciales, iniciarSesion)
            ├── productos.php → Producto.php → DBConexion.php
            ├── producto-alta.php → Producto.php + Usuario::idEnSesion()
            ├── producto-editar.php → Producto.php
            ├── producto-borrar.php → Producto.php
            └── 404.php → Usuario::estaLogueado() (solo para el enlace "Volver")
```

**Justificación:**
- `admin/index.php` concentra **toda** la lógica de autorización antes de cargar cualquier vista protegida.
- Las vistas admin cargan `Producto` solo cuando manipulan catálogo.
- `ingresar.php` no usa el layout público porque es una pantalla de login independiente.

### Recursos estáticos

```
includes/header.php → css/estilos.css → @import base.css, home.css, contacto.css, listado.css, detalle.css
admin/vistas/*.php → admin/css/*.css (directo, sin import chain)
vistas/*.php + admin → imgs/* (rutas relativas desde su contexto)
```

---

## 6. Base de datos

### Motor y configuración

- **Motor:** MySQL / MariaDB, charset `utf8mb4_unicode_ci`.
- **Nombre:** `dw3_kuringhian_garcia`.
- **Conexión en código:** host `127.0.0.1:8889` (puerto típico de **MAMP**), usuario `root`, contraseña `root`.
- **En XAMPP** normalmente el host sería `127.0.0.1` o `localhost` sin puerto `:8889`.

### Diagrama relacional (resumen)

```
usuarios (1) ──────< (N) productos
                           │
                           │ (N)
                           ▼
              productos_tienen_categorias
                           │ (N)
                           ▼
                      categorias
```

### Tabla `usuarios`

| Campo | Tipo | Descripción |
|---|---|---|
| `usuario_id` | INT UNSIGNED PK AI | Identificador único |
| `email` | VARCHAR(255) UNIQUE | Login del administrador |
| `password` | VARCHAR(255) | Hash bcrypt de la contraseña |
| `nombre` | VARCHAR(100) NULL | Nombre (informativo) |
| `apellido` | VARCHAR(100) NULL | Apellido (informativo) |

**Diseño:** Solo hay un usuario admin sembrado. La contraseña nunca se guarda en texto plano; se usa `password_hash` / `password_verify` (hash `$2y$10$...` en el SQL).

### Tabla `categorias`

| Campo | Tipo | Descripción |
|---|---|---|
| `categoria_id` | INT UNSIGNED PK AI | ID |
| `nombre` | VARCHAR(100) UNIQUE | Nombre de categoría |

**Datos iniciales:** Estrategia, Clásico, Rompecabezas, Cartas, Misterio.

**Diseño:** Tabla de lookup normalizada. El UNIQUE en `nombre` evita duplicados.

### Tabla `productos`

| Campo | Tipo | Descripción |
|---|---|---|
| `producto_id` | INT UNSIGNED PK AI | ID |
| `nombre` | VARCHAR(255) | Nombre del juego |
| `precio` | DECIMAL(10,2) | Precio en pesos |
| `descripcion_corta` | VARCHAR(500) | Texto para tarjetas/listado |
| `descripcion` | TEXT | Descripción completa |
| `imagen` | VARCHAR(255) | Ruta relativa (`imgs/teg.webp`) |
| `usuario_fk` | INT UNSIGNED FK | Admin que dio de alta el producto |
| `fecha_alta` | DATETIME DEFAULT NOW | Ordenamiento en listados |

**Índices:** `idx_fecha_alta` (DESC) para listar los más recientes primero; FK a `usuarios`.

**Diseño:** Separa descripción corta/larga para distintas vistas. La imagen es ruta de texto, no BLOB — más simple para un proyecto académico.

### Tabla `productos_tienen_categorias`

| Campo | Tipo | Descripción |
|---|---|---|
| `producto_fk` | INT UNSIGNED PK (compuesta) | FK a productos |
| `categoria_fk` | INT UNSIGNED PK (compuesta) | FK a categorias |

**Claves foráneas con ON DELETE CASCADE:** al borrar un producto o categoría, se eliminan las filas de la tabla intermedia automáticamente.

**Diseño:** Modela relación **muchos-a-muchos** correctamente en BD, aunque la aplicación solo gestiona una categoría por producto en los formularios.

### Consultas SQL importantes del sistema

| Operación | Tablas involucradas | Método PHP |
|---|---|---|
| Listar productos con categoría | productos + ptc + categorias | `Producto::todas()` |
| Producto por ID | productos + ptc + categorias | `Producto::porId()` |
| Listar categorías | categorias | `Producto::todasCategorias()` |
| Categorías de un producto | productos_tienen_categorias | `Producto::categoriasPorProducto()` |
| Login | usuarios | `Usuario::porEmail()` |
| Crear producto | productos + ptc | `Producto::crear()` |
| Actualizar producto | productos + DELETE/INSERT ptc | `Producto::actualizar()` |
| Eliminar producto | productos (CASCADE ptc) | `Producto::eliminar()` |

---

## 7. Explicación de cada módulo

### 7.1. Módulo de enrutamiento público

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Despachar cada URL a la vista correcta de forma segura. |
| **Archivos** | `sitio/index.php` |
| **Funciones** | No hay funciones nombradas; lógica inline con `in_array` y `require`. |
| **Datos** | `$_GET['seccion']` → `$seccionActual` |
| **Interacción** | Llama a `includes/` y `vistas/`; no toca BD directamente. |

### 7.2. Módulo de layout público

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Encabezado, navegación, pie y estilos comunes. |
| **Archivos** | `includes/header.php`, `includes/footer.php`, `css/*` |
| **Datos** | `$seccionActual` para marcar nav activa. |
| **Interacción** | Envuelve todas las vistas públicas; enlaza al admin. |

### 7.3. Módulo Home

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Landing de marketing: hero, categorías, beneficios, FAQ. |
| **Archivos** | `vistas/home.php`, `css/home.css` |
| **Datos** | Ninguno dinámico; enlaces estáticos a productos por id. |
| **Interacción** | Punto de entrada visual; deriva tráfico a listado y detalle. |

### 7.4. Módulo Catálogo (listado + detalle)

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Mostrar productos desde MySQL. |
| **Archivos** | `vistas/listado.php`, `vistas/detalle.php`, `clases/Producto.php`, `css/listado.css`, `css/detalle.css` |
| **Funciones** | `Producto::todas()`, `Producto::porId()`, getters |
| **Datos** | Objetos `Producto` con id, nombre, precio, categorías concatenadas, descripciones, imagen. |
| **Interacción** | Depende de `DBConexion`; alimentado por CRUD admin. |

### 7.5. Módulo Contacto

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Formulario de contacto con validación UX. |
| **Archivos** | `vistas/contacto.php`, `css/contacto.css` |
| **Datos** | Campos: nombre, email, asunto, motivo, mensaje — solo en cliente. |
| **Interacción** | Aislado; no persiste ni envía email. |

### 7.6. Módulo de autenticación admin

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Proteger el panel con sesión PHP. |
| **Archivos** | `admin/index.php`, `admin/vistas/ingresar.php`, `clases/Usuario.php` |
| **Funciones** | `verificarCredenciales`, `iniciarSesion`, `cerrarSesion`, `estaLogueado`, `idEnSesion` |
| **Datos** | `$_SESSION['usuario_id']`, `$_SESSION['usuario_email']` |
| **Interacción** | Bloquea acceso a vistas CRUD; provee `usuario_fk` al crear productos. |

### 7.7. Módulo CRUD de productos (admin)

| Aspecto | Detalle |
|---|---|
| **Objetivo** | ABM completo del catálogo. |
| **Archivos** | `productos.php`, `producto-alta.php`, `producto-editar.php`, `producto-borrar.php`, `clases/Producto.php` |
| **Funciones** | `todas`, `todasCategorias`, `categoriasPorProducto`, `crear`, `actualizar`, `eliminar` |
| **Datos** | Formularios POST ↔ tablas `productos`, `productos_tienen_categorias`, `categorias` |
| **Interacción** | Cambios se reflejan inmediatamente en el sitio público. |

### 7.8. Módulo de conexión a datos

| Aspecto | Detalle |
|---|---|
| **Objetivo** | Abstracción mínima de PDO. |
| **Archivos** | `clases/DBConexion.php` |
| **Funciones** | `getConexion(): PDO` |
| **Datos** | Constantes de host, user, pass, dbname. |
| **Interacción** | Usado por `Producto` y `Usuario` en cada operación (nueva instancia cada vez). |

---

## 8. Explicación de todas las funciones importantes

### Clase `DBConexion`

#### `getConexion(): PDO`
- **Para qué sirve:** Crea y devuelve una instancia PDO conectada a MySQL.
- **Parámetros:** Ninguno (usa constantes de clase).
- **Retorno:** Objeto `PDO`.
- **Cuándo se ejecuta:** Cada vez que `Producto` o `Usuario` necesitan acceder a la BD.
- **Quién la llama:** Métodos de `Producto` y `Usuario`.
- **Por qué así:** Centraliza el DSN; en un proyecto más grande migraría a un singleton o inyección de dependencias.

---

### Clase `Producto`

#### `todas(): array`
- **Para qué sirve:** Obtiene todos los productos con sus categorías agregadas en un string.
- **Parámetros:** Ninguno.
- **Retorno:** Array de objetos `Producto` (PDO::FETCH_CLASS).
- **Cuándo:** Al cargar listado público o tabla admin.
- **Quién la llama:** `vistas/listado.php`, `admin/vistas/productos.php`.
- **Por qué GROUP_CONCAT:** Permite mostrar categorías en una sola columna sin segunda consulta por producto.

#### `porId(int $id): ?self`
- **Para qué sirve:** Busca un producto por ID o devuelve null.
- **Parámetros:** `$id` entero del producto.
- **Retorno:** Instancia `Producto` o `null`.
- **Cuándo:** Detalle público, edición, borrado, validaciones internas de actualizar/eliminar.
- **Quién la llama:** `detalle.php`, `producto-editar.php`, `producto-borrar.php`, métodos `actualizar` y `eliminar`.
- **Por qué null y no excepción:** Permite a la vista decidir qué mensaje mostrar sin try/catch.

#### `todasCategorias(): array`
- **Para qué sirve:** Lista id y nombre de todas las categorías para `<select>`.
- **Retorno:** Array asociativo `PDO::FETCH_ASSOC`.
- **Quién la llama:** Formularios de alta y edición.

#### `categoriasPorProducto(int $productoId): array`
- **Para qué sirve:** Devuelve array de IDs de categoría de un producto.
- **Retorno:** Array de enteros (FETCH_COLUMN).
- **Quién la llama:** `producto-editar.php` para preseleccionar categoría.
- **Por qué array si solo hay una:** La BD permite varias; el código está preparado para N aunque la UI use una.

#### `crear(...): int`
- **Para qué sirve:** Inserta producto y su categoría; devuelve el nuevo ID.
- **Parámetros:** nombre, precio, descripcionCorta, descripcion, imagen, usuarioId, categoriaId.
- **Retorno:** `producto_id` insertado.
- **Cuándo:** POST de alta admin.
- **Validación:** Lanza `InvalidArgumentException` si `categoriaId <= 0`.
- **Por qué dos INSERT:** Primero el producto, luego la fila en tabla intermedia — transacción manual implícita (sin `beginTransaction`, mejora posible).

#### `actualizar(...): bool`
- **Para qué sirve:** Modifica producto y reemplaza categoría.
- **Retorno:** `true` si existía; `false` si el id no existe.
- **Estrategia categoría:** DELETE todas las filas ptc del producto + INSERT una nueva — simple y correcto para una sola categoría.

#### `eliminar(int $id): bool`
- **Para qué sirve:** Borra producto por ID.
- **Retorno:** `true` si `rowCount() > 0`.
- **CASCADE:** Las filas en `productos_tienen_categorias` se eliminan solas.

#### Getters (`getId`, `getNombre`, `getPrecio`, etc.)
- **Para qué sirven:** Acceso controlado a propiedades privadas desde las vistas.
- **Por qué privadas + getters:** Encapsulamiento básico; PDO FETCH_CLASS asigna directamente a propiedades privadas por convención de nombres de columnas.

---

### Clase `Usuario`

#### `porEmail(string $email): ?self`
- **Para qué sirve:** Busca usuario por email.
- **Retorno:** `Usuario` o `null`.
- **Quién la llama:** `verificarCredenciales`.

#### `verificarCredenciales(string $email, string $password): ?self`
- **Para qué sirve:** Autentica combinación email/password.
- **Lógica:** Busca usuario → `password_verify` contra hash almacenado → devuelve usuario o null.
- **Quién la llama:** `ingresar.php` en POST.
- **Por qué no revelar si el email existe:** Mensaje genérico "Email o contraseña incorrectos" en la vista.

#### `iniciarSesion(self $usuario): void` (estático)
- **Para qué sirve:** Escribe `usuario_id` y `usuario_email` en `$_SESSION`.
- **Quién la llama:** Tras login exitoso.

#### `cerrarSesion(): void` (estático)
- **Para qué sirve:** Vacía `$_SESSION` y destruye la sesión.
- **Quién la llama:** `admin/index.php` cuando `seccion=salir`.

#### `estaLogueado(): bool` (estático)
- **Para qué sirve:** Comprueba si existe `$_SESSION['usuario_id']`.
- **Quién la llama:** `admin/index.php` para middleware de auth.

#### `idEnSesion(): ?int` (estático)
- **Para qué sirve:** Devuelve el ID del usuario logueado o null.
- **Quién la llama:** `producto-alta.php` para `usuario_fk`.

#### `getPassword(): string`
- **Uso interno:** Solo para `password_verify` dentro de la clase; no debería exponerse a vistas (actualmente no se hace).

---

## 9. Decisiones de programación

### `include` vs `require`

| Uso | Archivo | Razón |
|---|---|---|
| `include_once` | header.php, footer.php | Layout opcional en teoría; `once` evita duplicación si se incluye dos veces. |
| `require` | vistas/*.php | La vista es **obligatoria**; si falta el archivo, debe fallar fatalmente. |
| `require_once` | clases/*.php | Las clases no deben cargarse dos veces (evita "Cannot redeclare class"). |

### División en archivos

- **Un archivo por sección:** facilita localizar código en el examen y sigue el patrón del curso.
- **Clases separadas por entidad:** `Producto` y `Usuario` no mezclan responsabilidades.
- **Admin con vistas HTML completas:** el login y el CRUD tienen diseños distintos al sitio público; no comparten `includes/`.

### Validaciones del lado del servidor

- **Admin CRUD:** toda validación de negocio en PHP (campos vacíos, precio > 0, categoría válida). El atributo HTML `required` es solo UX.
- **Login:** verificación en servidor con hash — imprescindible; nunca confiar solo en el cliente.
- **Contacto:** solo validación cliente — decisión consciente de simular envío sin backend (limitación conocida).

### Algoritmo de autenticación

- `password_verify` (bcrypt) en lugar de comparar texto plano — estándar de la industria.
- Mensaje de error unificado — evita enumeración de usuarios.

### Variables globales vs locales

- `$_GET`, `$_POST`, `$_SESSION` son superglobales de PHP — uso inevitable en este patrón.
- `$seccionActual` se define en `index.php` y la consumen `header.php` y las vistas — variable de contexto de request.
- No hay variables globales custom (`$GLOBALS`) — buena práctica.

### Reutilización de código

- `Producto::todas()` sirve tanto al listado público como al admin — DRY en la capa de datos.
- CSS base con custom properties (`:root`) — colores y espaciados reutilizables.
- Formularios de alta y edición comparten `producto-editar.css` — misma estructura visual.

### PDO con prepared statements

- Todos los queries usan `prepare()` + `execute(['param' => $valor])` — mitiga SQL injection.
- Parámetros nombrados (`:id`, `:email`) — legibilidad.

### Ventajas y desventajas de la arquitectura

| Ventajas | Desventajas |
|---|---|
| Simple de explicar y evaluar | Lógica de formulario mezclada en vistas |
| Sin dependencias externas | Nueva conexión PDO por cada operación |
| Lista blanca de secciones segura | Sin autoload PSR-4 |
| PDO preparado | Sin transacciones explícitas en crear/actualizar |
| Separación público/admin | `productos.json` redundante si la BD es la fuente de verdad |

### Malas prácticas detectadas y cómo mejorarlas

1. **Credenciales hardcodeadas en `DBConexion.php`** → usar archivo `.env` o config fuera del webroot.
2. **Sin `PDO::ATTR_ERRMODE = EXCEPTION`** → errores silenciosos; difícil depurar.
3. **Sin CSRF tokens en formularios admin** → vulnerable a Cross-Site Request Forgery.
4. **Sin `htmlspecialchars` en `detalle.php` descripción** — sí se usa; bien implementado en la mayoría de salidas.
5. **`producto-borrar` por GET muestra página** — debería aceptar solo POST y devolver 405 en GET.
6. **Imagen como texto libre en alta** → riesgo de XSS si se insertara `<script>` en ruta (mitigado parcialmente por `htmlspecialchars` al mostrar).
7. **Puerto MAMP hardcodeado** → dificulta despliegue en XAMPP sin editar código.
8. **Sin roles de usuario** → cualquier usuario de la tabla `usuarios` es admin completo.

---

## 10. Flujo de cada formulario

### 10.1. Formulario de login (admin)

| Etapa | Detalle |
|---|---|
| **Origen** | `admin/vistas/ingresar.php` |
| **Destino** | `admin/index.php?seccion=ingresar` (mismo URL, method POST) |
| **Campos** | `email` (email), `password` (password) |
| **Validación cliente** | `required`, `type="email"`, `autocomplete` |
| **Validación servidor** | `trim` en email; `verificarCredenciales` con `password_verify` |
| **SQL** | `SELECT usuario_id, email, password FROM usuarios WHERE email = :email` |
| **Respuesta éxito** | Redirect 302 a `?seccion=productos`; sesión iniciada |
| **Respuesta error** | Re-render con mensaje y email repoblado |

### 10.2. Formulario de alta de producto

| Etapa | Detalle |
|---|---|
| **Origen** | `admin/vistas/producto-alta.php` |
| **Destino** | `index.php?seccion=producto-alta` POST |
| **Campos** | nombre, precio, descripcion_corta, descripcion, imagen, categoria_id |
| **Validación cliente** | `required` en todos los campos |
| **Validación servidor** | Campos no vacíos; precio numérico > 0; categoria_id > 0 |
| **SQL** | INSERT en `productos`; INSERT en `productos_tienen_categorias` |
| **Respuesta éxito** | Redirect a listado productos |
| **Respuesta error** | Mensaje de alerta; formulario repoblado con `$valoresAlta` |

### 10.3. Formulario de edición de producto

| Etapa | Detalle |
|---|---|
| **Origen** | `admin/vistas/producto-editar.php` |
| **Destino** | `index.php?seccion=producto-editar&id=N` POST |
| **Campos** | producto_id (hidden), nombre, precio, descripcion_corta, descripcion, imagen (hidden), categoria_id |
| **Validación** | Igual que alta; además verifica que el producto exista |
| **SQL** | UPDATE `productos`; DELETE ptc; INSERT ptc |
| **Respuesta éxito** | Redirect a productos |
| **Respuesta error** | Alerta en pantalla; si id inválido en GET → redirect silencioso |

### 10.4. Formulario de borrado (diálogo)

| Etapa | Detalle |
|---|---|
| **Origen** | Modal en `admin/vistas/productos.php` |
| **Destino** | `index.php?seccion=producto-borrar&id=N` POST |
| **Campos** | `producto_id` (hidden) |
| **Validación servidor** | `producto_id > 0`; producto debe existir (en flujo POST actual solo verifica id) |
| **SQL** | `DELETE FROM productos WHERE producto_id = :id` |
| **Respuesta** | Redirect a productos (sin mensaje de confirmación) |

### 10.5. Formulario de contacto (público)

| Etapa | Detalle |
|---|---|
| **Origen** | `vistas/contacto.php` |
| **Destino** | `action="#"` — no envía al servidor |
| **Campos** | nombre, email, asunto, motivo, mensaje |
| **Validación cliente** | HTML5 `required`, `type="email"`; JS `reportValidity()` |
| **Validación servidor** | **No existe** |
| **SQL** | **Ninguna** |
| **Respuesta** | `alert('Tu mensaje se envió con éxito...')` — simulación |

---

## 11. Manejo de sesiones

### Dónde se inicia

Solo en `sitio/admin/index.php` con `session_start()` al inicio del archivo. El sitio público **no** usa sesiones.

### Qué se almacena

| Clave | Constante | Contenido |
|---|---|---|
| `usuario_id` | `Usuario::SESSION_KEY_ID` | ID numérico del admin |
| `usuario_email` | `Usuario::SESSION_KEY_EMAIL` | Email para mostrar en el panel |

No se guarda contraseña ni nombre en sesión.

### Cómo se crea la sesión

Tras login exitoso, `Usuario::iniciarSesion($usuario)` escribe ambas claves en `$_SESSION`. PHP envía la cookie de sesión al navegador automáticamente.

### Cómo se destruye

`Usuario::cerrarSesion()`:
1. Asigna `$_SESSION = []`.
2. Si la sesión está activa, llama `session_destroy()`.

Se invoca al navegar a `?seccion=salir`.

### Middleware de protección

En `admin/index.php`, **antes** de cargar cualquier vista (excepto `ingresar`):

```text
si seccion != ingresar Y NO estaLogueado → redirect ingresar
```

Esto impide acceder por URL directa a `producto-alta` sin autenticación.

### Por qué se usan sesiones

- HTTP es stateless; la sesión mantiene el estado de login entre peticiones.
- Es el mecanismo estándar en PHP para autenticación en aplicaciones monolíticas sin JWT.

---

## 12. Seguridad

### Medidas implementadas

| Medida | Implementación |
|---|---|
| **SQL Injection** | PDO prepared statements en todas las consultas |
| **XSS (salida)** | `htmlspecialchars($texto, ENT_QUOTES, 'UTF-8')` en la mayoría de ecos PHP |
| **Password hashing** | bcrypt via `password_verify` / hashes `$2y$` en BD |
| **Control de acceso** | Middleware en `admin/index.php` con redirect |
| **Lista blanca de rutas** | `$seccionesPermitidas` en ambos index.php |
| **Cast de IDs** | `(int) $_GET['id']` evita inyección de tipos en IDs |
| **Mensaje de login genérico** | No distingue "usuario no existe" de "password incorrecta" |
| **Logout** | Limpieza de sesión |

### Permisos y autenticación

- Solo existe rol implícito de **administrador**.
- Cualquier registro válido en `usuarios` tendría acceso total al CRUD.
- No hay permisos granulares (solo lectura, etc.).

### Qué podría mejorarse

1. **CSRF:** agregar token en formularios POST del admin.
2. **Rate limiting** en login para frenar fuerza bruta.
3. **HTTPS** en producción para proteger cookie de sesión.
4. **`session_regenerate_id(true)`** tras login exitoso — previene session fixation.
5. **Flags de cookie:** `HttpOnly`, `Secure`, `SameSite=Strict`.
6. **Validar existencia de imagen** en servidor al dar de alta (evitar rutas arbitrarias).
7. **Procesar contacto en servidor** con sanitización y envío real de email.
8. **Ocultar `datos.txt`** del webroot o no commitear credenciales.
9. **PDO exception mode** y logging de errores fuera de producción.
10. **Confirmar borrado solo por POST** sin fallback GET.

---

## 13. Tecnologías utilizadas

| Tecnología | Rol en el proyecto |
|---|---|
| **PHP 8+** | Lenguaje del servidor; enrutamiento, sesiones, clases, PDO. Tipado en propiedades y return types. |
| **HTML5** | Estructura semántica: `<header>`, `<main>`, `<article>`, `<details>`, `<dialog>`, atributos ARIA. |
| **CSS3** | Estilos custom sin framework; variables CSS, flexbox/grid, responsive. Archivos modulares importados. |
| **JavaScript (vanilla)** | Toggle de contraseña en login; modal de borrado; validación simulada de contacto. Sin npm ni bundlers. |
| **MySQL** | Persistencia relacional; FK, CASCADE, índices. |
| **PDO** | Capa de acceso a datos con prepared statements y FETCH_CLASS. |
| **Google Fonts** | Inter (cuerpo) y Roboto (títulos) vía CDN. |
| **SVG** | Iconos inline y archivos en `imgs/` para UI ligera. |
| **WebP / PNG** | Imágenes de productos optimizadas. |

**No se utiliza Bootstrap.** El diseño es propio con sistema de colores cálidos (rojo `#a31212`, beige `#f5e6d3`).

**Entorno:** XAMPP o MAMP según consigna académica. El código apunta a MAMP (`127.0.0.1:8889`).

---

## 14. Resumen del funcionamiento completo

Imaginá que abrís el navegador y entrás a la carpeta `sitio/` servida por Apache.

La primera petición va a `index.php`. Ese archivo es el "portero": mira la URL, ve si pediste `?seccion=listado` o si no pediste nada (entonces asume `home`), y comprueba que la sección exista en su lista permitida. Si pediste algo raro, te manda a la vista 404.

Luego arma la página como un sándwich: arriba el `header.php` con el menú de Galmir (Inicio, Listado, Contacto) y un icono que lleva al admin; en el medio la vista que corresponda; abajo el `footer.php`.

Si estás en el home, ves contenido 100 % estático: un banner, tres tarjetas de categorías que enlazan a productos específicos, beneficios y preguntas frecuentes. Nada toca la base de datos.

Si entrás al listado, ahí sí PHP habla con MySQL. La vista crea un objeto `Producto`, que pide una conexión a `DBConexion`, ejecuta un SELECT con JOINs para traer cada juego con su categoría, y devuelve una lista de objetos. La vista recorre esa lista y imprime tarjetas escapando el HTML para que nadie pueda inyectar scripts.

Si hacés clic en "Ver detalle", la URL lleva un `id`. La vista de detalle convierte ese id a entero, busca el producto, y si no lo encuentra te avisa; si lo encuentra, muestra toda la información. Los botones de compra están ahí visualmente pero no hacen nada todavía.

El contacto es pura interfaz: completás campos, el navegador valida que no estén vacíos, y un script muestra un alert de éxito sin que el servidor se entere.

Para administrar el catálogo, entrás al link del admin. Ahí otro `index.php` toma el control, pero primero llama `session_start()` porque necesita acordarse si ya iniciaste sesión. Si no estás logueado, cualquier intento de ver productos te devuelve al login.

En el login, enviás email y contraseña. El servidor busca el email en la tabla `usuarios`, compara la contraseña con el hash bcrypt, y si coincide guarda tu id y email en la sesión. A partir de ahí podés ver la tabla de productos.

Desde el panel podés crear un producto completando un formulario: el servidor valida, inserta en `productos`, vincula la categoría en la tabla intermedia, y te redirige al listado. Editar funciona parecido pero hace UPDATE y reemplaza la categoría. Borrar muestra un diálogo de confirmación en JavaScript y, si aceptás, envía un POST que elimina el registro; MySQL borra en cascada las relaciones con categorías.

Todo cambio en el admin se refleja automáticamente en el sitio público porque ambos leen la misma base de datos. El archivo `productos.json` quedó obsoleto como fuente de datos pero documenta el estado inicial de la migración.

---

## 15. Posibles preguntas de examen

### Arquitectura y estructura

**1. ¿Qué patrón de enrutamiento usa el proyecto?**  
Front Controller: un único `index.php` por subsistema recibe todas las peticiones y despacha según `?seccion=`.

**2. ¿Por qué hay dos `index.php`?**  
Uno para el sitio público (`sitio/index.php`) y otro para el admin (`sitio/admin/index.php`), separando autenticación y layout.

**3. ¿Qué relación tiene este proyecto con `productos.json`?**  
Era la fuente de datos del primer parcial; los datos se migraron a MySQL y el JSON quedó como backup.

**4. ¿Dónde está el "modelo" y dónde la "vista"?**  
Modelo: `clases/Producto.php` y `clases/Usuario.php`. Vista: `vistas/` y `admin/vistas/`.

**5. ¿Por qué `detalle` no aparece en el menú de navegación?**  
Se accede desde el listado o enlaces del home; no es una sección de primer nivel en la navegación.

### Base de datos

**6. ¿Cuántas tablas tiene la base y cuáles son?**  
Cuatro: `usuarios`, `categorias`, `productos`, `productos_tienen_categorias`.

**7. ¿Qué tipo de relación hay entre productos y categorías?**  
Muchos a muchos, implementada con tabla intermedia `productos_tienen_categorias`.

**8. ¿Por qué existe `usuario_fk` en productos?**  
Para registrar qué administrador dio de alta cada producto (trazabilidad).

**9. ¿Qué pasa si borro un producto respecto a sus categorías?**  
ON DELETE CASCADE en la tabla intermedia elimina las filas de `productos_tienen_categorias`.

**10. ¿Por qué `GROUP_CONCAT` en las consultas de productos?**  
Para obtener en una sola query todas las categorías de un producto como string separado por comas.

**11. ¿Por qué el nombre de la base es `dw3_kuringhian_garcia`?**  
Convención del curso: `dw` + cuatrimestre + apellidos de los integrantes.

### PHP y PDO

**12. ¿Qué ventaja tienen los prepared statements?**  
Separan SQL de datos; previenen inyección SQL.

**13. ¿Qué es `PDO::FETCH_CLASS` en este proyecto?**  
Mapea cada fila del resultado a una instancia de la clase `Producto` automáticamente.

**14. ¿Por qué `require` para vistas y `require_once` para clases?**  
La vista debe existir sí o sí; las clases no deben declararse dos veces.

**15. ¿Dónde se castea el id de producto y por qué?**  
En `detalle.php` y vistas admin: `(int) $_GET['id']` garantiza un entero seguro.

### Seguridad y sesiones

**16. ¿Dónde se llama `session_start()`?**  
Solo en `sitio/admin/index.php`.

**17. ¿Qué datos se guardan en sesión?**  
`usuario_id` y `usuario_email`. Nunca la contraseña.

**18. ¿Cómo se protegen las rutas del admin?**  
`admin/index.php` verifica `Usuario::estaLogueado()` antes de cargar vistas distintas de `ingresar`.

**19. ¿Cómo se almacenan las contraseñas?**  
Como hash bcrypt (`$2y$10$...`), verificadas con `password_verify`.

**20. ¿Qué es XSS y cómo lo previenen?**  
Cross-Site Scripting: inyectar HTML/JS malicioso. Se mitiga con `htmlspecialchars` al imprimir datos.

**21. ¿El proyecto está protegido contra CSRF?**  
No. Es una mejora pendiente en los formularios POST del admin.

### Formularios y flujos

**22. ¿El formulario de contacto guarda datos?**  
No. Solo muestra un alert en JavaScript.

**23. ¿Cuál es el flujo completo de login?**  
POST a ingresar → verificarCredenciales → iniciarSesion → redirect productos.

**24. ¿Cómo se borra un producto en la práctica?**  
Desde el modal en `productos.php` que hace POST a `producto-borrar`.

**25. ¿Se puede cambiar la imagen al editar un producto?**  
No en la UI actual; la imagen va en un campo hidden sin input visible.

**26. ¿Qué validaciones hace el alta de producto en servidor?**  
Campos obligatorios no vacíos, precio numérico mayor a cero, categoría seleccionada.

### Diseño y frontend

**27. ¿Se usa Bootstrap?**  
No. CSS propio con variables en `base.css`.

**28. ¿Cómo se organizan los estilos?**  
`estilos.css` importa módulos: base, home, contacto, listado, detalle.

**29. ¿Para qué sirve el elemento `<dialog>` en productos admin?**  
Modal nativo HTML para confirmar eliminación sin librerías externas.

### Decisiones y mejoras

**30. ¿Por qué la imagen se guarda como ruta y no como archivo subido?**  
Simplicidad académica: el admin escribe `imgs/teg.webp` en lugar de implementar upload.

**31. ¿Qué desventaja tiene crear una nueva conexión PDO en cada método?**  
Overhead de conexión; en producción se usaría una única conexión o pool.

**32. ¿Por qué el listado ordena por `fecha_alta DESC`?**  
Para mostrar primero los productos agregados más recientemente.

**33. ¿Qué ocurriría si alguien accede a `?seccion=../../etc/passwd`?**  
La lista blanca impide cargar archivos fuera de `vistas/`; caería en 404.

**34. ¿El sitio público necesita base de datos para el home?**  
No. Home y contacto funcionan sin MySQL.

**35. ¿Qué credenciales de prueba usa el admin?**  
`admin@galmir.local` / `admin123` (según `datos.txt` y datos semilla SQL).

---

## 16. Conclusión

Este proyecto fue diseñado como una **evolución académica progresiva**: de un catálogo estático en JSON (primer parcial) a una aplicación dinámica con **MySQL, PDO, sesiones y CRUD** (segundo parcial), siguiendo la estructura del ejemplo docente del curso.

Las decisiones más importantes fueron:

1. **Front Controller con lista blanca** — seguridad y claridad sin framework.
2. **Separación público / admin** — distintos layouts y reglas de acceso.
3. **Clases de modelo con PDO preparado** — acceso a datos seguro y encapsulado.
4. **Normalización de categorías** con tabla intermedia — diseño relacional correcto aunque la UI simplifique a una categoría.
5. **Contraseñas con bcrypt y sesiones PHP** — autenticación mínima pero correcta para el alcance del trabajo.

El sistema cumple su objetivo pedagógico: demostrar que el alumno comprende enrutamiento PHP, interacción con base de datos relacional, autenticación, validación server-side, escape de salida HTML y organización modular de un proyecto web clásico.

Las principales líneas de evolución —probablemente ligadas al **Final** (pendiente de documentar según `docs/Programación II - Final.pdf`)— serían: carrito de compras real, procesamiento de contacto, subida de imágenes, roles de usuario, protección CSRF y refactor hacia una separación estricta controlador-vista sin lógica POST en las vistas.

---

*Documento generado para estudio y defensa oral del proyecto Galmir — Programación II.*
