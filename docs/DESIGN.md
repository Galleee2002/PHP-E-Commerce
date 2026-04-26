# Design System - La Mesa Ludica (Rojo Calido)

## 1. Tema Visual y Atmósfera

La identidad visual de La Mesa Ludica es calida, intensa y cercana. El sistema gira alrededor de una familia de rojos profundos combinada con beige y blanco calido para crear contraste legible, sin caer en una estetica fria.

El tono de marca es emocional y dinamico:
- El rojo principal marca jerarquia y presencia.
- El rojo oscuro aporta profundidad en estados hover y pie de pagina.
- El beige claro y medio equilibran la energia del rojo en superficies extensas.
- El amarillo suave se reserva para llamados a la accion y foco visual.

**Caracteristicas clave**
- Fondo general crema/beige para descanso visual.
- Jerarquia de marca basada en 3 rojos.
- Componentes con bordes suaves y contraste alto en texto.
- Botones y elementos interactivos con feedback cromatico claro.
- Lenguaje visual util para e-commerce: legible, directo y confiable.

## 2. Paleta de Colores y Roles

### Colores principales
- **Rojo principal** (`#A31212`): fondo de hero, nav principal, acciones primarias.
- **Rojo oscuro** (`#7A0D0D`): hover, estados activos, footer, overlays oscuros.
- **Rojo acento** (`#C62828`): botones secundarios importantes, cards destacadas, badges de accion.

### Tonos calidos / neutros
- **Beige claro** (`#F5E6D3`): fondo base de pagina y secciones amplias.
- **Beige medio** (`#EAD2B3`): tarjetas suaves, paneles informativos, divisores sutiles.
- **Amarillo suave** (`#F2B544`): CTA visual, foco, highlights y elementos de atencion.

### Neutrales de texto
- **Blanco calido** (`#FFF8F0`): texto sobre rojos y superficies oscuras.
- **Gris texto suave** (`#6B5E57`): texto secundario, metadatos y descripciones.
- **Negro suave** (`#2B1E1E`): texto principal, titulos y labels de alto contraste.

## 3. Tokens Tailwind

Se recomienda usar esta configuracion para mantener consistencia:

```js
// Play CDN v3 (fijado)
// 1) <script src="https://cdn.tailwindcss.com/3.4.17"></script>
// 2) luego definir la config:
tailwind.config = {
	theme: {
		extend: {
			colors: {
				primary: '#A31212',
				'primary-dark': '#7A0D0D',
				accent: '#C62828',
				'beige-light': '#F5E6D3',
				'beige-medium': '#EAD2B3',
				'yellow-soft': '#F2B544',
				'warm-white': '#FFF8F0',
				'text-soft': '#6B5E57',
				'soft-black': '#2B1E1E'
			}
		}
	}
};
```

## 4. Tipografia

### Familias sugeridas
- **Titulos**: Serif expresiva (ejemplo: `Georgia` o `Lora`).
- **Texto UI y cuerpo**: Sans legible (ejemplo: `Inter`, `Source Sans`, `Arial`).

### Escala recomendada
- H1: 36-48px, peso 700, line-height 1.1-1.2
- H2: 28-36px, peso 600-700, line-height 1.2
- H3: 20-24px, peso 600
- Body: 16-18px, peso 400-500, line-height 1.5-1.7
- Caption: 14px, peso 400-500

## 5. Estilos de Componentes

### Boton primario
- Fondo: `#A31212`
- Texto: `#FFF8F0`
- Hover: `#7A0D0D`
- Focus ring: `#F2B544`
- Radio: 10-12px

### Boton secundario
- Fondo: `#C62828`
- Texto: `#FFF8F0`
- Hover: `#7A0D0D`
- Border: opcional `#A31212`

### Card base
- Fondo: `#FFF8F0` o `#EAD2B3`
- Border: `rgba(122, 13, 13, 0.20)`
- Titulo: `#2B1E1E`
- Texto: `#6B5E57`
- Radio: 12-16px
- Sombra: suave (`shadow-sm` / `shadow-md`)

### Inputs
- Fondo: `#FFF8F0`
- Texto: `#2B1E1E`
- Border: `rgba(122, 13, 13, 0.25)`
- Focus: borde `#A31212` y ring `#F2B544`

### Navegacion
- Fondo: `#A31212`
- Links inactivos: `#FFF8F0` con opacidad o fondo tenue
- Link activo: `#C62828`
- Hover: `#F2B544` (texto oscuro)

### Footer
- Fondo: `#7A0D0D`
- Texto principal: `#FFF8F0`
- Links: `#F2B544`

## 6. Layout y Espaciado

- Unidad base: 8px
- Espacios recomendados: 8, 12, 16, 24, 32, 48
- Contenedor: `max-w-6xl` centrado
- Secciones: `p-6` en desktop, `p-4` en mobile
- Separacion vertical entre bloques: `gap-4` a `gap-6`

## 7. Profundidad y Elevacion

| Nivel | Tratamiento | Uso |
|------|-------------|-----|
| 0 | Sin sombra | Fondo principal |
| 1 | Borde suave rojo oscuro 20% | Cards y paneles |
| 2 | `shadow-sm` | Elementos interactivos |
| 3 | `shadow-md` | Bloques destacados |

## 8. Do y Don't

### Do
- Usar `#F5E6D3` como base de pantalla.
- Mantener texto principal en `#2B1E1E`.
- Reservar `#F2B544` para foco y CTA visual.
- Mantener contraste alto en botones rojos con texto claro.
- Usar tokens Tailwind para evitar hex sueltos en componentes.

### Don't
- No mezclar azules frios con esta identidad.
- No usar negro puro (`#000000`) para texto principal.
- No saturar toda la interfaz con rojo sin zonas de descanso beige.
- No quitar estados de focus visibles en formularios y botones.

## 9. Responsive

### Breakpoints
- Mobile: <640px
- Tablet: 640-1023px
- Desktop: >=1024px

### Comportamiento
- Navegacion en wrap en mobile.
- Cards en 1 columna mobile, 2 tablet, 3 desktop.
- Botones con altura tactil minima de 44px.

## 10. Prompt Guide Rapido

- "Usa fondo `beige-light (#F5E6D3)`, tarjeta `warm-white (#FFF8F0)`, titulo `soft-black (#2B1E1E)` y texto secundario `text-soft (#6B5E57)`"
- "Boton primario `primary (#A31212)` con hover `primary-dark (#7A0D0D)` y focus ring `yellow-soft (#F2B544)`"
- "Destaca acciones secundarias con `accent (#C62828)`"
