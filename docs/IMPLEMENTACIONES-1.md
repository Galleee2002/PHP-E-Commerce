# IMPLEMENTACIONES-1.md — Fase 2: autenticación pública y roles

Documento académico y simple sobre lo implementado en la **Fase 2** del final de Programación II (Galmir).  
Foco principal: conceptos de **PHP** aplicados al sitio.

---

## 1. Objetivo de la fase

Completar el acceso de usuarios en el Sitio público y proteger el panel Admin:

1. **Registro** de usuarios comunes.
2. **Inicio de sesión** y **cierre de sesión**.
3. **Perfil** visible solo para quienes están autenticados.
4. **Roles** `comun` y `admin` (no existe un rol “invitado”).
5. El Admin solo acepta usuarios con rol `admin`.

Una persona que navega sin cuenta **no es un rol**: es simplemente un visitante **sin sesión**.

---

## 2. Sesiones en PHP

### `session_start()`

Al principio del front controller (`sitio/index.php` y `sitio/admin/index.php`) se llama a `session_start()`.  
Eso habilita el array `$_SESSION`, donde PHP guarda datos del usuario entre peticiones HTTP.

Sin sesión no hay “estado de login”: cada request sería independiente.

### Datos guardados en sesión

La clase `Usuario` define constantes para las claves:

| Constante | Clave en `$_SESSION` | Contenido |
|-----------|----------------------|-----------|
| `SESSION_KEY_ID` | `usuario_id` | ID del usuario |
| `SESSION_KEY_EMAIL` | `usuario_email` | Email |
| `SESSION_KEY_ROL` | `usuario_rol` | `comun` o `admin` |

Al iniciar sesión se usa `session_regenerate_id(true)` para reducir el riesgo de fijación de sesión (reutilizar un ID viejo).

### Autenticación vs autorización

- **Autenticación** (`estaLogueado()`): ¿hay un `usuario_id` en sesión?
- **Autorización** (`esAdmin()`): ¿el rol en sesión es `admin`?

Ambas son necesarias: un usuario común puede estar autenticado en el Sitio, pero **no** autorizado en el Admin.

---

## 3. Programación orientada a objetos: clase `Usuario`

Archivo: `sitio/clases/Usuario.php`.

Responsabilidades:

- Leer usuarios desde MySQL (`porEmail()`, `porId()`).
- Verificar email + contraseña (`verificarCredenciales()`).
- Registrar nuevos usuarios (`registrar()`).
- Manejar la sesión (`iniciarSesion()`, `cerrarSesion()`, `estaLogueado()`, `esAdmin()`).

Las consultas SQL viven **dentro de métodos de la clase**, no en las vistas.  
Eso sigue el estilo de la cursada (modelo tipo Active Record).

Propiedades relevantes:

- `usuario_id`, `email`, `password`, `nombre`, `apellido`, `rol`.

Roles persistidos (únicos):

```php
public const ROL_COMUN = 'comun';
public const ROL_ADMIN = 'admin';
```

---

## 4. PDO y placeholders

PDO (`PHP Data Objects`) se obtiene con `DBConexion::getConexion()`.

Ejemplo de consulta segura:

```php
$consulta = "SELECT usuario_id, email, password, nombre, apellido, rol
             FROM usuarios
             WHERE email = :email";
$stmt = $db->prepare($consulta);
$stmt->execute(['email' => $email]);
```

`:email` es un **placeholder**. El valor real se pasa en `execute()`.  
Así se evita concatenar datos del usuario dentro del SQL (riesgo de inyección SQL).

El resultado se hidrata en la clase con:

```php
$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
```

---

## 5. Contraseñas: `password_hash` y `password_verify`

### Al registrar

```php
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
```

Se guarda el **hash** en la columna `password`, nunca el texto plano.

### Al iniciar sesión

```php
password_verify($passwordIngresada, $usuario->getPassword());
```

Compara la contraseña escrita con el hash almacenado.

Mensaje de error genérico:  
“Email o contraseña incorrectos.”  
No se revela si el email existe (mejor práctica básica de seguridad).

---

## 6. Front controller y whitelist

Archivo: `sitio/index.php`.

Flujo:

1. `session_start()` y carga de `Usuario`.
2. Lectura de `?seccion=...`.
3. Acciones especiales (`salir`) y **guards** (redirecciones).
4. Procesamiento de formularios POST (registro / login).
5. Solo después: `header.php` + vista + `footer.php`.

Whitelist de secciones públicas:

```php
$seccionesPermitidas = [
    'home', 'listado', 'detalle', 'contacto',
    'registro', 'iniciar-sesion', 'perfil',
];
```

Si la sección no está permitida → se muestra `404`.  
Esto evita cargar archivos PHP arbitrarios vía query string.

### Por qué la lógica va antes del HTML

`header('Location: ...')` debe enviarse **antes** de cualquier salida HTML.  
Si el header ya se imprimió, la redirección falla (“headers already sent”).

Por eso registro, login, logout y guards se resuelven en el front controller, no dentro de la vista.

---

## 7. Guards (protección de secciones)

### Sitio público

| Situación | Acción |
|-----------|--------|
| Logueado entra a `registro` o `iniciar-sesion` | Redirige a `perfil` |
| No logueado entra a `perfil` | Redirige a `iniciar-sesion` |
| `?seccion=salir` | Cierra sesión y vuelve a `home` |

### Admin

En `sitio/admin/index.php`:

- Rutas protegidas exigen `Usuario::esAdmin()`.
- Un usuario `comun` autenticado en el Sitio puede ver el formulario de login del Admin, pero **no** productos ni el ABM.
- En `admin/vistas/ingresar.php`, solo se inicia sesión si el usuario tiene rol `admin`.

---

## 8. Flujo completo de la Fase 2

```text
Visitante (sin sesión)
   │
   ├─ Registro ──► INSERT usuarios (rol = comun, password hasheado)
   │                    │
   │                    └─► Login con mensaje de éxito
   │
   └─ Login ──► verificarCredenciales()
                    │
                    ├─ OK ──► $_SESSION (id, email, rol) ──► Perfil
                    └─ Error ──► mensaje genérico

Perfil ──► Cerrar sesión ──► session_destroy() ──► Home

Admin:
  credenciales + rol admin ──► panel
  común u otros ──► no entra
```

---

## 9. Escape de salida (XSS)

En las vistas, todo dato dinámico se imprime con:

```php
htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
```

Así, caracteres como `<` o `"` no se interpretan como HTML/JS.

Las contraseñas **nunca** se repueblan en los inputs ni se muestran en el perfil.

---

## 10. Archivos tocados en esta fase

| Archivo | Rol |
|---------|-----|
| `sitio/clases/Usuario.php` | Auth, roles, registro, sesión |
| `sitio/index.php` | Front controller + guards + POST |
| `sitio/includes/header.php` | Navegación condicional |
| `sitio/vistas/registro.php` | Formulario de alta |
| `sitio/vistas/iniciar-sesion.php` | Formulario de login |
| `sitio/vistas/perfil.php` | Datos del usuario logueado |
| `sitio/css/cuenta.css` | Estilos de cuenta |
| `sitio/css/estilos.css` | Import del CSS de cuenta |
| `sitio/admin/index.php` | Guard `esAdmin()` |
| `sitio/admin/vistas/ingresar.php` | Login solo para admin |

La base de datos ya tenía la columna `rol` y los usuarios semilla (Fase 1). Esta fase **consume** ese schema desde PHP.

---

## 11. Ideas clave para la defensa oral

1. El carrito aún no está (Fase 3); aquí solo resolvemos **quién está logueado** y **con qué rol**.
2. `comun` / `admin` son valores de columna; el visitante sin cuenta no es un tercer rol.
3. Las consultas usan placeholders PDO.
4. Las contraseñas se hashean; la sesión guarda id/email/rol, no el password.
5. El front controller + whitelist controla qué vistas se cargan.
6. El Admin exige autorización (`esAdmin`), no solo autenticación.
