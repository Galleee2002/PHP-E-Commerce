# RESUMEN.md — Guía de defensa del final (Galmir)

Apunte de estudio para defender el final de **Programación II**.  
Proyecto: e-commerce de juegos de mesa **Galmir** (Sitio + Admin, PHP + MySQL/PDO).


### Front controller + whitelist

Cada área tiene un único punto de entrada. La sección llega por query string (`?seccion=listado`) y **solo** se carga si está en una lista permitida. Si no está → `404`.

**`sitio/index.php` (líneas 9–18)**

```php
$seccionesPermitidas = [
    'home',
    'listado',
    'detalle',
    'contacto',
    'registro',
    'iniciar-sesion',
    'perfil',
    'carrito',
];
```

**`sitio/index.php` (líneas 159–164)**

```php
if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

include_once __DIR__ . '/includes/header.php';
require __DIR__ . '/vistas/' . $seccionActual . '.php';
```

**Por qué importa:** el nombre del archivo no sale de un string libre del usuario. Eso evita incluir paths arbitrarios (protección básica frente a LFI).

**Patrón de cursada:** `require_once` manual, sin framework, sin autoload, sin namespaces. Las consultas SQL viven **dentro de métodos de clase**, estilo Active Record liviano (como `Noticia` del docente).

Rutas con `__DIR__` (nunca paths de la PC ni `localhost/...` hardcodeados).

---

## 4. Archivos implementados (por responsabilidad)

### 4.1 Entrada

| Archivo | Rol |
|---------|-----|
| `sitio/index.php` | Front controller del Sitio: sesión, whitelist, guards de auth, POST del carrito/compra, registro/login |
| `sitio/admin/index.php` | Front controller del Admin: whitelist, guard `esAdmin()`, logout |

### 4.2 Clases (`sitio/clases/`)

#### `DBConexion.php`
- **Qué hace:** crea la conexión PDO a MySQL.
- **Método clave:** `getConexion(): PDO`.
- **Por qué existe:** la consigna pide OOP mínimo para la conexión; centraliza host, usuario, DB.

**`sitio/clases/DBConexion.php` (líneas 10–17)**

```php
public function getConexion(): PDO
{
    $db_dsn = "mysql:host=" . self::DB_HOST
        . ";dbname=" . self::DB_NAME
        . ";charset=utf8mb4";

    return new PDO($db_dsn, self::DB_USER, self::DB_PASS);
}
```

Base: `dw3_kuringhian_garcia`.

#### `Producto.php`
- **Qué hace:** catálogo público + CRUD del ABM admin + categorías.
- **Métodos clave:** `todas()`, `porId()`, `crear()`, `actualizar()`, `eliminar()`, `todasCategorias()`.
- **Por qué existe:** el “ítem” de la consigna; concentra el SQL de productos.

#### `Usuario.php`
- **Qué hace:** registro, login, roles, sesión y listados para admin.
- **Métodos clave:** `porEmail()`, `porId()`, `todos()`, `verificarCredenciales()`, `registrar()`, `iniciarSesion()`, `cerrarSesion()`, `estaLogueado()`, `esAdmin()`.
- **Por qué existe:** autenticación + autorización en una sola clase, con SQL encapsulado.

#### `Carrito.php`
- **Qué hace:** manipular `$_SESSION['carrito']` (agregar, quitar, total, vaciar).
- **Métodos clave:** `agregar()`, `quitar()`, `vaciar()`, `obtenerItems()`, `calcularTotal()`, `cantidadItems()`.
- **Por qué existe:** la consigna exige OOP del carrito; no hay tabla `carritos` en MySQL.

#### `Compra.php`
- **Qué hace:** persistir el pedido y consultar historial.
- **Métodos clave:** `crearDesdeCarrito()`, `porUsuario()`, `porId()`.
- **Por qué existe:** la compra debe quedar en DB (detalle + total) y el admin debe ver historial.


## 5. Decisiones de diseño (núcleo para estudiar)

Cada decisión sigue el mismo formato: qué → por qué → código → frase para el docente.

---

### 5.1 Roles como columna `ENUM` (no tabla `roles`)

**Qué hicimos:** `usuarios.rol` con valores `comun` y `admin`.

**Por qué:** la consigna pide “al menos 2 roles”. Con dos valores fijos, una columna ENUM alcanza; una tabla `roles` sería sobreingeniería para este alcance.

**`db/dw3_kuringhian_garcia.sql` — tabla `usuarios`**

```sql
CREATE TABLE `usuarios` (
  `usuario_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `rol` enum('comun','admin') NOT NULL DEFAULT 'comun',
```

**Frase:** “Cumplimos los dos roles con una columna ENUM en usuarios; no hace falta una tabla de roles para solo dos valores.”

---

### 5.2 Visitante ≠ tercer rol

**Qué hicimos:** quien no está logueado simplemente **no tiene** claves de sesión. No hay rol `visitante`.

**Por qué:** un visitante es ausencia de autenticación, no un tipo de usuario en la DB.

**Frase:** “El visitante no es un rol: es alguien sin sesión. Los roles viven en la tabla usuarios para cuentas registradas.”

---

### 5.3 Autenticación ≠ autorización

**Qué hicimos:**
- `estaLogueado()` → ¿hay `usuario_id` en sesión? (autenticación)
- `esAdmin()` → ¿el rol en sesión es `admin`? (autorización)

**`sitio/clases/Usuario.php` (líneas 133–142)**

```php
public static function estaLogueado(): bool
{
    return isset($_SESSION[self::SESSION_KEY_ID]);
}

public static function esAdmin(): bool
{
    return isset($_SESSION[self::SESSION_KEY_ROL])
        && $_SESSION[self::SESSION_KEY_ROL] === self::ROL_ADMIN;
}
```

En el Admin, todo lo que no sea `ingresar` exige `esAdmin()`:

**`sitio/admin/index.php` (líneas 30–33)**

```php
if ($seccionActual !== 'ingresar' && !Usuario::esAdmin()) {
    header('Location: ?seccion=ingresar');
    exit;
}
```

**Frase:** “Autenticación es ‘quién sos’ (`estaLogueado`); autorización es ‘qué podés hacer’ (`esAdmin`). Un común puede comprar en el Sitio, pero no entra al panel.”

---

### 5.4 Contraseñas hasheadas y sesión segura

**Qué hicimos:**
- Al registrar: `password_hash(..., PASSWORD_DEFAULT)`.
- Al login: `password_verify`.
- En sesión guardamos `id`, `email`, `rol` — **nunca** el password.
- Al iniciar sesión: `session_regenerate_id(true)` (mitiga fijación de sesión).
- Mensaje genérico: “Email o contraseña incorrectos.” (no revelamos si el email existe).

**`sitio/clases/Usuario.php` — `registrar()`**

```php
public function registrar(string $email, string $password, string $nombre, string $apellido): self
{
    $db = (new DBConexion)->getConexion();

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $consulta = "INSERT INTO usuarios (email, password, nombre, apellido, rol)
                 VALUES (:email, :password, :nombre, :apellido, :rol)";
    $stmt = $db->prepare($consulta);
    $stmt->execute([
        'email' => $email,
        'password' => $passwordHash,
        // ...
        'rol' => self::ROL_COMUN,
    ]);
```

**`sitio/clases/Usuario.php` — `iniciarSesion()`**

```php
public static function iniciarSesion(self $usuario): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    $_SESSION[self::SESSION_KEY_ID] = $usuario->getId();
    $_SESSION[self::SESSION_KEY_EMAIL] = $usuario->getEmail();
    $_SESSION[self::SESSION_KEY_ROL] = $usuario->getRol();
}
```

**Frase:** “Las contraseñas se guardan hasheadas; la sesión solo guarda id, email y rol. Al loguear regeneramos el id de sesión.”

---

### 5.5 Carrito en `$_SESSION`, compra en MySQL

**Qué hicimos:** mientras el usuario navega, el carrito vive en sesión. Al completar la compra, se inserta en MySQL y se vacía la sesión.

**Por qué:** la consigna pide carrito autenticado + guardar detalle + vaciar. No exige tabla `carritos`. Separar “temporal” (sesión) de “historial” (DB) es el patrón simple de la cursada.

**`sitio/clases/Carrito.php` — `agregar()`**

```php
public function agregar(int $productoId, int $cantidad = 1): bool
{
    // ...
    $producto = (new Producto)->porId($productoId);

    if ($producto === null) {
        return false;
    }

    $this->asegurarSesion();

    if (isset($_SESSION[self::SESSION_KEY][$productoId])) {
        $_SESSION[self::SESSION_KEY][$productoId]['cantidad'] += $cantidad;
    } else {
        $_SESSION[self::SESSION_KEY][$productoId] = [
            'cantidad' => $cantidad,
            'nombre' => $producto->getNombre(),
            'precio' => $producto->getPrecio(),
        ];
    }

    return true;
}
```

Estructura en sesión:

```text
$_SESSION['carrito'][producto_id] = ['cantidad' => int, 'nombre' => string, 'precio' => float]
```

**Frase:** “El carrito es estado temporal en sesión; la compra es un hecho histórico en MySQL. Por eso no hay tabla de carritos.”

---

### 5.6 Precio re-leído desde DB → `precio_unitario`

**Qué hicimos:** al listar el carrito usamos el precio guardado en sesión (comodidad). Al **persistir** la compra, volvemos a leer el precio con `Producto::porId()` y lo guardamos en `precio_unitario`.

**Por qué:** la sesión podría estar desactualizada; MySQL es la fuente de verdad. Además, si mañana cambia el precio del producto, el historial de compras viejas sigue mostrando lo que se pagó.

**`sitio/clases/Compra.php` — dentro de `crearDesdeCarrito()`**

```php
$producto = $productoModelo->porId($productoId);

if ($producto === null) {
    throw new RuntimeException('Un producto del carrito ya no está disponible.');
}

$precioUnitario = (float) $producto->getPrecio();
$total += $precioUnitario * $cantidad;

$lineas[] = [
    'producto_fk' => $productoId,
    'cantidad' => $cantidad,
    'precio_unitario' => $precioUnitario,
];
```

**Frase:** “Al comprar no confiamos en el precio de la sesión: lo re-leemos de la DB y lo congelamos en `precio_unitario`.”

---

### 5.7 Transacción PDO al completar la compra

**Qué hicimos:** `beginTransaction` → INSERT cabecera `compras` → INSERT líneas en `compras_tienen_productos` → `commit`. Si algo falla → `rollBack`. Solo después del commit exitoso se vacía el carrito.

**`sitio/clases/Compra.php` — transacción**

```php
$db = (new DBConexion)->getConexion();
$db->beginTransaction();

try {
    $stmtCompra = $db->prepare(
        'INSERT INTO compras (usuario_fk, total)
         VALUES (:usuario_fk, :total)'
    );
    // ... INSERT detalle por cada línea ...
    $db->commit();
} catch (Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }

    throw $e;
}

$carrito->vaciar();
```

**Frase:** “Usamos una transacción para que el pedido se guarde completo o no se guarde nada. Si falla el SQL, hacemos rollBack y el carrito no se vacía.”

---

### 5.8 SQL solo en clases + placeholders

**Qué hicimos:** las vistas no arman SQL. Los métodos reciben datos y usan `:email`, `:id`, etc.

**Por qué:** regla de la consigna: concatenar valores de `$_GET`/`$_POST` en el SQL = desaprobación. Los placeholders evitan inyección SQL.

**`sitio/clases/Usuario.php` — `porEmail()`**

```php
public function porEmail(string $email): ?self
{
    $db = (new DBConexion)->getConexion();

    $consulta = "SELECT usuario_id, email, password, nombre, apellido, rol
                 FROM usuarios
                 WHERE email = :email";
    $stmt = $db->prepare($consulta);
    $stmt->execute(['email' => $email]);
```

**Incorrecto (nunca):** `"WHERE email = '" . $_POST['email'] . "'"`.

**Frase:** “Todas las consultas con datos del usuario usan prepared statements con placeholders, y el SQL está dentro de los métodos de clase.”

---

### 5.9 Front controller + whitelist

**Qué hicimos:** ver sección 3. También hay guards de vista:

**`sitio/index.php` (líneas 37–50)**

```php
if (
    ($seccionActual === 'registro' || $seccionActual === 'iniciar-sesion')
    && Usuario::estaLogueado()
) {
    header('Location: index.php?seccion=perfil');
    exit;
}

if (
    ($seccionActual === 'perfil' || $seccionActual === 'carrito')
    && !Usuario::estaLogueado()
) {
    header('Location: index.php?seccion=iniciar-sesion');
    exit;
}
```

**Frase:** “El front controller elige la vista con una whitelist; los guards redirigen según si hay sesión o no.”

---

### 5.10 Escape XSS e IDs casteados

**Qué hicimos:** en vistas, datos dinámicos con `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`. IDs desde GET/POST como `(int)`.

**Frase:** “Escapamos la salida HTML para XSS y casteamos los IDs a entero antes de usarlos.”

---

## 7. Base de datos (didáctica)

### 7.1 Nombre y tablas

Base: **`dw3_kuringhian_garcia`** (formato `dw3_apellido1_apellido2`).

| Tabla | Para qué |
|-------|----------|
| `usuarios` | Cuentas + rol |
| `productos` | Ítems del catálogo (≥5 campos) |
| `categorias` | Tabla adicional relacionada a ítems |
| `productos_tienen_categorias` | N:M productos ↔ categorías |
| `compras` | Cabecera del pedido |
| `compras_tienen_productos` | Detalle N:M compra ↔ productos |

### 7.2 Relaciones

```text
usuarios 1──< productos                     (quién lo dio de alta en admin)
usuarios 1──< compras
productos N──M categorias                   via productos_tienen_categorias
compras   N──M productos                    via compras_tienen_productos
```

**Integridad:**
- CASCADE en puentes N:M de categorías: al borrar producto/categoría se limpian uniones.
- CASCADE en detalle de compra → compras: borrar una compra borra sus líneas.
- **Sin** CASCADE en `compras.usuario_fk` ni `productos.usuario_fk`: no queremos perder historial/autoría al tocar usuarios.

### 7.2.1 Cardinalidad: qué es 1 a N y N a M

La **cardinalidad** indica cuántas filas de una tabla se relacionan con cuántas de otra. En el DER aparece como `1`, `N` o `M` en los extremos de cada línea.

#### 1 a N (uno a muchos)

**Una** fila de A se relaciona con **varias** de B. Cada fila de B apunta a **una sola** de A.

Ejemplo: un usuario puede tener muchas compras; cada compra pertenece a un solo usuario.

```text
Usuario #2
   ├── Compra #1
   ├── Compra #2
   └── Compra #4
```

En SQL se implementa con una **FK en el lado “muchos”**:

```sql
-- compras.usuario_fk → usuarios.usuario_id
CONSTRAINT fk_compras_usuario
  FOREIGN KEY (usuario_fk) REFERENCES usuarios (usuario_id)
```

Misma lógica: `usuarios` 1──N `productos` (`productos.usuario_fk` = quién lo dio de alta).

**Frase:** “Uno a muchos: un usuario, muchas compras; la clave foránea va en la tabla del lado muchos.”

#### N a M (muchos a muchos)

**Varias** filas de A se relacionan con **varias** de B (y al revés).

Ejemplo: un producto puede tener varias categorías; una categoría agrupa muchos productos.

```text
T.E.G. ──────── Estrategia
Monopoly ────── Clásico
Burako ──────── Cartas
No Lo Testeamos ─ Cartas   ← "Cartas" tiene más de un producto
```

En el modelo relacional **no** se pone una FK directa de un lado al otro. Se crea una **tabla puente** (intermedia) con PK compuesta:

```text
productos  N ── M  categorias
              ↓
   productos_tienen_categorias (producto_fk, categoria_fk)
```

El otro N:M del proyecto: `compras` ↔ `productos` vía `compras_tienen_productos` (además guarda `cantidad` y `precio_unitario`, datos propios de la línea).

**Frase:** “Muchos a muchos: ambos lados pueden ser varios; por eso usamos tabla intermedia.”

#### Cómo distinguirlos

| Relación | Pregunta rápida | Implementación |
|----------|-----------------|----------------|
| **1:N** | ¿Cada B tiene un solo A? → sí | FK en la tabla del “muchos” |
| **N:M** | ¿Ambos lados pueden ser varios? → sí | Tabla puente |

| En nuestro DER | Tipo |
|----------------|------|
| `usuarios` → `productos` | 1:N |
| `usuarios` → `compras` | 1:N |
| `productos` ↔ `categorias` | N:M |
| `compras` ↔ `productos` | N:M |

### 7.3 CRUD en SQL (con ancla en el proyecto)

CRUD = las cuatro operaciones básicas: **C**reate, **R**ead, **U**pdate, **D**elete → en SQL: `INSERT`, `SELECT`, `UPDATE`, `DELETE`.

#### SELECT (leer)

Lee filas. En el proyecto: listar productos, buscar usuario por email, historial de compras.

Ejemplo — buscar usuario (placeholder `:email`):

```sql
SELECT usuario_id, email, password, nombre, apellido, rol
FROM usuarios
WHERE email = :email;
```

Ejemplo — listado de productos con categorías (`Producto::todas`): JOIN + `GROUP_CONCAT`.

Ejemplo — compras de un usuario:

```sql
SELECT compra_id, usuario_fk, fecha, total
FROM compras
WHERE usuario_fk = :usuario_fk
ORDER BY fecha DESC;
```

#### INSERT (crear)

Agrega filas nuevas. En el proyecto: registro, alta de producto, completar compra.

```sql
-- Registro (Usuario::registrar)
INSERT INTO usuarios (email, password, nombre, apellido, rol)
VALUES (:email, :password, :nombre, :apellido, :rol);

-- Cabecera de compra (Compra::crearDesdeCarrito)
INSERT INTO compras (usuario_fk, total)
VALUES (:usuario_fk, :total);

-- Línea de detalle
INSERT INTO compras_tienen_productos
  (compra_fk, producto_fk, cantidad, precio_unitario)
VALUES
  (:compra_fk, :producto_fk, :cantidad, :precio_unitario);
```

Alta de producto (`Producto::crear`): primero INSERT en `productos`, después INSERT en `productos_tienen_categorias` con el `lastInsertId()`.

#### UPDATE (actualizar)

Modifica filas existentes. En el proyecto: editar producto en el ABM.

**`sitio/clases/Producto.php` — `actualizar()`**

```php
$consulta = "
    UPDATE productos
    SET nombre = :nombre,
        precio = :precio,
        descripcion_corta = :descripcion_corta,
        descripcion = :descripcion,
        imagen = :imagen
    WHERE producto_id = :id
";

$stmt = $db->prepare($consulta);
$stmt->execute([
    'nombre' => $nombre,
    'precio' => $precio,
    // ...
    'id' => $id,
]);
```

Al cambiar categoría: `DELETE` del puente + `INSERT` nuevo (reemplazo limpio de la relación).

#### DELETE (borrar)

Elimina filas. En el proyecto: borrar producto del ABM.

**`sitio/clases/Producto.php` — `eliminar()`**

```php
$consulta = "DELETE FROM productos WHERE producto_id = :id";
$stmt = $db->prepare($consulta);
$stmt->execute(['id' => $id]);
```

Gracias al `ON DELETE CASCADE` de `productos_tienen_categorias`, al borrar el producto también se borran sus filas de unión con categorías.

### 7.4 Placeholders vs concatenar

| Forma | ¿OK? |
|-------|------|
| `WHERE id = :id` + `execute(['id' => $id])` | Sí |
| `WHERE id = " . $id` o meter `$_POST` en el string SQL | No (desaprobación) |

PDO `prepare` + `execute` separa la estructura del SQL de los valores: el motor trata los datos como datos, no como código SQL.

### 7.5 Seed para la demo

Credenciales en `datos.txt`:

| Cuenta | Email | Password | Rol |
|--------|-------|----------|-----|
| Admin | `admin@galmir.local` | `admin123` | admin |
| Común | `usuario@galmir.local` | `usuario123` | comun |

Hay compras de ejemplo vinculadas a usuarios comunes: en la defensa podés abrir Admin → Usuarios → Detalle y mostrar historial **sin** tener que comprar en el momento (aunque el flujo completo también funciona).

---

## 8. Cheatsheet de defensa oral

**1. ¿Por qué el carrito está en sesión y no en la DB?**  
Porque es estado temporal mientras el usuario navega. Lo permanente es la compra: al completar, insertamos en MySQL y vaciamos la sesión.

**2. ¿Cómo distinguís autenticación de autorización?**  
`estaLogueado()` mira si hay sesión; `esAdmin()` mira si el rol es admin. Un común autenticado compra; solo el admin entra al panel.

**3. ¿El visitante es un rol?**  
No. Es ausencia de sesión. Los roles `comun`/`admin` son de usuarios registrados.

**4. ¿Por qué `precio_unitario` en el detalle de compra?**  
Congela el precio al momento de comprar. Si mañana cambia el catálogo, el historial sigue siendo correcto.

**5. ¿Qué hace la transacción PDO?**  
Garantiza atomicidad: o se guardan cabecera + todas las líneas, o se hace `rollBack` y no queda un pedido a medias. El carrito solo se vacía si el commit salió bien.

**6. ¿Dónde están las consultas SQL?**  
Dentro de métodos de las clases (`Usuario`, `Producto`, `Compra`, etc.), con placeholders. Las vistas solo renderizan.

**7. ¿Cómo evitás cargar archivos arbitrarios con `?seccion=`?**  
Con una whitelist: si la sección no está permitida, forzamos `404` y nunca incluimos un path libre.

**8. ¿Cómo guardás las contraseñas?**  
Con `password_hash` al registrar y `password_verify` al login. En sesión no va el password.

**9. ¿Por qué columna `rol` y no tabla `roles`?**  
Solo hay dos roles fijos; ENUM cumple la consigna con el menor cambio de schema.

**10. ¿Cómo ves el historial en el admin?**  
`Usuario::todos()` lista; en el detalle, `Compra::porUsuario` trae cabeceras y `Compra::porId` las líneas de cada compra.

**11. ¿Hay pasarela de pagos?**  
No. La consigna pide guardar el detalle y vaciar el carrito, sin pagos.

**12. ¿Qué es PDO y por qué lo usás?**  
PHP Data Objects: API para hablar con MySQL. Lo exige la materia; permite prepared statements seguros.

**13. ¿Qué es 1 a N?**  
Una fila de A con muchas de B; cada B apunta a un solo A. Ejemplo: un usuario → muchas compras. Se implementa con FK en el lado muchos (`compras.usuario_fk`).

**14. ¿Qué es N a M?**  
Varias de A con varias de B. Ejemplo: productos ↔ categorías. Se implementa con tabla puente (`productos_tienen_categorias`). Lo mismo compras ↔ productos vía `compras_tienen_productos`.

---

## 9. Checklist mental pre-defensa

Recorrido feliz a poder demostrar:

1. Home / Listado / Detalle (catálogo desde DB).
2. Registro de un común (o login con `usuario@galmir.local` / `usuario123`).
3. Agregar productos al carrito desde el detalle.
4. Ver carrito, quitar un ítem, completar compra (carrito vacío + filas nuevas en DB).
5. Logout.
6. Entrar al Admin con `admin@galmir.local` / `admin123`.
7. ABM: listar / alta o editar un producto.
8. Usuarios → Detalle → historial (fecha, total, productos con `precio_unitario`).

Si te preguntan “mostrame en el código”: tené a mano `Usuario.php` (auth/roles), `Carrito.php` (sesión), `Compra.php` (transacción) y el `index.php` del Sitio (whitelist + guards).

---

*Documento independiente para estudiar la defensa. Proyecto Galmir — Programación II — carácter final.*
