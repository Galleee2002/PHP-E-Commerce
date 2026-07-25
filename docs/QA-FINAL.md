# QA-FINAL.md — Devolución senior (Galmir / Programación II)

Fecha de auditoría: **2026-07-25**.  
Fuentes: `docs/Programación II - Final.pdf` (vía auditoría en `docs/RULES.md`), código en `sitio/`, DB `dw3_kuringhian_garcia`.

---

## 1. Veredicto

**Listo para entregar.**

La consigna funcional del final está implementada (Sitio + Admin, 2 roles, carrito autenticado, compra sin pasarela, OOP/PDO, usuarios e historial). La Fase 6 de pulido/entrega quedó cerrada: sin `var` en JS, nav condicional, `datos.txt` con carácter **final**, DER actualizado, SQL importable, zip de entrega armado, y E2E **23/23 PASS**.

---

## 2. Matriz consigna PDF → estado

### Sitio

| # | Requisito | Estado | Evidencia |
|---|-----------|--------|-----------|
| 1 | Home | OK | `vistas/home.php` |
| 2 | Listado (DB → detalle) | OK | `vistas/listado.php` + `Producto` |
| 3 | Detalle + agregar carrito | OK | POST `agregar-carrito` + guard login |
| 4 | Contacto (conceptual) | OK | form + JS, sin mail |
| 5 | Registro (solo no auth) | OK | vista + guard redirect |
| 6 | Iniciar sesión (solo no auth) | OK | bcrypt + sesión |
| 7 | Perfil (solo auth) | OK | guard → login |
| 8 | Carrito (auth; quitar + comprar) | OK | `Carrito` + `Compra` |

### Admin

| # | Requisito | Estado | Evidencia |
|---|-----------|--------|-----------|
| 1 | Login admin | OK | `esAdmin()` |
| 2–4 | ABM ítems | OK | productos / alta / editar / borrar |
| 5 | Lista usuarios | OK | `usuarios.php` + `Usuario::todos()` |
| 6 | Detalle + historial | OK | `usuario-detalle.php` + `Compra::porUsuario/porId` |

### PHP / DB / entrega

| Requisito | Estado | Notas |
|-----------|--------|-------|
| Front controller + whitelist GET | OK | `sitio/index.php`, `admin/index.php` |
| OOP: ítem, usuarios, DB, auth, carrito | OK | + `Compra` |
| PDO + placeholders | OK | sin concatenar input en SQL |
| Rutas `__DIR__` | OK | sin paths de PC |
| DB `dw3_apellido1_apellido2` | OK | `dw3_kuringhian_garcia` |
| Tablas usuarios, ítems, relacionada, compras | OK | categorías N:M + compras N:M |
| DER + SQL con datos reales | OK | regenerados en Fase 6 |
| ≥ 2 roles | OK | `comun` / `admin` |
| Zip entrega | OK | `Kuringhian_Garcia.zip` |

---

## 3. Resultados E2E (2026-07-25)

Entorno: XAMPP (Apache + MySQL + PHP 8.2), URL base `http://127.0.0.1/PHP-E-Commerce/sitio`.

| ID | Resultado |
|----|-----------|
| sitio_home / listado / detalle / contacto | PASS |
| nav_guest_registro_login | PASS |
| nav_guest_sin_carrito | PASS |
| guard_perfil_sin_auth / guard_carrito_sin_auth | PASS |
| login_comun | PASS |
| nav_auth_perfil_carrito / nav_auth_sin_registro | PASS |
| agregar_carrito | PASS |
| completar_compra (`compras 3 → 4`) | PASS |
| carrito_vacio_post_compra | PASS |
| comun_no_entra_admin | PASS |
| admin_login_productos | PASS |
| admin_usuarios_lista | PASS |
| admin_historial_usuario | PASS |
| admin_detalle_id_invalido | PASS |
| admin_producto_alta | PASS |
| js_sin_var_detalle | PASS |
| datos_txt_final | PASS |
| header_nav_condicional_codigo | PASS |

**Resumen: 23/23 PASS.**

Flujo feliz verificado:

```text
Login comun → Detalle → Carrito → Completar compra
  → Admin login → Usuarios → Detalle historial
```

DB: dump importado en DB de prueba `dw3_kuringhian_garcia_qa` sin error (luego eliminada). Schema con `rol`, `compras`, `compras_tienen_productos` y seeds.

---

## 4. Checklist Fase 6 (`RULES.md`)

| Ítem | Estado |
|------|--------|
| `var` → `const`/`let` | OK (`detalle.php`) |
| Nav Sitio condicional | OK (registro/login vs perfil/carrito/salir) |
| Detalle carrito + login | OK |
| Contacto conceptual | OK |
| Placeholders | OK |
| Sin rutas de máquina | OK |
| `datos.txt` = **final** + credenciales | OK (+ usuario común de prueba) |
| DER + SQL importable | OK |
| Zip `sitio/` + `db/` + `der/` + `datos.txt` | OK → `Kuringhian_Garcia.zip` |
| E2E documentado | OK |

---

## 5. Hallazgos / residuales (no bloqueantes)

1. **Usuario `test@gmail.com`** y compras extra en el dump: datos reales de pruebas locales (válidos para la consigna; no son Lorem). Si se quiere un dump “mínimo de seed”, se puede limpiar antes de reentregar.
2. **DER PNG** regenerado con el schema completo; es un diagrama legible para defensa (no un export de MySQL Workbench).
3. **PHPDoc `@var`** en vistas PHP: se mantienen (no son JavaScript).
4. **Host PDO** `127.0.0.1` en `DBConexion`: configuración local habitual; no es path hardcodeado de PC.

---

## 6. Credenciales de prueba (`datos.txt`)

| Rol | Email | Password |
|-----|-------|----------|
| admin | `admin@galmir.local` | `admin123` |
| comun | `usuario@galmir.local` | `usuario123` |

---

## 7. Qué entregar / qué sigue

1. Subir/entregar **`Kuringhian_Garcia.zip`** (raíz del repo).
2. Preparar **defensa oral** con los puntos de `RULES.md` §10 (sesión vs MySQL, roles, placeholders, transacción, whitelist).
3. No hace falta más desarrollo funcional para cumplir la consigna del PDF.

**Conclusión:** el proyecto Galmir cubre el final de Programación II. Estado: **cerrado para entrega**.
