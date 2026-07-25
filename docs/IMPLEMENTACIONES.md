# IMPLEMENTACIONES.md — Fases 1 a 6 (Galmir)

Documento académico sobre lo implementado en el final de Programación II.  
Fuente de reglas de alcance: `docs/RULES.md`.  
Devolución QA: `docs/QA-FINAL.md`.

**Estado actual:** Fases **1–6** finalizadas. Proyecto listo para entregar (`Kuringhian_Garcia.zip`).

---

## 0. Mapa rápido

| Fase | Tema | Resultado |
|------|------|-----------|
| 1 | Base de datos y roles | Schema con `rol`, `compras`, detalle N:M |
| 2 | Auth pública y roles | Registro, login, perfil, guards Sitio/Admin |
| 3 | Carrito OOP | Agregar / listar / quitar en `$_SESSION` |
| 4 | Completar compra | Transacción PDO + vaciar carrito |
| 5 | Admin usuarios | Listado + detalle con historial de compras |
| 6 | Pulido y entrega | JS sin `var`, nav, DER, `datos.txt` final, zip, QA E2E |

---

## 1. Fase 1 — Base de datos y roles

**Objetivo:** dejar el schema listo para auth, compras e historial.

Base: `dw3_kuringhian_garcia` (`db/dw3_kuringhian_garcia.sql`).

### Cambios principales

1. Columna `usuarios.rol` (`ENUM('comun','admin')`, default `comun`).
2. Tabla `compras` (`compra_id`, `usuario_fk`, `fecha`, `total`).
3. Tabla `compras_tienen_productos` (`compra_fk`, `producto_fk`, `cantidad`, `precio_unitario`).
4. Seed: admin, usuario común y ≥1 compra de ejemplo.

`precio_unitario` guarda el precio **al momento de la compra** (historial real si mañana cambia el catálogo).

Relaciones:

```text
usuarios 1──< compras
compras   N──M productos   (compras_tienen_productos)
productos N──M categorias  (ya existía)
```

---

## 2. Fase 2 — Auth pública y roles en sesión

**Objetivo:** acceso de usuarios en el Sitio y protección del Admin.

### Sesiones

En `sitio/index.php` y `sitio/admin/index.php` se llama a `session_start()`.  
Eso habilita `$_SESSION` entre peticiones HTTP.

| Constante (`Usuario`) | Clave | Contenido |
|-----------------------|-------|-----------|
| `SESSION_KEY_ID` | `usuario_id` | ID |
| `SESSION_KEY_EMAIL` | `usuario_email` | Email |
| `SESSION_KEY_ROL` | `usuario_rol` | `comun` / `admin` |

Al login: `session_regenerate_id(true)` (mitiga fijación de sesión).

- **Autenticación** (`estaLogueado()`): ¿hay `usuario_id` en sesión?
- **Autorización** (`esAdmin()`): ¿el rol es `admin`?

Un visitante sin cuenta **no es un rol**: solo navega sin sesión.

### Clase `Usuario`

Archivo: `sitio/clases/Usuario.php`.

- Lectura: `porEmail()`, `porId()`, `todos()` (Fase 5).
- Auth: `verificarCredenciales()`, `registrar()`, `iniciarSesion()`, `cerrarSesion()`.
- Roles: `estaLogueado()`, `esAdmin()`, `idEnSesion()`.

Consultas SQL **dentro de métodos**, con PDO y placeholders (`:email`, etc.).

### Contraseñas

```php
password_hash($password, PASSWORD_DEFAULT);   // al registrar
password_verify($ingresada, $hashGuardado);  // al login
```

Mensaje de error genérico: “Email o contraseña incorrectos.” (no revela si el email existe).

### Front controller y guards

Whitelist pública (incluye lo agregado en Fases 3+):

```php
$seccionesPermitidas = [
    'home', 'listado', 'detalle', 'contacto',
    'registro', 'iniciar-sesion', 'perfil', 'carrito',
];
```

| Situación | Acción |
|-----------|--------|
| Logueado en `registro` / `iniciar-sesion` | → `perfil` |
| No logueado en `perfil` / `carrito` | → `iniciar-sesion` |
| `?seccion=salir` | Cierra sesión → `home` |
| Admin sin `esAdmin()` | No entra al ABM |

La lógica de POST/guards va **antes** del HTML para que `header('Location: ...')` funcione.

---

## 3. Fase 3 — Carrito OOP en sesión

**Objetivo:** armar carrito autenticado sin tablas de carrito en MySQL.

### Por qué en sesión

| Mientras navega | Al comprar (Fase 4) |
|-----------------|---------------------|
| `$_SESSION['carrito']` | Filas en MySQL + vaciar sesión |

Cumple la consigna con el patrón de cursada: sin tabla `carritos`.

### Clase `Carrito`

Archivo: `sitio/clases/Carrito.php`.

```php
public const SESSION_KEY = 'carrito';
// $_SESSION['carrito'][producto_id] = ['cantidad', 'nombre', 'precio'];
```

Métodos: `agregar()`, `quitar()`, `vaciar()`, `obtenerItems()`, `calcularTotal()`, `cantidadItems()`.

Al agregar se valida el producto con `Producto::porId()`. Nombre/precio en sesión sirven para listar; el precio de compra se re-lee en Fase 4.

### POST y vistas

- Detalle: form `accion=agregar-carrito` (+ cantidad).
- Carrito: listado, quitar, total, badge en header.
- Mensajes con patrón **flash** (`FLASH_OK` / `FLASH_ERROR`): set → redirect → show → unset.

---

## 4. Fase 4 — Completar compra

**Objetivo:** persistir el pedido y vaciar el carrito (sin pasarela de pagos).

### Clase `Compra`

Archivo: `sitio/clases/Compra.php`.

| Método | Rol |
|--------|-----|
| `crearDesdeCarrito($usuarioId, $carrito)` | Transacción + inserts + vaciar |
| `porId($id)` | Cabecera + líneas (usado en Fase 5) |
| `porUsuario($usuarioId)` | Listado de compras del usuario |

### Transacción PDO

```text
beginTransaction
  → validar ítems (carrito no vacío)
  → por cada ítem: Producto::porId()  // precio desde DB
  → INSERT compras (usuario_fk, total)
  → INSERT compras_tienen_productos (...)
commit
vaciar carrito
```

Si falla (carrito vacío, producto inexistente, error SQL): `rollBack` y el carrito **no** se vacía.

### Front controller y UI

- POST `accion=completar-compra` en `sitio/index.php` (solo logueados).
- Botón “Completar compra” en `sitio/vistas/carrito.php`.
- Éxito / error vía flash en la vista carrito (sin vista `compra-ok.php`).

---

## 5. Fase 5 — Admin: usuarios e historial

**Objetivo:** en el panel admin, listar usuarios y ver el detalle con historial de compras (fecha, total, productos).

Cumple las secciones 5–6 del Admin en la consigna: lista de usuarios + detalle con historial.

### Whitelist del Admin

En `sitio/admin/index.php`:

```php
$seccionesPermitidas = [
    'ingresar',
    'productos',
    'producto-alta',
    'producto-editar',
    'producto-borrar',
    'usuarios',
    'usuario-detalle',
];
```

Solo un usuario con `esAdmin()` entra a `usuarios` / `usuario-detalle` (el guard de Fase 2 ya protege todo lo que no sea `ingresar`).

### `Usuario::todos()`

Listado para el panel. Query fija (sin datos de usuario en el SQL) + `PDO::FETCH_CLASS`:

```php
public function todos(): array
{
    $db = (new DBConexion)->getConexion();
    $stmt = $db->query(
        'SELECT usuario_id, email, password, nombre, apellido, rol
         FROM usuarios
         ORDER BY usuario_id ASC'
    );
    $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
    return $stmt->fetchAll();
}
```

En la vista **no se muestra** el password (solo ID, nombre, apellido, email, rol).

### Flujo listado → detalle → historial

```text
Admin logueado
  └─ ?seccion=usuarios
        └─ Usuario::todos()  → tabla + link “Ver detalle”
              └─ ?seccion=usuario-detalle&id=N
                    ├─ Usuario::porId(N)     → datos de la cuenta
                    ├─ Compra::porUsuario(N) → cabeceras (fecha, total)
                    └─ Compra::porId(id)     → líneas (nombre, cantidad, precio_unitario)
```

### Por qué dos métodos de `Compra`

| Método | Qué trae | Para qué |
|--------|----------|----------|
| `porUsuario($id)` | Solo cabeceras | Listar compras del usuario |
| `porId($compraId)` | Cabecera + productos (JOIN) | Mostrar el detalle de cada compra |

Separar listado y detalle evita un JOIN grande innecesario cuando solo necesitamos las fechas/totales; al pintar cada compra pedimos sus líneas.

Ejemplo mínimo en la vista:

```php
$id = (int) ($_GET['id'] ?? 0);
$usuario = (new Usuario)->porId($id);
$compras = (new Compra)->porUsuario($id);

foreach ($compras as $cabecera) {
    $detalle = (new Compra)->porId((int) $cabecera['compra_id']);
    // $detalle['productos'] = nombre, cantidad, precio_unitario
}
```

Si `$id` es 0 o el usuario no existe: mensaje de error y link al listado (sin fatal error).

### Vistas y nav

- `sitio/admin/vistas/usuarios.php` — tabla de usuarios.
- `sitio/admin/vistas/usuario-detalle.php` — datos + historial (tablas anidadas).
- Topbar: links **Productos** | **Usuarios** (también en alta/editar de productos).

Estilos reutilizan `productos.css` (mismo look del ABM).

### Escape XSS (igual que el resto)

```php
htmlspecialchars($usuario->getEmail(), ENT_QUOTES, 'UTF-8');
$id = (int) ($_GET['id'] ?? 0);
```

---

## 6. Fase 6 — Pulido y entrega

**Objetivo:** checklist final vs consigna, QA E2E y paquete de entrega.

### JavaScript: `const` / `let` (nunca `var`)

En `sitio/vistas/detalle.php` el stepper de cantidad quedó así:

```js
const form = document.querySelector('[data-qty-form]');
const input = form.querySelector('.qty-stepper__value');
const btnDec = form.querySelector('[data-qty-dec]');
const btnInc = form.querySelector('[data-qty-inc]');

function getCantidad() {
  const n = parseInt(input.value, 10);
  return Number.isFinite(n) && n >= 1 ? n : 1;
}
```

`const` por defecto; `let` solo si la variable se reasigna. Los PHPDoc `/** @var ... */` en PHP **no** son JavaScript.

### Nav condicional del Sitio

En `sitio/includes/header.php`:

| Estado | Qué muestra |
|--------|-------------|
| No autenticado | Registro + Iniciar sesión |
| Autenticado | Perfil + Cerrar sesión + Carrito |
| Admin | + link al panel |

El carrito **no** se muestra sin sesión (cumple checklist RULES).

### Entrega

- `datos.txt`: carácter **final** + credenciales admin y usuario común de prueba.
- `der/dw3_kuringhian_garcia.png`: DER con `rol`, `compras`, `compras_tienen_productos`.
- `db/dw3_kuringhian_garcia.sql`: re-exportado e importable.
- Zip: `Kuringhian_Garcia.zip` = `sitio/` + `db/` + `der/` + `datos.txt`.

### QA

Veredicto y matriz completa: `docs/QA-FINAL.md` (23/23 PASS).

---

## 7. Flujo end-to-end (Fases 2–6)

```text
Visitante
  └─ Registro / Login ──► $_SESSION (id, email, rol)

Usuario autenticado
  └─ Detalle ──POST agregar──► $_SESSION['carrito']
        └─ Carrito
              ├─ quitar ──► sesión
              └─ Completar compra
                    ├─ INSERT compras + detalle (transacción)
                    ├─ vaciar carrito
                    └─ flash OK

Admin (rol admin)
  └─ Panel
        ├─ ABM productos
        └─ Usuarios → Detalle → historial (compras + líneas)
```

---

## 8. Escape XSS

En vistas, datos dinámicos:

```php
htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
```

IDs desde `$_GET` / `$_POST` se castean a `(int)`. Las contraseñas no se repueblan ni se muestran.

---

## 9. Archivos por fase

| Fase | Archivos clave |
|------|----------------|
| 1 | `db/dw3_kuringhian_garcia.sql`, `der/` |
| 2 | `Usuario.php`, `index.php`, `header.php`, `registro.php`, `iniciar-sesion.php`, `perfil.php`, `admin/index.php`, `cuenta.css` |
| 3 | `Carrito.php`, `detalle.php`, `carrito.php`, `header.php` (badge), `carrito.css` |
| 4 | `Compra.php`, `index.php` (POST completar), `carrito.php` (botón), `carrito.css` |
| 5 | `Usuario.php` (`todos`), `admin/index.php` (whitelist), `usuarios.php`, `usuario-detalle.php`, nav + CSS admin |
| 6 | `detalle.php` (JS), `header.php` + `base.css`, `datos.txt`, `der/`, `db/*.sql`, `Kuringhian_Garcia.zip`, `docs/QA-FINAL.md` |

---

## 10. Ideas para la defensa oral

1. Visitante sin cuenta ≠ tercer rol; solo ausencia de sesión.
2. Autenticación (`estaLogueado`) ≠ autorización (`esAdmin`).
3. Carrito en sesión vs compra en MySQL: temporal vs historial.
4. Al comprar se re-lee el precio desde DB y se guarda en `precio_unitario`.
5. Transacción PDO: o se guarda todo el pedido, o nada (`rollBack`).
6. Consultas con placeholders; SQL dentro de métodos de clase.
7. Front controller + whitelist evita cargar archivos arbitrarios vía `?seccion=`.
8. Contraseñas hasheadas; la sesión no guarda el password.
9. Historial admin: `porUsuario` lista cabeceras; `porId` trae los productos de cada compra (consultas en la clase `Compra`, no en la vista).
10. Por qué en JS usamos `const`/`let` y no `var` (regla del equipo / scope).
