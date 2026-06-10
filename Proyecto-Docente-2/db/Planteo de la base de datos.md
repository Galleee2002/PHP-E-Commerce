# Planteo de la estructura de la base de datos para Saraza Basket
Como nuestra base de datos va a ser SQL (MySQL o MariaDB) vamos
a necesitar contar con un schema, que vamos a llamar: 
    prog2_2026_1_n

## Entidades
Los elementos de los cuales queremos almacenar información.

- noticias
- usuarios
- equipos

## Atributos / Campos
Son las características de las que queremos guardar datos de cada
una de las entidades (típicamente, al menos 2).

- noticias
    - noticia_id
    - titulo
    - sinopsis
    - cuerpo
    - imagen
    - imagen_descripcion
    - fecha_publicacion
    - usuario_fk

- usuarios
    - usuario_id
    - email
    - contraseña
    - tipo_usuario*
    - nombre
    - apellido

- equipos
    - equipo_id
    - ciudad
    - nombre
    - conferencia*

## Registros
Todos los registros de una tabla van a tener que componerse de la
misma cantidad de columnas: La cantidad de atributos de la tabla.

Cada registro se compone de un valor por cada atributo de la tabla.

Por ejemplo, en equipos:
    (1, 'San Antonio', 'Spurs', 'Oeste')
    (2, 'Houston', 'Rockets', 'Oeste')
    (3, 'Boston', 'Celtics', 'Este')

¿Qué pasa si de un determinado registro hay uno o más datos que
no sabemos?
Depende como armemos la base. Pero siempre le vamos a poder poner
algún valor "default": null, "", etc.


## TODO
- Hacer el DER de la base.

## Normalización
Es el proceso de aplicar las normas de base de datos conocidas
como "formas normales" (normal forms, NF).

Si bien hay muchas NF, las realmente importantes son las primeras
3: 1NF, 2NF y 3NF

### 1NF
Esta regla nos dice que no deben existir "grupos de repetición",
refiriéndose a que no debe haber:
    - Campos con un grupo de valores.
    - Varios campos que listen alternativas de valores.

Adicionalmente, en base de datos siempre se recomienda que si
dos valores pueden ir separados, entonces vayan separados.

postres
- id
- nombre
- sabores

+-------+-----------+-----------------------+
| id    | nombre    | sabores               |
+-------+-----------+-----------------------+
| 1     | Helado    | Chocolate, Vainilla   | <--- grupo de valores
| 2     | Licuado   |                       |
+-------+-----------+-----------------------+

¿Por qué está mal?
Se vuelve mucho más difícil, sin mencionar lento, poder manejar
esa información.

Supongamos que nos piden si tenemos el gusto "chocolate común".
¿Cómo lo busco?
En base de datos, las búsquedas potencialmente eficientes suelen
ser del formato:
    <campo> <operador> <valor>

Por ejemplo:
    sabor = 'Chocolate'
    precio < 10000

Este tipo de búsquedas ya no serivirían. No podemos simplemente
preguntar si el campo es igual a "Chocolate", porque tiene un 
montón de caracteres más.
Necesitaríamos o hacer una búsqueda "parcial" (*mucho* más lento)
o tendríamos que parsear el campo y en valores individuales y luego
buscar (también es mucho más lento, y además complicado de 
programar).

Por estas razones es que *nunca* deberíamos tener una lista de
valores en un campo.(*)


(*) En las versiones modernas de bases de datos, existen campos
para poder guardar valores en JSON. En este tipo de campos sí
podría llegar a haber ciertos tipos de listas de valores. Pero
no es lo habitual.

¿Y qué pasa con los "grupos de columnas"?
Como alternativa a lo anterior, a alguien se le podría ocurrir
hacer:

+-------+-----------+-----------+-----------+-----------+
| id    | nombre    | sabor1    | sabor2    | sabor3    | 
+-------+-----------+-----------+-----------+-----------+
| 1     | Helado    | Chocolate | Vainilla  | Menta     |
| 2     | Licuado   |           |           |           |
+-------+-----------+-----------+-----------+-----------+

Ahí tenemos una lista / grupo de campos (los sabores del 1 al 3).

Según la 1NF, esto está mal.

¿Por qué?
Primero, porque las consultas de búsqueda van a volver bastante
engorrosas.
Para saber si tengo un determinado gusto, no tenemos forma de 
saber en qué columna va a estar ese gusto.
Por ejemplo, si me piden saber si tenemos "chocolate", no sé si
este valor estaría en "sabor1", "sabor2" o "sabor3".

Por ende, tendríamos que hacer una pregunta de tipo:
    sabor1 = 'chocolate'
    OR sabor2 = 'chocolate'
    OR sabor3 = 'chocolate'

Y no solo eso. ¿Qué pasa si necesitamos después tener 4 posibles
gustos, y no 3?
Tendríamos que agregar un nuevo campo "sabor4".
Esto se puede hacer, pero en un servidor en producción con un
volumen de datos no despreciable, hacer este cambio puede tomar
*horas*.
Y sin mencionar que el día de mañana se puede necesitar un 5to
sabor. Y un 6to. Y un 7mo. Etc.

Por estas razones, *no* deberíamos usar grupos de columnas.

¿Cómo se resuelve, entonces?
Lo vemos un poquito. Se necesita usar "relaciones".


## Primary Key
La Primary Key (PK para los amigos) es el menor conjunto de campos
de una tabla que me permite identificar unívocamente a los 
registros.

Por supuesto, puede ser un conjunto de un solo elemento. De hecho, 
es lo más común. Especialmente porque el campo en cuestión es el
"id".

Estrictamente hablando, las tablas *no* están obligadas a tener una
PK. Dicho esto, es realmente raro que una tabla *no* tenga una PK.
Para los fines prácticos, siempre agregamos una.
Típicamente, es el campo "id".

Las PK tienen roles importantes en la base de datos:
- Las búsquedas que se realizan por la PK son las más rápidas 
que se soportan en la base.
- Los registros quedan ordenados físicamente en memoria por la
PK.
- La base de datos usa a veces la PK para hacer optimizaciones
propias.
- Son esenciales para las "relaciones".