# RULES — Segundo Parcial (Galmir E-Commerce)

Guía de implementación para completar el **2.º parcial** de Programación II sobre la entrega del **1.er parcial**, siguiendo los patrones del docente en `Clase_Ejemplo_Parcial_2/proyecto/` (Saraza Basket) adaptados al e-commerce de juegos de mesa.

**Base de datos:** `dw3_kuringhian_garcia`  
**Entornos permitidos:** solo **XAMPP** y **MAMP** (macOS y Windows).

---

## 1. Contexto y referencias

| Recurso | Ubicación |
|---------|-----------|
| Consigna 1.er parcial | [Programación II - Primer Parcial.pdf](Programación%20II%20-%20Primer%20Parcial.pdf) |
| Consigna 2.º parcial | [Programación II - Segundo Parcial.pdf](Programación%20II%20-%20Segundo%20Parcial.pdf) |
| Proyecto entregado (1.er parcial) | Raíz del repo: `index.php`, `clases/Producto.php`, `vistas/`, `data/productos.json` |
| Ejemplo del docente (patrones) | `../Clase_Ejemplo_Parcial_2/proyecto/` |
| Conexión PDO de referencia | `../Clase_Ejemplo_Parcial_2/proyecto/clases/DBConexion.php` |
| Modelo con lectura BD | `../Clase_Ejemplo_Parcial_2/proyecto/clases/Noticia.php` |

### Qué copiar del docente

- Whitelist de rutas vía `?seccion=` en `index.php` y `admin/index.php`
- Clase `DBConexion` con PDO y constantes
- Consultas con `prepare()` + placeholders (`:id`) dentro de métodos de clase
- Diseño de BD normalizada con tabla relacionada N:M

### Qué NO copiar

- Tema NBA, textos, imágenes ni el SQL `prog2_2026_1_n`
- El ejemplo **incompleto** del docente como entrega final: no tiene login funcional ni ABM (alta/edición/baja)

### Equipo

- Milagros Sol Kuringhian / Gael Garcia Nuñez — Comisión DWN3AP

---

## 2. Checklist obligatorio (2.º parcial)

Marcar antes de subir al campus:

### Sitio público

- [ ] **Home** informativa para visitantes nuevos
- [ ] **Listado** de ítems desde **base de datos** (no JSON)
- [ ] **Detalle** por ítem (enlace con `?id=`)
- [ ] **Contacto** con formulario integrado al sitio (no exige envío de email ni guardar en BD)

### Panel de administración

- [ ] **Login** con email y password
- [ ] **ABM** — listado de ítems con acceso a alta, edición y baja
- [ ] **Formulario de alta** con campos necesarios → INSERT en BD
- [ ] **Formulario de edición** pre-poblado con datos actuales → UPDATE en BD

### PHP

- [ ] Carga dinámica de secciones por query string (`?seccion=`)
- [ ] POO: mínimo **Producto**, **Usuario**, **DBConexion**
- [ ] PDO para todas las interacciones con MySQL/MariaDB
- [ ] Consultas con datos de `$_GET` / `$_POST` solo con **placeholders** (nunca concatenar en el SQL)
- [ ] Rutas relativas o con `__DIR__` (sin `C:\Users\...` ni URLs fijas al proyecto local)

### Base de datos

- [ ] Nombre: `dw3_kuringhian_garcia`
- [ ] DER incluido en la entrega
- [ ] Archivo `.sql` exportado con **datos cargados**
- [ ] Datos reales (sin Lorem ipsum en productos visibles)
- [ ] Normalizada (1NF, 2NF, 3NF)
- [ ] Tabla **usuarios**
- [ ] Tabla **productos** con al menos **5 campos** de negocio
- [ ] Al menos **una tabla adicional** relacionada con productos

### Entrega

- [ ] Zip con carpetas `sitio/`, `db/`, `der/` y `datos.txt` en la raíz del zip
- [ ] `datos.txt` indica **2do parcial** + email/password del **admin del panel**
- [ ] Nombre del zip según consigna grupal: `kuringhian-milagros_garcia-gael.zip`

---

## 3. Matriz de brechas

| Requisito | Estado actual (1.er parcial) | Objetivo (2.º parcial) |
|-----------|------------------------------|-------------------------|
| Fuente de ítems | `data/productos.json` | Tabla `productos` vía PDO |
| `DBConexion` | No existe | `clases/DBConexion.php` |
| `Usuario` + sesión | No existe | Login admin + `session_start()` |
| Panel `admin/` | No existe | `admin/index.php` + vistas ABM |
| CRUD productos | Solo lectura JSON | `crear`, `actualizar`, `eliminar` en `Producto` |
| Tabla relacionada | No existe | `categorias` + `productos_tienen_categorias` |
| Estructura de entrega | Proyecto en raíz | `sitio/` + `db/` + `der/` en el zip |
| `datos.txt` | 1er parcial | 2do parcial + credenciales admin |
| Contacto en BD | No | Mantener formulario conceptual (sin persistencia) |
| Detalle | Descripción hardcodeada en un caso | Usar `getDescripcion()` desde BD |
| Escape HTML | Parcial | `htmlspecialchars()` en salidas dinámicas |
| Ejemplo docente | Solo lectura + admin sin ABM | Completar login y ABM vosotros |

---

## 4. Modelo de datos (`dw3_kuringhian_garcia`)

Equivalente conceptual al ejemplo NBA (`noticias`, `equipos`, `noticias_tienen_equipos`). Ver planteo en `../Clase_Ejemplo_Parcial_2/proyecto/db/Planteo de la base de datos.md`.

### Tablas

#### `usuarios`

| Campo | Tipo sugerido | Notas |
|-------|---------------|-------|
| `usuario_id` | INT UNSIGNED PK AI | |
| `email` | VARCHAR UNIQUE | Login del panel |
| `password` | VARCHAR | Texto plano aceptable en curso; `password_hash` como mejora opcional |
| `nombre` | VARCHAR NULL | |
| `apellido` | VARCHAR NULL | |

Seed: al menos un usuario administrador (credenciales en `datos.txt` de entrega).

#### `productos` (ítems — ≥5 campos)

Mapeo desde `data/productos.json`:

| Campo | Origen JSON / negocio |
|-------|------------------------|
| `producto_id` | `id` |
| `nombre` | `nombre` |
| `precio` | `precio` |
| `descripcion_corta` | `descripcion_corta` |
| `descripcion` | `descripcion` |
| `imagen` | `imagen` (ruta relativa, ej. `imgs/teg.webp`) |
| `usuario_fk` | FK → `usuarios` (quién dio de alta el producto) |
| `fecha_alta` | DATETIME (listados ordenados) |

#### `categorias` + `productos_tienen_categorias`

- Normaliza el string `categoria` del JSON (Estrategia, Clásico, Cartas, etc.)
- Relación **N:M** (un producto puede tener varias categorías; una categoría, muchos productos)
- Cumple “tabla adicional relacionada con ítems”

```
productos (1) ──< productos_tienen_categorias >── (N) categorias
usuarios (1) ──< productos
```

### Reglas de diseño

- Claves primarias y foráneas explícitas
- Sin grupos repetición (1NF); sin dependencias parciales (2NF); sin transitivas innecesarias (3NF)
- Índice en campo de consulta frecuente (`fecha_alta` DESC o `nombre`)
- Charset: `utf8mb4`

### Archivos a producir

| Archivo | Uso |
|---------|-----|
| `db/dw3_kuringhian_garcia.sql` | Importación en phpMyAdmin / mysql CLI |
| `der/dw3_kuringhian_garcia.png` | Diagrama para la entrega (Workbench, phpMyAdmin Designer, etc.) |
| Opcional en repo | `db/Planteo-base-de-datos.md` con entidades y NF |

---

## 5. Convenciones de código

### Rutas y archivos

- Imágenes y assets: rutas relativas al sitio (`imgs/producto.webp`)
- Includes: `__DIR__ . '/../clases/Producto.php'`
- Prohibido: `C:\Users\...`, `/Users/...`, `http://localhost/mi-carpeta-fija/...`

### Routing (como el docente)

```php
$listaRutas = [
    'home' => ['title' => '...'],
    'listado' => ['title' => '...'],
    // ...
];
$seccion = $_GET['seccion'] ?? 'home';
if (!isset($listaRutas[$seccion])) {
    $seccion = '404';
}
require __DIR__ . '/vistas/' . $seccion . '.php';
```

### PDO en clases (como `Noticia::porId`)

```php
$consulta = "SELECT * FROM productos WHERE producto_id = :id";
$stmt = $db->prepare($consulta);
$stmt->execute(['id' => $id]);
$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
```

**Prohibido:**

```php
// INSEGURO — desaprueba el TP
$consulta = "SELECT * FROM productos WHERE producto_id = " . $_GET['id'];
```

### Nombres para `FETCH_CLASS`

Propiedades privadas en **snake_case** alineadas a columnas: `$producto_id`, `$descripcion_corta`.

### Vistas

- Lógica PHP al inicio del archivo
- Layout solo en `includes/header.php` y `footer.php` (público) o en `admin/index.php` (admin)
- Desde `admin/vistas/`: `require_once __DIR__ . '/../../clases/Producto.php';`

### Salida HTML

```php
<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>
```

### Admin — guard de sesión

Al inicio de `admin/index.php` (antes del HTML):

- Si `seccion !== 'ingresar'` y no hay sesión de admin → redirigir a `?seccion=ingresar`

---

## 6. Mapa de archivos objetivo (`sitio/`)

Al empaquetar, el código ejecutable va dentro de `sitio/`:

```
sitio/
├── index.php
├── clases/
│   ├── DBConexion.php
│   ├── Producto.php      # todas, porId, crear, actualizar, eliminar
│   └── Usuario.php       # porEmail, verificar credenciales, etc.
├── admin/
│   ├── index.php
│   └── vistas/
│       ├── ingresar.php
│       ├── productos.php
│       ├── producto-alta.php
│       ├── producto-editar.php
│       └── 404.php
├── vistas/
│   ├── home.php
│   ├── listado.php
│   ├── detalle.php
│   ├── contacto.php
│   └── 404.php
├── includes/
│   ├── header.php
│   └── footer.php
├── css/
├── imgs/
└── data/
    └── productos.json    # opcional: backup / referencia de migración
```

### Whitelist admin sugerida

`ingresar`, `productos`, `producto-alta`, `producto-editar`, `producto-borrar` (POST o acción en listado), `404`

### Whitelist público (mantener)

`home`, `listado`, `detalle`, `contacto`, `404`

---

## 7. Modalidad de entrega (zip)

```
kuringhian-milagros_garcia-gael.zip
├── sitio/                              # proyecto PHP completo
├── db/
│   └── dw3_kuringhian_garcia.sql
├── der/
│   └── dw3_kuringhian_garcia.png
└── datos.txt                           # en la raíz del zip, NO dentro de sitio/
```

### Plantilla `datos.txt`

```
Carrera: Diseño Web y Programación
Materia: Programación II
Cuatrimestre: 3er Cuatrimestre
Año: 2026
Turno: Noche
Comisión: DWN3AP
Apellido y nombre: Milagros Sol Kuringhian / Gael Garcia Nuñez
Docente: Santiago Gallino
Carácter de la entrega: 2do Parcial

Email administrador: admin@galmir.local
Password administrador: (la que definan en la tabla usuarios para el panel)
```

Las credenciales son las del **panel web**, no las de MySQL `root`.

---

## 8. Plan de implementación por fases

1. **Base de datos** — Crear tablas, seed desde `productos.json`, relación con `categorias`, exportar `.sql` y DER.
2. **`DBConexion`** — Perfil XAMPP o MAMP activo; probar con `test-db.php` (sección 11).
3. **`Producto` lectura** — `todas()` y `porId()` con PDO; actualizar `listado` y `detalle`; corregir descripción en detalle.
4. **`Usuario` + login** — Seed admin; `session_start`; formulario POST en `admin/vistas/ingresar.php`; proteger rutas admin.
5. **ABM admin** — Listado, alta, edición pre-poblada, baja; INSERT/UPDATE/DELETE con placeholders.
6. **Pulido** — `htmlspecialchars`, probar `id` inválido, opcional filtro por `q` en listado.
7. **Empaquetado** — Copiar a `sitio/`, `db/`, `der/`, actualizar `datos.txt`, eliminar `test-db.php`, generar zip.

---

## 9. Anti-patrones (desaprueban el TP)

- Concatenar `$_GET` / `$_POST` dentro del SQL
- Copiar el proyecto Saraza Basket o importar `prog2_2026_1_n` como vuestra base
- Lorem ipsum en productos del sitio público
- Panel admin sin login
- Rutas absolutas de disco o URL fija al TP en la PC de un integrante
- Entregar solo la carpeta del 1.er parcial sin `sitio/db/der`
- Mezclar perfil XAMPP (host `:3306`) con password de MAMP (`root`)
- Subir `test-db.php` en el zip final
- Usar Laragon, Homebrew o MySQL Installer en la documentación de entrega (este TP usa **solo MAMP y XAMPP**)

---

## 10. Equivalencias docente (NBA) → Galmir (e-commerce)

| Docente | Galmir |
|---------|--------|
| `Noticia` | `Producto` |
| `noticias` | `productos` |
| `equipos` | `categorias` |
| `noticias_tienen_equipos` | `productos_tienen_categorias` |
| `noticias` (vista listado) | `listado` |
| `noticias-leer` | `detalle` (`?seccion=detalle&id=`) |
| `admin/vistas/noticias.php` | `admin/vistas/productos.php` |
| `ingresar` / `crear-cuenta` (público) | Solo login admin obligatorio en 2.º parcial |
| `DBConexion` + `Usuario` | Igual concepto; `Usuario` debe implementarse completo |

---

## 11. Guía de entorno: MySQL con MAMP o XAMPP (macOS y Windows)

**No usar** Laragon, Homebrew ni MySQL Installer para este TP. Solo **dos perfiles** de constantes en `DBConexion.php`.

### 11.1 Referencia común

| Parámetro | Ejemplo docente | Galmir |
|-----------|-----------------|--------|
| Motor | MySQL / MariaDB | Igual |
| Host | `127.0.0.1` + puerto en `DB_HOST` | Igual |
| Usuario | `root` | `root` |
| Contraseña | XAMPP: vacía · MAMP: `root` | Ver 11.7 |
| Base | `prog2_2026_1_n` | **`dw3_kuringhian_garcia`** |
| Cliente | PDO | PDO |
| Charset DSN | `utf8mb4` | `utf8mb4` |

### 11.2 Stack permitido

| Stack | macOS | Windows | phpMyAdmin | Puerto MySQL típico | `DB_PASS` en PHP |
|-------|-------|---------|------------|----------------------|------------------|
| **XAMPP** | Sí | Sí | Sí | **3306** | `""` |
| **MAMP** | Sí | Sí | Sí | **8889** (Mac) · verificar en MAMP Win | `root` |

Cada integrante elige **uno**; el grupo comparte el mismo `.sql` y `DB_NAME`.

### 11.3 macOS — MAMP o XAMPP

#### MAMP

1. Instalar desde [mamp.info](https://www.mamp.info) → **Start** (Apache + MySQL en verde).
2. **MAMP → Preferencias → Ports** → anotar puerto MySQL (**8889** por defecto en Mac).
3. phpMyAdmin: **WebStart** o `http://localhost:8888/phpMyAdmin`.
4. Activar perfil **MAMP** en `DBConexion.php` (sección 11.7).

#### XAMPP

1. Instalar XAMPP para Mac → **Start** en MySQL.
2. Puerto MySQL: **3306**.
3. phpMyAdmin: `http://localhost/phpmyadmin`.
4. Activar perfil **XAMPP** en `DBConexion.php`.

### 11.4 Windows — XAMPP o MAMP

#### XAMPP (recomendado)

1. Instalar en `C:\xampp` (ruta sin espacios ni tildes).
2. **XAMPP Control Panel** → **Start** en Apache y MySQL.
3. phpMyAdmin: `http://localhost/phpmyadmin`.
4. `root` sin contraseña → perfil **XAMPP**.

```cmd
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SHOW DATABASES;"
```

#### MAMP para Windows

1. Instalar MAMP → **Start Servers**.
2. **Preferences → Ports** → anotar puerto MySQL (puede ser 3306 u 8889).
3. phpMyAdmin desde el panel MAMP.
4. Perfil **MAMP** (`DB_PASS` = `root`).

```cmd
"C:\MAMP\bin\mysql\bin\mysql.exe" -u root -proot -e "SHOW DATABASES;"
```

(Ajustar `-p` según la contraseña configurada en MAMP.)

### 11.5 Verificar PHP y `pdo_mysql`

#### macOS

```bash
php -v
php -m | grep -i pdo_mysql
which php
```

Si usan **MAMP**, verificar con el PHP de MAMP (el del sistema puede no tener el mismo `php.ini`):

```bash
/Applications/MAMP/bin/php/php8.3.14/bin/php -v
/Applications/MAMP/bin/php/php8.3.14/bin/php -m | grep -i pdo_mysql
```

Editar el `php.ini` de **ese** binario; descomentar `extension=pdo_mysql`; reiniciar Apache en MAMP/XAMPP.

#### Windows

```cmd
php -v
php -m | findstr /i pdo_mysql
where php
```

XAMPP:

```cmd
C:\xampp\php\php.exe -m | findstr pdo_mysql
```

Editar `C:\xampp\php\php.ini` (o `C:\MAMP\bin\php\php8.x.x\php.ini`) → habilitar `extension=pdo_mysql` → reiniciar Apache.

**Criterio:** debe listarse `pdo_mysql`. Si no, aparece `could not find driver`.

### 11.6 Importar `dw3_kuringhian_garcia.sql`

Orden: **importar BD → configurar `DBConexion` → probar `test-db.php`**.

#### Opción A — phpMyAdmin (Mac y Windows)

1. Abrir phpMyAdmin (URL de MAMP o XAMPP).
2. **Importar** → elegir `db/dw3_kuringhian_garcia.sql`.
3. Verificar tablas: `usuarios`, `productos`, `categorias`, `productos_tienen_categorias`.

#### Opción B — Terminal macOS

```bash
cd /ruta/al/proyecto
mysql -u root -e "CREATE DATABASE IF NOT EXISTS dw3_kuringhian_garcia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root dw3_kuringhian_garcia < db/dw3_kuringhian_garcia.sql
```

MAMP (puerto 8889):

```bash
mysql -u root -proot -P 8889 -e "CREATE DATABASE IF NOT EXISTS dw3_kuringhian_garcia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -proot -P 8889 dw3_kuringhian_garcia < db/dw3_kuringhian_garcia.sql
```

#### Opción C — CMD Windows (XAMPP)

```cmd
cd C:\ruta\al\proyecto
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS dw3_kuringhian_garcia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
C:\xampp\mysql\bin\mysql.exe -u root dw3_kuringhian_garcia < db\dw3_kuringhian_garcia.sql
```

#### Opción D — DER (opcional)

MySQL Workbench u otra herramienta solo para generar `der/dw3_kuringhian_garcia.png`. La app sigue usando MAMP/XAMPP + `DBConexion.php`.

### 11.7 `DBConexion.php` — solo XAMPP y MAMP

Dejar **un solo perfil activo**. Plantilla (basada en el docente):

```php
<?php

class DBConexion
{
    // --- Perfil XAMPP (Mac o Windows) — activo por defecto ---
    public const DB_HOST = "127.0.0.1:3306";
    public const DB_USER = "root";
    public const DB_PASS = "";
    public const DB_NAME = "dw3_kuringhian_garcia";

    // --- Perfil MAMP: comentar bloque XAMPP y descomentar este ---
    // public const DB_HOST = "127.0.0.1:8889";
    // public const DB_USER = "root";
    // public const DB_PASS = "root";
    // public const DB_NAME = "dw3_kuringhian_garcia";

    public function getConexion(): PDO
    {
        $db_dsn = "mysql:host=" . self::DB_HOST
            . ";dbname=" . self::DB_NAME
            . ";charset=utf8mb4";

        try {
            return new PDO($db_dsn, self::DB_USER, self::DB_PASS);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
```

| Perfil | `DB_HOST` | `DB_PASS` | Cuándo |
|--------|-----------|-----------|--------|
| XAMPP | `127.0.0.1:3306` | `""` | XAMPP Mac o Windows |
| MAMP | `127.0.0.1:8889` (o puerto de Preferences) | `root` | MAMP |

Si MAMP en Windows usa puerto 3306, cambiar solo el número en `DB_HOST`; `DB_PASS` sigue siendo `root`.

Uso en modelos:

```php
$db = (new DBConexion)->getConexion();
```

### 11.8 Probar conexión — `test-db.php`

Crear en la raíz del sitio (borrar antes del zip):

```php
<?php
require_once __DIR__ . '/clases/DBConexion.php';
try {
    $db = (new DBConexion)->getConexion();
    $n = $db->query('SELECT COUNT(*) FROM productos')->fetchColumn();
    echo "OK: conexión exitosa. Productos en BD: $n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage();
}
```

| SO | Comando (desde carpeta del sitio) | URL |
|----|-----------------------------------|-----|
| macOS XAMPP | `php -S localhost:8000` | `http://localhost:8000/test-db.php` |
| macOS MAMP | `/Applications/MAMP/bin/php/php8.x.x/bin/php -S localhost:8000` | Igual |
| Windows XAMPP | `C:\xampp\php\php.exe -S localhost:8000` | Igual |
| Windows MAMP | `C:\MAMP\bin\php\php8.x.x\php.exe -S localhost:8000` | Igual |

Éxito: mensaje `OK` y cantidad > 0.

### 11.9 Levantar el sitio

#### Servidor embebido PHP

```bash
cd sitio
php -S localhost:8000
```

Windows (XAMPP):

```cmd
cd C:\ruta\al\proyecto\sitio
C:\xampp\php\php.exe -S localhost:8000
```

URLs:

- Listado: `http://localhost:8000/index.php?seccion=listado`
- Admin: `http://localhost:8000/admin/index.php?seccion=ingresar`

#### Apache (htdocs)

| SO | Carpeta | URL típica |
|----|---------|------------|
| macOS MAMP | `/Applications/MAMP/htdocs/galmir/` | `http://localhost:8888/galmir/index.php?seccion=home` |
| macOS XAMPP | `htdocs/galmir/` | `http://localhost/galmir/index.php?seccion=home` |
| Windows XAMPP | `C:\xampp\htdocs\galmir\` | `http://localhost/galmir/index.php?seccion=home` |
| Windows MAMP | `C:\MAMP\htdocs\galmir\` | Según puerto Apache de MAMP |

### 11.10 Errores frecuentes

| Error | macOS | Windows |
|-------|-------|---------|
| `Connection refused` | **Start** en MAMP o XAMPP | Igual en Control Panel |
| `Unknown database` | Reimportar SQL en phpMyAdmin | Igual; path `db\archivo.sql` en CMD |
| `Access denied for root` | MAMP: `DB_PASS=root` · XAMPP: `""` | Igual |
| Puerto incorrecto | MAMP: `:8889` en `DB_HOST` | MAMP Win: ver **Preferences → Ports** |
| `could not find driver` | `php.ini` del PHP de MAMP/XAMPP usado | `C:\xampp\php\php.ini` o MAMP equivalente |
| `'mysql' is not recognized` | Ruta completa al binario en `/Applications/MAMP/...` | `C:\xampp\mysql\bin\mysql.exe` |
| Listado vacío sin error | Perfil mezclado (host XAMPP + pass MAMP) | Igual |
| Tildes incorrectas | `utf8mb4` en BD y en DSN | Igual |

### 11.11 Checklist “BD conectada”

- [ ] MySQL iniciado en MAMP o XAMPP
- [ ] phpMyAdmin muestra `dw3_kuringhian_garcia` con datos
- [ ] `pdo_mysql` visible con el **mismo** `php` que sirve el sitio
- [ ] Un solo perfil activo en `DBConexion.php` (XAMPP **o** MAMP)
- [ ] `test-db.php` responde OK (archivo eliminado antes del zip)
- [ ] `index.php?seccion=listado` muestra productos desde BD
- [ ] Login del panel funciona con usuario de tabla `usuarios`

---

## Resumen rápido

1. Leer PDF del 2.º parcial y esta guía.
2. Diseñar e importar `dw3_kuringhian_garcia`.
3. Configurar `DBConexion` (XAMPP o MAMP).
4. Migrar `Producto` a PDO; implementar `Usuario`, sesión y ABM admin.
5. Empaquetar `sitio/`, `db/`, `der/`, `datos.txt`.

Referencia de código del docente: `../Clase_Ejemplo_Parcial_2/proyecto/`.  
Entrega 1.er parcial actual: raíz del repositorio (migrar a `sitio/` al final).
