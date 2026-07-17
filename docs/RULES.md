# RULES.md — Final Programación II (Galmir)

Guía justificada para completar el final a partir del 2do parcial.  
Fuentes auditadas: consigna (`docs/Programación II - Final.pdf`), proyecto del docente (`proyecto/`) y proyecto propio (`sitio/`).

---

## 1. Decisiones fijadas

| Decisión | Elección | Justificación |
|----------|----------|---------------|
| Carrito | `$_SESSION` mientras el usuario navega; al comprar → MySQL y vaciar sesión | Cumple la consigna (carrito autenticado + guardar detalle + vaciar) sin tablas de carrito; patrón simple de cursada |
| Roles | Columna `rol` en `usuarios` (`comun` / `admin`) | Cumple “al menos 2 roles” con mínimo cambio de schema; evita una tabla `roles` innecesaria |
| JS | Solo `let` y `const`; **nunca** `var` | Preferencia del equipo; el docente no usa JS, no hay conflicto de estilo |
| Alcance de este doc | Guía de implementación; el código se hace después | Separar auditoría/reglas de la ejecución reduce errores y sobreingeniería |

---

## 2. Auditoría de fuentes

### 2.1 Consigna (qué exige el final)

**Obligatorio a alto nivel**

- Web pública (“el Sitio”) + panel (“el Admin”).
- Al menos 2 roles: común y administrador.
- Continuación del 2do TP (corregir lo indicado y ampliar).

**Sitio — funcionalidades**

- Registro y autenticación de usuarios comunes.
- Carrito funcional **solo para usuarios autenticados**.
- Proceso de compra que: guarde detalles de productos adquiridos y vacíe el carrito (sin pasarela de pagos).

**Sitio — secciones**

| # | Sección | Visibilidad |
|---|---------|-------------|
| 1 | Home | Todos |
| 2 | Listado | Todos (ítems desde DB; click → detalle) |
| 3 | Detalle | Todos (agregar al carrito) |
| 4 | Contacto | Todos (form conceptual; no hace falta enviar email) |
| 5 | Registro | Solo no autenticados |
| 6 | Iniciar sesión | Solo no autenticados |
| 7 | Perfil | Solo autenticados |
| 8 | Carrito | Autenticados (quitar ítems + completar compra) |

**Admin — secciones**

| # | Sección | Estado esperado |
|---|---------|-----------------|
| 1 | Login (email + password admin) | Obligatorio |
| 2 | ABM de ítems (listado + alta/editar/eliminar) | Obligatorio |
| 3 | Formulario de alta | Obligatorio |
| 4 | Formulario de edición (pre-poblado) | Obligatorio |
| 5 | Lista de usuarios | Obligatorio |
| 6 | Detalle de usuario + historial de compras | Obligatorio |

**PHP (indispensables para aprobar)**

- Carga dinámica de secciones vía query string (`GET`), como en cursada.
- OOP mínimo para: ítem (producto), usuarios, conexión DB, autenticación, **carrito**.
- MySQL/MariaDB + **PDO**; consultas dentro de métodos de clase (estilo `Noticia` del docente).
- Placeholders/tokens en consultas con datos de usuario (`$_GET` / `$_POST`). Concatenar valores en el SQL = desaprobación.
- Rutas relativas o absolutas calculadas con `__DIR__`. Prohibido hardcodear paths de PC o `localhost/...`.

**Base de datos**

- Nombre: `dw3_apellido1_apellido2` → ya: `dw3_kuringhian_garcia`.
- DER + SQL exportado con datos reales (no Lorem ipsum).
- Normalizada.
- Tablas mínimas: usuarios, ítems (≥5 campos), al menos una tabla adicional relacionada a ítems, **compras**.

**Entrega**

- Zip con: `sitio/`, `db/`, `der/`, `datos.txt` (carácter **final** + credenciales admin).
- Nombre del archivo según integrantes (ver PDF).

### 2.2 Proyecto del docente (`proyecto/`)

**Qué aporta (usar como referencia de patrones)**

- Front controller + whitelist de secciones (`$listaRutas` / `$seccion` + `require` de vista).
- Capas: `clases/`, `vistas/`, `admin/`, `db/`.
- `DBConexion` con PDO y constantes de clase.
- Modelo estilo Active Record: `Noticia::todas()`, `porId()`, `PDO::FETCH_CLASS`, prepared statements con `:id`.
- Nombres en español, tipado PHP, sin `?>` final en archivos solo-PHP.
- Rutas con `__DIR__`.

**Qué NO aporta (no es el blueprint del e-commerce)**

- No hay JavaScript.
- No hay `session_start` / auth real.
- Login, registro y CRUD admin están en stub / “Coming soon”.
- No hay carrito, compras ni roles.
- Schema de noticias/equipos NBA; dominio distinto al nuestro.

**Conclusión:** el proyecto docente valida *cómo* escribir PHP en la materia. El *qué* del final lo define la consigna + nuestro dominio Galmir.

### 2.3 Proyecto propio (`sitio/` — Galmir)

**Ya implementado (aprox. 60% del final)**

| Pieza | Ubicación |
|-------|-----------|
| Front controller público | `sitio/index.php` |
| Home, listado, detalle, contacto, 404 | `sitio/vistas/` |
| Layout header/footer | `sitio/includes/` |
| Admin front controller + sesión | `sitio/admin/index.php` |
| Login admin + bcrypt | `sitio/clases/Usuario.php`, `admin/vistas/ingresar.php` |
| ABM productos (alta/editar/borrar) | `admin/vistas/producto-*.php` |
| PDO + `Producto` / `DBConexion` | `sitio/clases/` |
| Escape XSS habitual | `htmlspecialchars` en vistas |
| DB + DER + seed productos/categorías | `db/`, `der/` |
| Categorías N:M (tabla adicional) | `categorias`, `productos_tienen_categorias` |

**Gaps críticos vs consigna**

| Gap | Impacto |
|-----|---------|
| Sin columna / lógica de `rol` | No hay 2 roles |
| Sin registro / login / perfil en el Sitio | Secciones 5–7 faltan |
| Sin `session_start` en el front | No hay auth pública ni carrito |
| Botones carrito/favoritos con `href="#"` | Carrito no funcional |
| Sin tablas / clases de compras | Requisito DB + proceso de compra |
| Admin sin lista/detalle de usuarios | Secciones admin 5–6 |
| JS con `var` en 3 archivos | Incumple regla del equipo |
| `datos.txt` dice “2do Parcial” | Debe decir final al entregar |

---

## 3. Matriz consigna → estado → acción

| Requisito | Estado | Acción |
|-----------|--------|--------|
| Sitio público | Parcial | Ampliar auth, carrito, compra, nav condicional |
| Panel admin | Parcial | Agregar usuarios + historial; restringir login a `rol=admin` |
| 2 roles | Falta | Columna `rol` + guards |
| Home / Listado / Detalle / Contacto | OK | Mantener; en detalle: POST agregar al carrito |
| Registro / Iniciar sesión / Perfil | Falta | Vistas + métodos en `Usuario` |
| Carrito + compra | Falta | Clase `Carrito` (sesión) + `Compra` (MySQL) |
| Admin login | OK (cualquier logueado) | Exigir `esAdmin()` |
| ABM ítems | OK | No reescribir |
| Lista / detalle usuarios | Falta | Vistas admin + consultas |
| OOP producto/usuario/DB/auth | OK | Ampliar auth; agregar Carrito (+ Compra) |
| PDO + placeholders | OK | Mantener en todo lo nuevo |
| Rutas relativas / `__DIR__` | OK | Mantener |
| DB nombre + DER + SQL | Parcial | Extender schema, re-exportar, actualizar DER |
| Tabla compras | Falta | Crear + seed |
| HTML semántico + CSS propio | OK | Reutilizar estilos; extender lo mínimo |
| Entrega zip + datos.txt | Preparar al final | Actualizar carácter a `final` |

---

## 4. Reglas de código (estilo cursada, sin complicar)

### 4.1 Arquitectura

1. **Front controller** por área (`sitio/index.php`, `sitio/admin/index.php`).
2. **Whitelist** de secciones; si no está → `404`.
3. Secciones vía `?seccion=nombre` (y `id` u otros params cuando haga falta).
4. Vistas en `vistas/`; clases en `clases/`; CSS en `css/`.
5. Consultas SQL **solo** dentro de métodos de clases.
6. No inventar frameworks, routers ni autoload obligatorio (el docente deja TODO de autoload; nosotros seguimos con `require_once`).

### 4.2 PHP — sintaxis y patrones

```php
// Whitelist (patrón ya usado en Galmir)
$seccionesPermitidas = ['home', 'listado', /* ... */];
$seccionActual = $_GET['seccion'] ?? 'home';
if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

// PDO + placeholder (estilo Noticia / Producto)
$db = (new DBConexion)->getConexion();
$stmt = $db->prepare('SELECT * FROM productos WHERE producto_id = :id');
$stmt->execute(['id' => $id]);
$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
```

- Tipado en propiedades y métodos cuando ya lo hacemos (`int`, `string`, `?self`, `array`).
- Constantes de clase para claves de sesión / roles (`Usuario::ROL_ADMIN`, `SESSION_KEY_*`).
- Getters; métodos de lectura/escritura en la propia clase entidad.
- `password_hash` / `password_verify` (nunca texto plano).
- Salida HTML: `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')`.
- Archivos solo-PHP: sin cierre `?>`.
- Comentarios: cortos y útiles para la defensa oral. **No** copiar los ensayos didácticos largos del docente.

### 4.3 JavaScript

- **Prohibido** `var`.
- Usar `const` por defecto; `let` solo si la variable se reasigna.
- Migrar los inline actuales:
  - `sitio/vistas/contacto.php`
  - `sitio/admin/vistas/ingresar.php`
  - `sitio/admin/vistas/productos.php`
- No hace falta archivos `.js` externos; inline está bien si queda claro.

```js
// Antes (incorrecto para este proyecto)
var form = document.querySelector('.contact-form');

// Después
const form = document.querySelector('.contact-form');
```

### 4.4 Rutas

```text
Correcto:  imgs/teg.webp
Correcto:  __DIR__ . '/vistas/' . $seccion . '.php'
Incorrecto: C:\Users\... o http://localhost/davinci/...
```

### 4.5 Qué no hacer (anti-sobreingeniería)

- Sin pasarela de pagos.
- Sin tablas `carritos` en MySQL (el carrito vive en sesión).
- Sin tabla `roles` separada.
- Sin frameworks JS; CSS propio existente alcanza.
- No reescribir el ABM de productos ni copiar el tema “Saraza Basket”.
- No agregar favoritos, stock, ni emails reales de contacto (fuera de consigna).

---

## 5. Modelo de datos objetivo

Partir de `db/dw3_kuringhian_garcia.sql`.

### 5.1 Cambios en `usuarios`

```sql
ALTER TABLE usuarios
  ADD COLUMN rol ENUM('comun', 'admin') NOT NULL DEFAULT 'comun'
  AFTER apellido;

-- Seed: el usuario admin existente debe quedar con rol = 'admin'
UPDATE usuarios SET rol = 'admin' WHERE email = 'admin@galmir.local';
```

### 5.2 Tablas nuevas: compras

```sql
CREATE TABLE compras (
  compra_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_fk INT UNSIGNED NOT NULL,
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (compra_id),
  KEY fk_compras_usuario (usuario_fk),
  CONSTRAINT fk_compras_usuario
    FOREIGN KEY (usuario_fk) REFERENCES usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE compras_tienen_productos (
  compra_fk INT UNSIGNED NOT NULL,
  producto_fk INT UNSIGNED NOT NULL,
  cantidad INT UNSIGNED NOT NULL DEFAULT 1,
  precio_unitario DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (compra_fk, producto_fk),
  KEY fk_ctp_producto (producto_fk),
  CONSTRAINT fk_ctp_compra
    FOREIGN KEY (compra_fk) REFERENCES compras (compra_id) ON DELETE CASCADE,
  CONSTRAINT fk_ctp_producto
    FOREIGN KEY (producto_fk) REFERENCES productos (producto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Por qué `precio_unitario`:** guarda el precio al momento de la compra (historial real si mañana cambia el precio del producto).

### 5.3 Relación (DER a actualizar)

```text
usuarios 1──< productos          (usuario_fk = quien lo cargó en admin)
usuarios 1──< compras
productos N──M categorias        (productos_tienen_categorias)  ← ya existe
compras   N──M productos         (compras_tienen_productos)     ← nuevo
```

### 5.4 Seed mínimo recomendado

- ≥ 1 usuario `comun` de prueba (email/password documentados en notas internas o en `datos.txt` si se desea).
- ≥ 1 compra de ejemplo vinculada a ese usuario (para demostrar admin → detalle → historial sin comprar en la defensa).
- Productos/categorías actuales se mantienen (datos reales, no Lorem).

### 5.5 Entrega DB

- Re-exportar dump completo a `db/dw3_kuringhian_garcia.sql`.
- Actualizar imagen DER en `der/`.
- En `datos.txt`: carácter de entrega = **final** (hoy dice “2do Parcial”).

---

## 6. Flujo carrito / compra (sesión)

```text
[Detalle] --POST agregar--> [$_SESSION carrito]
                                 |
                                 v
                          [Vista Carrito]
                           | quitar | completar
                           v        v
                      (sesión)   INSERT compras + detalle
                                        |
                                        v
                                 Vaciar $_SESSION
```

**Reglas de negocio**

1. Agregar / ver / completar carrito: solo si `Usuario::estaLogueado()`.
2. Si no está logueado e intenta agregar: redirigir a `iniciar-sesion` (mensaje claro).
3. Admin del panel: login solo si `rol === 'admin'` (`Usuario::esAdmin()`).
4. Un admin también puede comprar en el Sitio si está logueado ahí (mismo sistema de sesión o sesiones separadas front/admin: preferir **una sesión PHP** compartida con cuidado de no mezclar guards; ver Fase 2).
5. Al completar compra: transacción PDO (`beginTransaction` / `commit` / `rollBack`).

**Estructura sugerida en sesión**

```php
// $_SESSION['carrito'] = [
//   producto_id => ['cantidad' => int, 'nombre' => string, 'precio' => float],
// ];
```

Guardar nombre/precio en sesión evita consultas extra al listar; al comprar, **re-leer precio desde DB** (fuente de verdad) y persistir ese valor en `precio_unitario`.

---

## 7. Clases OOP — contrato mínimo

| Clase | Responsabilidad | Métodos orientativos |
|-------|-----------------|----------------------|
| `DBConexion` | PDO | `getConexion(): PDO` (ya existe) |
| `Producto` | CRUD ítems + listados | `todas()`, `porId()`, crear/actualizar/borrar (ya existe) |
| `Usuario` | Auth + roles + registro | `porEmail()`, `verificarCredenciales()`, `registrar()`, `iniciarSesion()`, `cerrarSesion()`, `estaLogueado()`, `esAdmin()`, `porId()`, `todos()` |
| `Carrito` | Manipular `$_SESSION` | `agregar()`, `quitar()`, `obtenerItems()`, `vaciar()`, `calcularTotal()`, `cantidadItems()` |
| `Compra` | Persistencia de pedidos | `crearDesdeCarrito(usuarioId, Carrito)`, `porUsuario(id)`, `porId(id)` |

Consultas con datos de usuario → siempre `:placeholders`.

---

## 8. ROADMAP de implementación

Orden obligatorio: cada fase asume la anterior terminada.

### Fase 1 — Base de datos y roles

**Objetivo:** schema listo para auth, carrito persistido como compra, y admin de usuarios.

**Archivos**

- `db/dw3_kuringhian_garcia.sql`
- `der/dw3_kuringhian_garcia.png` (o el archivo DER actual)
- `datos.txt` (actualizar carácter a final cuando se entregue; puede hacerse en Fase 6)

**Pasos**

1. Agregar `usuarios.rol`.
2. Crear `compras` y `compras_tienen_productos`.
3. Seed: admin con `rol=admin`, usuario común, ≥1 compra.
4. Re-exportar SQL + actualizar DER.

**Done cuando:** importás el SQL en un MySQL limpio y las tablas/datos existen sin error.

---

### Fase 2 — Auth pública y roles en sesión

**Objetivo:** registro, login, perfil, logout en el Sitio; admin solo para `admin`.

**Archivos**

- `sitio/clases/Usuario.php`
- `sitio/index.php` → `session_start()` + nuevas secciones en whitelist
- `sitio/includes/header.php` → nav condicional
- Nuevas vistas: `registro.php`, `iniciar-sesion.php`, `perfil.php` (+ manejar `salir`)
- `sitio/admin/index.php` → tras login, exigir `esAdmin()`

**Sintaxis / patrón**

```php
public const ROL_COMUN = 'comun';
public const ROL_ADMIN = 'admin';
public const SESSION_KEY_ROL = 'usuario_rol';

public function registrar(string $email, string $password, string $nombre, string $apellido): self
{
    // INSERT con :email, :password (password_hash), :nombre, :apellido, rol = comun
}

public static function esAdmin(): bool
{
    return isset($_SESSION[self::SESSION_KEY_ROL])
        && $_SESSION[self::SESSION_KEY_ROL] === self::ROL_ADMIN;
}
```

**Whitelist Sitio (objetivo)**

```php
$seccionesPermitidas = [
    'home', 'listado', 'detalle', 'contacto',
    'registro', 'iniciar-sesion', 'perfil', 'carrito',
];
// 'salir' como caso especial (igual que admin), no hace falta vista
```

**Guards de vista**

- `registro` / `iniciar-sesion`: si ya logueado → redirect `perfil` o `home`.
- `perfil` / `carrito`: si no logueado → redirect `iniciar-sesion`.

**Done cuando:** podés registrar un común, loguearte, ver perfil, cerrar sesión; un común **no** entra al admin; el admin sí.

---

### Fase 3 — Carrito OOP (sesión)

**Objetivo:** agregar desde detalle, listar, quitar, total.

**Archivos**

- `sitio/clases/Carrito.php` (nuevo)
- `sitio/vistas/detalle.php` → form POST agregar
- `sitio/vistas/carrito.php` (nuevo)
- `sitio/index.php` → sección `carrito`
- CSS mínimo para carrito (reutilizar botones/tablas existentes)

**Patrón**

```php
class Carrito
{
    public const SESSION_KEY = 'carrito';

    public function agregar(int $productoId, int $cantidad = 1): void { /* ... */ }
    public function quitar(int $productoId): void { /* ... */ }
    public function vaciar(): void { $_SESSION[self::SESSION_KEY] = []; }
    public function obtenerItems(): array { /* ... */ }
    public function calcularTotal(): float { /* ... */ }
}
```

**Done cuando:** usuario logueado agrega 2 productos, ve el carrito, quita uno, ve el total actualizado.

---

### Fase 4 — Completar compra

**Objetivo:** persistir compra + vaciar carrito (sin pagos).

**Archivos**

- `sitio/clases/Compra.php` (nuevo)
- `sitio/vistas/carrito.php` → botón “Completar compra”
- Opcional: vista `compra-ok.php` o mensaje flash en sesión

**Patrón (transacción)**

```php
$db->beginTransaction();
try {
    // INSERT compras (usuario_fk, total)
    // INSERT compras_tienen_productos por cada ítem (precio desde DB)
    $db->commit();
    $carrito->vaciar();
} catch (Throwable $e) {
    $db->rollBack();
    throw $e;
}
```

**Done cuando:** tras comprar, el carrito está vacío y en MySQL existen filas en `compras` + detalle; el usuario las ve en admin (Fase 5) o vía consulta.

---

### Fase 5 — Admin: usuarios e historial

**Objetivo:** lista de usuarios + detalle con compras.

**Archivos**

- `sitio/admin/index.php` → whitelist: `usuarios`, `usuario-detalle`
- `sitio/admin/vistas/usuarios.php`
- `sitio/admin/vistas/usuario-detalle.php`
- Métodos en `Usuario` / `Compra` (`todos()`, `porId()`, `porUsuario()`)
- Link en topbar/nav admin hacia Usuarios

**Done cuando:** desde admin ves todos los usuarios, entrás a uno y ves su historial (fecha, total, productos).

---

### Fase 6 — Pulido y entrega

**Checklist**

- [ ] Reemplazar todo `var` por `let`/`const` en JS inline.
- [ ] Nav Sitio: registro/login solo si no auth; perfil/carrito/salir si auth.
- [ ] Detalle: agregar al carrito funciona y valida login.
- [ ] Contacto: form conceptual OK (sin mail).
- [ ] Placeholders en **todas** las consultas nuevas.
- [ ] Sin rutas absolutas de máquina.
- [ ] `datos.txt` con carácter **final** + email/password admin.
- [ ] DER actualizado + SQL importable.
- [ ] Zip: `sitio/` + `db/` + `der/` + `datos.txt` con nombre de archivo según consigna.
- [ ] Probar flujo feliz end-to-end una vez en entorno limpio.

**Done cuando:** el checklist está completo y el flujo Home → Registro → Login → Detalle → Carrito → Compra → Admin Usuarios → Detalle historial funciona.

---

## 9. Mapa de archivos nuevos / tocados (vista rápida)

```text
sitio/
  index.php                          # session + whitelist ampliada
  includes/header.php                # nav condicional
  clases/
    Usuario.php                      # rol, registro, esAdmin, listados
    Carrito.php                      # NUEVO
    Compra.php                       # NUEVO
    Producto.php                     # sin cambios mayores
    DBConexion.php                   # sin cambios mayores
  vistas/
    detalle.php                      # form agregar carrito
    registro.php                     # NUEVO
    iniciar-sesion.php               # NUEVO
    perfil.php                       # NUEVO
    carrito.php                      # NUEVO
  admin/
    index.php                        # guard esAdmin + rutas usuarios
    vistas/
      usuarios.php                   # NUEVO
      usuario-detalle.php            # NUEVO
db/dw3_kuringhian_garcia.sql         # rol + compras + seed
der/                                 # DER actualizado
datos.txt                            # carácter final
RULES.md                             # este archivo
```

`proyecto/` del docente: **solo referencia**; no se entrega ni se modifica para el final.

---

## 10. Defensa oral — puntos a poder explicar

1. Por qué el carrito está en sesión y la compra en MySQL.
2. Por qué `rol` es columna y no tabla aparte.
3. Dónde están las consultas (métodos de clase) y por qué usamos placeholders.
4. Diferencia entre usuario `comun` y `admin` (guards Sitio vs Admin).
5. Qué hace la transacción al completar la compra.
6. Cómo el front controller + whitelist evita cargar archivos arbitrarios.

---

## 11. Resumen ejecutivo

| Pregunta | Respuesta |
|----------|-----------|
| ¿El proyecto del docente alcanza para el final? | **No** como producto; **sí** como guía de patrones PHP/PDO. |
| ¿Cuánto tenemos hecho? | Catálogo + admin ABM + login admin. Falta e-commerce (roles, auth pública, carrito, compras, admin usuarios). |
| ¿Cómo completamos? | Seguir el ROADMAP Fases 1→6 sin saltear ni sobrecomplicar. |
| ¿Regla de oro JS? | Solo `let` / `const`. |

Este documento es la fuente de verdad del equipo hasta cerrar el final.
`)