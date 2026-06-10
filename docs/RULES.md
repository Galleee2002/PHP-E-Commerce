# RULES — Segundo Parcial (Galmir E-Commerce)

Guía de implementación para completar el **2.º parcial** de Programación II sobre la entrega del **1.er parcial**, siguiendo los patrones del docente en `Clase_Ejemplo_Parcial_2/proyecto/` (Saraza Basket) adaptados al e-commerce de juegos de mesa.

**Base de datos:** `dw3_kuringhian_garcia`  
**Entornos permitidos:** solo **XAMPP** y **MAMP** (macOS y Windows).  
**Entorno activo del equipo:** MAMP (Mac) — puerto MySQL `8889`, `DB_PASS = root`.

---

## Estado del proyecto (actualizado)

| Fase | Descripción | Estado |
|------|-------------|--------|
| 1 | Base de datos (tablas, seed, `.sql`) | ✅ Hecho — **DER** ✅ |
| 2 | `DBConexion` + prueba de conexión | ✅ Hecho — `test-db.php` eliminado |
| 3 | `Producto` lectura PDO + listado/detalle | ✅ Hecho |
| 4 | `Usuario` + login admin | ✅ Hecho — lógica, sesión, guard y **UI login** |
| 5 | ABM admin (alta, edición, baja) | ✅ Backend B1–B6 · UI completa (baja vía `<dialog>` en listado) |
| 6 | Pulido (`htmlspecialchars`, edge cases) | ✅ Hecho |
| 7 | Empaquetado (`sitio/`, zip, `datos.txt`) | ✅ Hecho |

**Próximo paso:** Pruebas finales ABM (§ 8.2) → subir `kuringhian-milagros_garcia-gael.zip` al campus.

**Verificado contra** [Programación II - Segundo Parcial.pdf](Programación%20II%20-%20Segundo%20Parcial.pdf): sitio público ✅ · login ✅ · ABM ✅ · DER ✅ · `htmlspecialchars` ✅ · entrega final ⬜.

---

## 1. Contexto y referencias

| Recurso | Ubicación |
|---------|-----------|
| Consigna 1.er parcial | [Programación II - Primer Parcial.pdf](Programación%20II%20-%20Primer%20Parcial.pdf) |
| Consigna 2.º parcial | [Programación II - Segundo Parcial.pdf](Programación%20II%20-%20Segundo%20Parcial.pdf) |
| Código en desarrollo | Raíz del repo (migrar a `sitio/` al empaquetar) |
| SQL exportado | `db/dw3_kuringhian_garcia.sql` ✅ |
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
- El ejemplo **incompleto** del docente como entrega final: no tiene login funcional ni ABM

### Equipo

- Milagros Sol Kuringhian / Gael Garcia Nuñez — Comisión DWN3AP

---

## 2. Checklist obligatorio (2.º parcial)

Marcar antes de subir al campus:

### Sitio público

- [x] **Home** informativa para visitantes nuevos
- [x] **Listado** de ítems desde **base de datos** (no JSON)
- [x] **Detalle** por ítem (enlace con `?id=`)
- [x] **Contacto** con formulario integrado al sitio (sin envío de email ni guardar en BD)

### Panel de administración

- [x] **Login** con email y password (PDO + sesión + diseño en `ingresar.php`)
- [x] **ABM** — listado de ítems con acceso a alta, edición y baja (UI listado ✅)
- [x] **Formulario de alta** con campos necesarios → INSERT en BD (UI ✅)
- [x] **Formulario de edición** pre-poblado con datos actuales → UPDATE en BD (UI ✅)
- [x] **Confirmación de baja** con form POST → DELETE en BD (UI: `<dialog>` en `productos.php`; backend en `producto-borrar.php`)

### PHP

- [x] Carga dinámica de secciones por query string (`?seccion=`)
- [x] POO: mínimo **Producto** ✅, **Usuario** ✅, **DBConexion** ✅
- [x] PDO para **todas** las interacciones con MySQL (lectura + ABM ✅)
- [x] Consultas con datos de `$_GET` / `$_POST` solo con **placeholders** (`porId` ✅)
- [x] Rutas relativas o con `__DIR__`

### Base de datos

- [x] Nombre: `dw3_kuringhian_garcia`
- [x] DER incluido en la entrega (`der/dw3_kuringhian_garcia.png`)
- [x] Archivo `.sql` exportado con **datos cargados**
- [x] Datos reales (6 productos, 5 categorías, 1 admin)
- [x] Normalizada (1NF, 2NF, 3NF)
- [x] Tabla **usuarios**
- [x] Tabla **productos** con al menos **5 campos** de negocio
- [x] Tabla adicional relacionada: **categorias** + **productos_tienen_categorias**

### Entrega

- [x] Zip con carpetas `sitio/`, `db/`, `der/` y `datos.txt` en la raíz del zip
- [x] `datos.txt` indica **2do parcial** + credenciales admin (`admin@galmir.local` / `admin123`)
- [x] Nombre del zip: `kuringhian-milagros_garcia-gael.zip`

---

## 3. Matriz de brechas

| Requisito | 1.er parcial | Objetivo 2.º parcial | Estado actual |
|-----------|--------------|----------------------|---------------|
| Fuente de ítems | `data/productos.json` | Tabla `productos` vía PDO | ✅ Hecho |
| `DBConexion` | No existía | `clases/DBConexion.php` | ✅ Hecho (MAMP) |
| `Producto` lectura | JSON | `todas()`, `porId()` PDO | ✅ Hecho |
| `Usuario` + sesión | No existía | Login admin + `session_start()` | ✅ Hecho |
| Panel `admin/` | No existía | `admin/index.php` + vistas ABM | ✅ |
| CRUD productos | Solo lectura | `crear`, `actualizar`, `eliminar` | ✅ Hecho |
| Tabla relacionada | No existía | `categorias` + N:M | ✅ Hecho |
| Detalle | Descripción hardcodeada | `getDescripcion()` desde BD | ✅ Hecho |
| Escape HTML | Parcial (admin sí, público no) | `htmlspecialchars()` en **todas** las salidas | ✅ Fase 6 |
| Estructura entrega | Proyecto en raíz | `sitio/` + `db/` + `der/` | ✅ Fase 7 |
| `datos.txt` | 1er parcial | 2do parcial + credenciales | ✅ Hecho |
| DER | — | `der/dw3_kuringhian_garcia.png` | ✅ |

---

## 4. Modelo de datos (`dw3_kuringhian_garcia`)

Equivalente al ejemplo NBA (`noticias`, `equipos`, `noticias_tienen_equipos`).

```
usuarios (1) ──< productos (N)
productos (N) ──< productos_tienen_categorias >── (N) categorias
```

### Tablas implementadas

| Tabla | Registros seed | Notas |
|-------|----------------|-------|
| `usuarios` | 1 admin | `admin@galmir.local` / `admin123` |
| `productos` | 6 | Migrados desde `data/productos.json` |
| `categorias` | 5 | Estrategia, Clásico, Rompecabezas, Cartas, Misterio |
| `productos_tienen_categorias` | 6 | Relación N:M |

### Campos clave de `productos` (≥5 de negocio)

`nombre`, `precio`, `descripcion_corta`, `descripcion`, `imagen` (+ `usuario_fk`, `fecha_alta`)

### Archivos de BD

| Archivo | Estado |
|---------|--------|
| `db/dw3_kuringhian_garcia.sql` | ✅ Exportado desde phpMyAdmin |
| `der/dw3_kuringhian_garcia.png` | ✅ |

---

## 5. Convenciones de código

### Rutas y archivos

- Imágenes: rutas relativas (`imgs/teg.webp`)
- Includes: `__DIR__ . '/../clases/Producto.php'`
- Prohibido: rutas de disco absolutas o URLs fijas al proyecto local

### Routing (como el docente)

Whitelist de nombres de sección + `require` dinámico de la vista (sin mapa de rutas absolutas):

```php
$seccionesPermitidas = ['home', 'listado', 'detalle', 'contacto'];

$seccionActual = $_GET['seccion'] ?? 'home';

if (!in_array($seccionActual, $seccionesPermitidas, true)) {
    $seccionActual = '404';
}

require __DIR__ . '/vistas/' . $seccionActual . '.php';
```

Mismo patrón en `admin/index.php` (whitelist + guard de sesión + `require` de `admin/vistas/{seccion}.php`).

### Carga de datos (como el docente)

- **No** precargar productos en `index.php` público ni en `admin/index.php`.
- Cada vista hace su `require_once` de la clase y consulta PDO en el bloque PHP superior.
- Ejemplo público (`vistas/listado.php`): `$productos = (new Producto)->todas();`
- Ejemplo admin (`admin/vistas/productos.php`): idem.

### PDO en clases (implementado en `Producto`)

```php
$consulta = "SELECT ... FROM productos p ... WHERE p.producto_id = :id";
$stmt = $db->prepare($consulta);
$stmt->execute(['id' => $id]);
$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
```

**Prohibido:** concatenar `$_GET` / `$_POST` en el SQL.

### `FETCH_CLASS`

Propiedades privadas en **snake_case**: `$producto_id`, `$descripcion_corta`. Getters públicos en camelCase: `getId()`, `getDescripcionCorta()`.

### Salida HTML (Fase 6) ✅

Todas las salidas dinámicas usan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`:

| Área | Archivos |
|------|----------|
| Sitio público | `vistas/listado.php`, `vistas/detalle.php` |
| Includes | `includes/header.php` (`$_GET['q']` en búsqueda) |
| Admin | `admin/vistas/ingresar.php`, `productos.php`, `producto-alta.php`, `producto-editar.php` |

`vistas/home.php`, `vistas/contacto.php` y `vistas/404.php` no tienen salida dinámica desde BD ni `$_GET`/`$_POST`.

```php
<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>
```

### Admin — guard de sesión (Fase 4)

En `admin/index.php`, antes del HTML:

- Si `seccion !== 'ingresar'` y no hay sesión → redirigir a `?seccion=ingresar`

---

## 6. Mapa de archivos

### Estado en repo (raíz)

```
PHP-E-Commerce/
├── index.php                    ✅ whitelist + require vista (sin precarga BD)
├── clases/
│   ├── DBConexion.php           ✅ MAMP activo
│   ├── Producto.php             ✅ lectura + CRUD (1 categoría por formulario, N:M en BD)
│   └── Usuario.php              ✅ porEmail, verificarCredenciales, sesión
├── admin/
│   ├── index.php                ✅ session_start, whitelist, guard, salir (sin precarga BD)
│   └── vistas/
│       ├── ingresar.php         ✅ login + UI
│       ├── productos.php        ✅ backend B3 + UI
│       ├── producto-alta.php    ✅ backend B4 + UI
│       ├── producto-editar.php  ✅ backend B5 + UI
│       ├── producto-borrar.php  ✅ backend POST (GET solo fallback si se abre la URL directo)
│       └── 404.php              ✅
├── vistas/
│   ├── home.php                 ✅
│   ├── listado.php              ✅ lee BD en la vista (Producto::todas)
│   ├── detalle.php              ✅ getDescripcion() desde BD
│   ├── contacto.php             ✅
│   └── 404.php                  ✅
├── includes/                    ✅
├── css/ · imgs/                 ✅ (incl. `login-img.webp`)
├── admin/css/
│   ├── ingresar.css             ✅
│   ├── productos.css            ✅
│   └── producto-editar.css      ✅ (alta + edición)
├── data/productos.json          ✅ backup migración
├── db/dw3_kuringhian_garcia.sql ✅
├── docs/
│   ├── RULES.md                 ✅
│   └── Programación II - Segundo Parcial.pdf
├── der/dw3_kuringhian_garcia.png  ✅
└── datos.txt                    ✅ 2do parcial + credenciales admin
```

### Objetivo al empaquetar (`sitio/`)

Copiar el proyecto PHP completo dentro de `sitio/` manteniendo la misma estructura.

**Whitelist público:** `home`, `listado`, `detalle`, `contacto`, `404`

**Whitelist admin:** `ingresar`, `productos`, `producto-alta`, `producto-editar`, `producto-borrar`, `salir`, `404`

---

## 7. Modalidad de entrega (zip)

```
kuringhian-milagros_garcia-gael.zip
├── sitio/                         # proyecto PHP completo
├── db/dw3_kuringhian_garcia.sql
├── der/dw3_kuringhian_garcia.png
└── datos.txt                      # raíz del zip, NO dentro de sitio/
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
Password administrador: admin123
```

Las credenciales son las del **panel web**, no las de MySQL `root`.

---

## 8. Plan de implementación por fases

| # | Fase | Tareas | Estado |
|---|------|--------|--------|
| 1 | **Base de datos** | Tablas, seed, exportar `.sql`, DER | ✅ |
| 2 | **`DBConexion`** | Perfil MAMP/XAMPP, probar conexión | ✅ |
| 3 | **`Producto` lectura** | `todas()`, `porId()`, listado, detalle | ✅ |
| 4 | **`Usuario` + login** | `Usuario.php`, `admin/`, sesión, guard, UI login | ✅ |
| 5 | **ABM admin** | Backend B1–B6 + UI (login, listado, alta, edición, baja vía dialog) | ✅ |
| 6 | **Pulido** | `htmlspecialchars` en público + admin, pruebas ABM | ✅ |
| 7 | **Empaquetado** | `sitio/`, `db/`, `der/`, `datos.txt`, zip | ✅ Hecho |

### 8.1 Pasos lógicos tras el diseño del login (Fase 4 → 5)

Con el frontend del login integrado en `admin/vistas/ingresar.php` y `admin/css/ingresar.css`, el orden de trabajo recomendado es:

#### Paso A — Cerrar Fase 4 ✅

Verificado: login, guard de sesión, redirect y logout funcionan correctamente.

#### Paso B — Fase 5 backend (PHP / PDO) ✅

| Orden | Tarea | Archivo(s) | Estado |
|-------|-------|------------|--------|
| B1 | Métodos `crear()`, `actualizar()`, `eliminar()` en `Producto` (`$categoriaId` como `int`) | `clases/Producto.php` | ✅ |
| B2 | Alta/edición: persistir categoría en `productos_tienen_categorias` (1 `categoria_id` por formulario) | `Producto.php` + vistas | ✅ |
| B3 | Listado admin desde BD | `admin/vistas/productos.php` | ✅ backend |
| B4 | Formulario alta + POST → INSERT | `admin/vistas/producto-alta.php` | ✅ backend |
| B5 | Formulario edición pre-poblado + POST → UPDATE | `admin/vistas/producto-editar.php` | ✅ backend |
| B6 | Confirmación + POST → DELETE | `admin/vistas/producto-borrar.php` | ✅ backend |

Reglas PDO para el ABM (igual que lectura):

- Solo `prepare()` + placeholders (`:nombre`, `:id`, etc.).
- `usuario_fk` en INSERT: usar `Usuario::idEnSesion()`.
- Validar `id` inexistente antes de editar/borrar → mensaje o redirect al listado.
- Campos mínimos del producto: `nombre`, `precio`, `descripcion_corta`, `descripcion`, `imagen`, `categoria_id` (una categoría por alta/edición; tabla N:M sigue en BD).
- Estilo alineado al docente: `prepare()` + `execute()` en lecturas, datos cargados en cada vista, ABM sin transacciones ni helpers privados extra.

#### Paso C — Fase 5 frontend ✅

Todas las pantallas del panel tienen UI integrada:

| Pantalla | Sección | Archivo | Notas |
|----------|---------|---------|-------|
| Login | `ingresar` | `ingresar.php` | ✅ |
| Listado | `productos` | `productos.php` | ✅ incluye acciones Editar / Borrar |
| Alta | `producto-alta` | `producto-alta.php` | ✅ |
| Edición | `producto-editar&id=N` | `producto-editar.php` | ✅ |
| Baja | `producto-borrar&id=N` | `productos.php` + `producto-borrar.php` | ✅ confirmación en `<dialog>` del listado; POST a `producto-borrar` |

**Flujo de baja (implementado):**

1. En el listado, el botón borrar abre un `<dialog>` con el nombre del producto.
2. «Sí, eliminar» envía `POST` a `index.php?seccion=producto-borrar&id={id}` con `producto_id`.
3. `producto-borrar.php` ejecuta `eliminar()` y redirige al listado.

El HTML mínimo en `producto-borrar.php` (GET) solo aparece si alguien abre esa URL directo en el navegador; el flujo normal no lo usa.

#### Paso D — Fase 6 (pulido) ✅

1. **`htmlspecialchars()` en sitio público:** `vistas/listado.php`, `vistas/detalle.php` e `includes/header.php` (`$_GET['q']`). `home.php`, `contacto.php` y `404.php` sin salida dinámica.
2. **Admin:** escape confirmado en `ingresar`, `productos`, `producto-alta` y `producto-editar`; `producto-borrar` solo expone `(int)` en fallback GET.
3. **Pruebas ABM end-to-end** (checklist § 8.2) — pendiente de marcar manualmente antes de entregar.

#### Paso E — Fase 7 (entrega) ⬜

1. **DER:** ✅ `der/dw3_kuringhian_garcia.png`
2. **`datos.txt`:** carácter **2do Parcial** + credenciales admin (`admin@galmir.local` / `admin123`).
3. **Empaquetado:** copiar proyecto PHP a `sitio/`, incluir `db/` y `der/`, generar `kuringhian-milagros_garcia-gael.zip`.
4. **Verificar:** no incluir `test-db.php`; perfil MAMP activo en `DBConexion.php`.

### 8.2 Checklist de pruebas (antes de entregar)

Marcar al cerrar Fase 5–6:

**Listado admin**
- [ ] Muestra los productos seed desde BD
- [ ] Enlaces Editar/Borrar llevan al `id` correcto
- [ ] Botón Alta funciona
- [ ] Cerrar sesión redirige al login

**Alta**
- [ ] Select muestra las 5 categorías
- [ ] Alta válida aparece en listado público y admin
- [ ] Error de validación muestra `$errorAlta` y repuebla campos

**Edición**
- [ ] Formulario pre-poblado con `$valoresEdicion` (incluye `categoria_id`)
- [ ] Cambios persisten en BD y sitio público
- [ ] `id=999` redirige al listado

**Baja**
- [ ] Muestra nombre del producto a eliminar
- [ ] POST elimina y redirige al listado
- [ ] Cancelar vuelve sin borrar
- [ ] Producto desaparece del sitio público

**General**
- [x] `htmlspecialchars()` en todas las salidas dinámicas (público + admin)
- [ ] HTML5 semántico y CSS coherente con la temática
- [ ] Zip con estructura `sitio/`, `db/`, `der/`, `datos.txt`

---

## 9. Anti-patrones (desaprueban el TP)

- Concatenar `$_GET` / `$_POST` dentro del SQL
- Copiar Saraza Basket o importar `prog2_2026_1_n` como base
- Lorem ipsum en productos visibles
- Panel admin sin login
- Rutas absolutas de disco o URL fija al TP local
- Entregar sin `sitio/`, `db/`, `der/`
- Mezclar perfil XAMPP (pass vacía) con MAMP (`root`)
- Subir `test-db.php` en el zip final
- Laragon, Homebrew o MySQL Installer (solo **MAMP** y **XAMPP**)

---

## 10. Equivalencias docente (NBA) → Galmir

| Docente | Galmir | Estado |
|---------|--------|--------|
| `Noticia` | `Producto` | Lectura ✅ · CRUD ✅ · UI ABM ✅ |
| `noticias` | `productos` | ✅ |
| `equipos` | `categorias` | ✅ |
| `noticias_tienen_equipos` | `productos_tienen_categorias` | ✅ |
| `noticias` (listado) | `listado` | ✅ |
| `noticias-leer` | `detalle` | ✅ |
| `admin/vistas/noticias.php` | `admin/vistas/productos.php` | ✅ |
| `DBConexion` + `Usuario` | Igual concepto | ✅ |

---

## 11. Guía de entorno (MAMP / XAMPP)

Solo **dos perfiles** en `DBConexion.php`. Dejar **uno activo**.

| Perfil | `DB_HOST` | `DB_PASS` | Cuándo |
|--------|-----------|-----------|--------|
| **MAMP** ✅ (activo) | `127.0.0.1:8889` | `root` | Mac del equipo |
| XAMPP | `127.0.0.1:3306` | `""` | Otro integrante |

### Levantar el sitio (desarrollo)

```bash
cd /ruta/al/proyecto
/Applications/MAMP/bin/php/php8.3.30/bin/php -S localhost:8000
```

| URL | Qué probar |
|-----|------------|
| `http://localhost:8000/index.php?seccion=listado` | 6 productos desde BD |
| `http://localhost:8000/index.php?seccion=detalle&id=1` | T.E.G. + descripción BD |
| `http://localhost:8000/index.php?seccion=detalle&id=999` | Producto no encontrado |
| `http://localhost:8000/admin/index.php?seccion=ingresar` | Login UI + credenciales |
| `http://localhost:8000/admin/index.php?seccion=productos` | Listado ABM con UI |
| `http://localhost:8000/admin/index.php?seccion=producto-alta` | Alta con UI |
| `http://localhost:8000/admin/index.php?seccion=producto-editar&id=1` | Edición con UI |
| `http://localhost:8000/admin/index.php?seccion=producto-borrar&id=1` | Solo POST desde dialog del listado (GET = fallback mínimo) |

### `DBConexion.php` (estado actual en repo)

```php
<?php

class DBConexion
{
    // --- Perfil XAMPP (Mac o Windows) ---
    // public const DB_HOST = "127.0.0.1:3306";
    // public const DB_USER = "root";
    // public const DB_PASS = "";
    // public const DB_NAME = "dw3_kuringhian_garcia";

    // --- Perfil MAMP (Mac) — activo ---
    public const DB_HOST = "127.0.0.1:8889";
    public const DB_USER = "root";
    public const DB_PASS = "root";
    public const DB_NAME = "dw3_kuringhian_garcia";

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

### Importar / reimportar BD

1. MAMP → **Start** → phpMyAdmin (`http://localhost:8888/phpMyAdmin`)
2. Crear o seleccionar `dw3_kuringhian_garcia`
3. **Importar** → `db/dw3_kuringhian_garcia.sql`
4. Verificar 4 tablas con datos

Terminal MAMP (alternativa):

```bash
/Applications/MAMP/Library/bin/mysql -u root -proot -P 8889 \
  dw3_kuringhian_garcia < db/dw3_kuringhian_garcia.sql
```

### Errores frecuentes

| Error | Solución |
|-------|----------|
| `Connection refused` | MySQL no iniciado en MAMP |
| `Unknown database` | Reimportar SQL |
| `Access denied` | MAMP → `DB_PASS = "root"` |
| `could not find driver` | Usar PHP de MAMP, habilitar `pdo_mysql` |
| Listado vacío | Perfil mezclado XAMPP + MAMP |

### Checklist conexión (Fases 1–3)

- [x] MySQL iniciado en MAMP
- [x] phpMyAdmin muestra `dw3_kuringhian_garcia` con datos
- [x] Perfil MAMP activo en `DBConexion.php`
- [x] `test-db.php` probado OK y **eliminado**
- [x] Listado muestra productos desde BD
- [x] Login del panel funciona (Fase 4 — credenciales `admin@galmir.local` / `admin123`)

---

## Resumen rápido

**Hecho:** BD + SQL + `DBConexion` + `Producto` (lectura + CRUD) + sitio público + login admin + ABM completo (backend + UI, baja vía dialog) + `htmlspecialchars` (público + admin).

**Pendiente:** pruebas finales ABM (§ 8.2) → subir `kuringhian-milagros_garcia-gael.zip` al campus.

Referencia docente: `../Clase_Ejemplo_Parcial_2/proyecto/`.
