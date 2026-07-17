/*
Vamos a aprender a escribir y ejecutar consultas SQL :D

Por cierto, esto es un comentario multilínea.

Dentro de este espacio (fuera del comentario) podemos escribir todas
las consultas que queramos, separándolas con ";" (sin comillas).

Las consultas (queries), recordamos, son las instrucciones que podemos
correr contra la base de datos.

Por ejemplo, vamos a crear la base de datos para poder luego sincronizar
el diagrama que hicimos.

Si quieren agregar comentarios de una línea, tienen que empezarlo con
un numeral seguido de un espacio.
O, el comentario más común de una línea, es escribir dos guiones medios
seguidos de un espacio.

## Tipos de SQL
SQL, como lenguaje, se divide en 2 categorías:
- DDL (Data Definition Language)
	Son las consultas que permiten administrar las estructuras de la 
    base de datos (el schema, tablas, campos, índices, etc).
    Por ejemplo, las consultas que empiezan con:
		SHOW, CREATE, ALTER, DROP
- DML (Data Manipulation Language)
	Son las consultas que permiten administrar los datos (registros) de 
    las tablas de la base de datos.
    Por ejemplo, las consultas que empiezan con:
		SELECT, INSERT, UPDATE, DELETE
        
## Creación del schema
Para crear el schema tenemos que usar la consulta "CREATE DATABASE" o
"CREATE SCHEMA".
Ambas van a llevar a continuación el nombre del schema.
Por ejemplo:
	CREATE DATABASE `prog2_2026_1_n`;
    
Si la base de datos ya existe, esta consulta tira un error.
Alternativamente, podemos agregarle la cláusula "IF NOT EXISTS" para que
no tire ese error, sino que simplemente solo trate de crearla si es que no
existe.
	CREATE DATABASE IF NOT EXISTS `prog2_2026_1_n`;

También se puede, y suele ser una buena idea, agregarle cuál es el charset
y collation que queremos usar.
Por ejemplo:
	CREATE DATABASE IF NOT EXISTS `prog2_2026_1_n` COLLATE 'utf8mb4_general_ci';
    
Si queremos eliminar una base de datos (por ejemplo, la creamos mal o ya no
nos interesa tenerla en el disco), podemos correr el query:
	DROP DATABASE `prog2_2026_1_n`;
    
Por supuesto, el nombre será el que quieran borrar.
Importante: La base de datos no pregunta si estamos seguros ni pide 
confirmación. Si ejecutan ese query, se borra la base entera.

En el último ejemplo del CREATE:
	CREATE DATABASE IF NOT EXISTS `prog2_2026_1_n` COLLATE 'utf8mb4_general_ci'; 

Vamos a notar que estamos usando dos tipos diferentes de comillas.
- Para el nombre de la base, usamos "backticks" o "tildes franceses" (``).
- Para el charset, usamos comillas simples ('').

Cumplen roles completamente diferentes cada una de esas comillas.
Los backticks son delimitadores de *estructuras*.
Las comillas simples son delimitadores de *valores*.

¿Las comillas dobles? No las usen.

Las comillas simples son "simples" de tipear.
Los backticks son más tediosos de escribir.
Es por esta razón que si los nombres de las estructuras respetan el 
formato que mencionamos en su momento, entonces los backticks se pueden
omitir.
*/
-- Este es un comentario de una línea.

-- Crear nuestro schema.
-- CREATE DATABASE `prog2_2026_1_n`;
-- CREATE DATABASE IF NOT EXISTS `prog2_2026_1_n`;
-- CREATE DATABASE IF NOT EXISTS `prog2_2026_1_n` COLLATE 'utf8mb4_general_ci'; 
CREATE DATABASE IF NOT EXISTS prog2_2026_1_n COLLATE 'utf8mb4_general_ci'; 

-- Si queremos eliminar la base de datos.
-- DROP DATABASE `prog2_2026_1_n`;
DROP DATABASE IF EXISTS prog2_2026_1_n;
-- DROP DATABASE prog2_2026_1_n;

-- Definimos que queremos trabajar con la base prog2_2026_1_n por
-- defecto.
USE prog2_2026_1_n;

/*
## Creción / inserción de registros: INSERT
Una vez que tenemos la tabla creada, podemos empezar a cargar datos en la
tabla.
Para esto, usamos la consulta INSERT, que tiene un par de variantes.

Sintaxis "normal":
	INSERT INTO <tabla>
	VALUES (<lista_de_valores>);
    
Dónde:
- <tabla>
	El nombre de la tabla donde queremos insertar.
- <lista_de_valores>
	Debe ser una lista de los valores, separados por coma, que queremos que
    el nuevo registro tenga.
    Debe haber un valor para cada campo de la tabla, en el mismo orden en
    que estén definidos los campos en la tabla.
    
### Nota sobre las sintaxis
Cuando hablemos de las sintaxis, vamos a seguir un par de convenciones
para explicarla:
- En mayúsculas va a estar lo que son palabras claves. Es decir, palabras
	que deben figurar de esa forma.
- Entre <> vamos a poner valores que deben reemplazarse por otros.
- Entre [] vamos a poner segmentos opcionales del query.
- Los "..." indican que la instrucción anterior puede repetirse múltiples
	veces.
*/
-- Vamos a insertar un registro en la tabla de usuarios.
-- Esta tabla tiene los campos: usuario_id, email, password, nombre y apellido.
-- Por lo tanto, para el registro, tenemos que escribir 5 valores.
-- Si el campo es AUTO_INCREMENT (como el id) podemos pasar NULL para que
-- el valor se genere solo.
INSERT INTO usuarios
VALUES (NULL, 'sara@za.com', 'asdasd', 'Sara', 'Za');

-- El query anterior nos va a decir que se ingresó correctamente ("una fila
-- afectada"), pero no nos la muestra. Esto es por ahorrar recursos.
-- Si lo queremos ver, podemos ejecutar:
SELECT * FROM usuarios;

-- ¿Qué pasa si ponemos más o menos valores que los que la tabla acepta?
INSERT INTO usuarios
VALUES (NULL, 'sara@za.com', 'asdasd', 'Sara', 'Za', 'qwe');

-- Nos tira el error:
-- Error Code: 1136. Column count doesn't match value count at row 1

-- Si hay campos "opcionales" (que acepten NULL), entonces podemos pasarles
-- ese valor.
INSERT INTO usuarios
VALUES (NULL, 'pepe@trueno.com', 'asdasd', NULL, NULL);

-- ¿Y si le pasamos NULL a un campo que no acepta NULL?
INSERT INTO usuarios
VALUES (NULL, 'pepe@trueno.com', NULL, NULL, NULL);

-- Nos tira el error: Error Code: 1048. Column 'password' cannot be null
-- Salvo que tenga un valor por defecto.

/*
# Sintaxis alternativa de INSERT
La sintaxis "común" no siempre es la más cómoda de escribir o leer.
Tiene 2 problemas:
1. No queda claro a simple vista qué valor corresponde a qué campo.
	Por ejemplo, si el query es:
		INSERT INTO usuarios
		VALUES (NULL, 'pepe@trueno.com', 'asdasd', NULL, NULL);
	
    Salvo que sepamos de antemano que las últimas dos son las columnas del nombre
    y del apellido, no sabemos a qué se refiere. Lo mismo con la del password.
    La del email "zafa" porque tiene un formato específico.
2. Si la tabla acepta varios campos NULL, es bastante tedioso tener que escribirlos
	una y otra vez.

Ambos problemas se pueden resolver usando la sintaxis "extendida":

	INSERT INTO <tabla> (<lista_de_campos>)
    VALUES (<lista_de_valores>);
    
Dónde:
- <tabla>
	El nombre de la tabla donde hacer el INSERT.
- <lista_de_campos>
	La lista de los campos a los que les vamos a asignar un valor en el nuevo
    registro, y el orden en el que lo vamos a hacer.
    Cualquier campo no mencionado acá va a automáticamente recibir NULL.
- <lista_de_valores>
	La lista de los valores, separados por coma, que tiene el nuevo registro.
    Debe haber uno para cada campo de la <lista_de_campos>, en el orden ahí
    indicado.
*/
-- Insertamos un nuevo usuario con la sintaxis extendida.
INSERT INTO usuarios (email, password)
VALUES ('jperez@gmail.com', 'asdasd');

/*
# Insertando en masa
Si queremos, podemos insertar múltiples registros en una tabla con un solo INSERT.
En cualquiera de las dos sintaxis mencionadas, podemos hacer:
	VALUES (<lista_de_valores>) [, (<lista_de_valores>)] ...
*/
-- Insertamos algunos equipos de la NBA.
INSERT INTO equipos (ciudad, nombre)
VALUES ('San Antonio', 'Spurs'),
	('Los Angeles', 'Lakers'),
	('Los Angeles', 'Clippers'),
	('Boston', 'Celtics'),
	('Houston', 'Rockets'),
	('Denver', 'Nuggets'),
	('Toronto', 'Raptors');
    
SELECT * FROM equipos;

-- Insertemos una noticia, que tiene una FK con usuarios.
-- Recordemos: una FK debe llevar un valor de un ID de un usuario que exista.
INSERT INTO noticias (
	usuario_fk, 
    fecha_publicacion, 
    titulo, 
    sinopsis, 
    cuerpo, 
    imagen, 
    imagen_descripcion
)
VALUES (
	1,
	'2019-02-19 15:15:15',
	'Ginóbili sigue rompiendo récords',
	'Emanuel \'Manu\' Ginóbili viene rompiendo algunos récords tanto de su equipo como de la liga.',
	'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?',
	'manu.jpg',
	'Manu Ginóbili'
);

-- ¿Qué pasa si pasamos un valor a la FK que no existe?
INSERT INTO noticias (
	usuario_fk, 
    fecha_publicacion, 
    titulo, 
    sinopsis, 
    cuerpo, 
    imagen, 
    imagen_descripcion
)
VALUES (
	7,
	'2019-02-27 09:52:46',
	'Houston Rockets lidera la conferencia',
	'De la mano de James Harden, los Rockets se apuntan como candidatos para ganar los playoff.',
	'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?',
	'rockets-logo.jpg',
	'Houston Rockets Logo'
);

-- Tira un error:
-- Error Code: 1452. Cannot add or update a child row: a foreign key constraint 
-- fails (`prog2_2026_1_n`.`noticias`, CONSTRAINT `fk_noticias_usuarios1` FOREIGN 
-- KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`))
-- Nos dice que no puede agregar o actualizar una "fila hijo", porque falla la
-- FK, y nos detalla cuál.
-- "Fila hijo" se refiere a la fila que lleva la FK.
INSERT INTO noticias (
	usuario_fk, 
    fecha_publicacion, 
    titulo, 
    sinopsis, 
    cuerpo, 
    imagen, 
    imagen_descripcion
)
VALUES (
	1,
	'2019-02-27 09:52:46',
	'Houston Rockets lidera la conferencia',
	'De la mano de James Harden, los Rockets se apuntan como candidatos para ganar los playoff.',
	'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?',
	'rockets-logo.jpg',
	'Houston Rockets Logo'
);

INSERT INTO noticias (
	usuario_fk, 
    fecha_publicacion, 
    titulo, 
    sinopsis, 
    cuerpo, 
    imagen, 
    imagen_descripcion
)
VALUES (
	1,
	'2019-03-01 19:11:43',
	'Toronto Raptors queda primero en el Este',
	'Los Raptors de Lowry y DeRozan se quedan con el primer lugar de su conferencia.',
	'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?',
	'raptors-logo.jpg',
	'Toronto Raptors Logo'
), (
	1,
	'2019-03-01 22:53:24',
	'Denver se queda corto por un partido',
	'Emanuel \'Manu\' Ginóbili viene rompiendo algunos récords tanto de su equipo como de la liga.',
	'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae ab sapiente harum? At repudiandae libero quis laudantium tempore, quas hic animi recusandae, provident, sunt aliquam qui dolorem sint earum unde?',
	'nuggets-logo.jpg',
	'Denver Nuggets Logo'
);

-- Finalmente, nos queda grabar registros de la tabla noticias_tienen_equipos.
-- Esta se compone de dos FKs, así que vamos a tener que armar los registros
-- en consecuncia.
INSERT INTO noticias_tienen_equipos (noticia_fk, equipo_fk)
VALUES 
	(1, 1), 
    (1, 2), 
    (1, 3), 
    (1, 5), 
    (3, 5), 
    (3, 2), 
    (4, 7), 
    (4, 4), 
    (5, 6);

SELECT * FROM usuarios;
SELECT * FROM noticias;
SELECT * FROM equipos;
SELECT * FROM noticias_tienen_equipos;

/*
# Actualización de registros: UPDATE
Para actualizar registros de una tabla, usamos la sintaxis:

	UPDATE <tabla>
    SET <campo> = <valor> [<campo> = <valor>] ...
    [WHERE <condicion>]
    
Dónde:
- <tabla>
	La tabla donde realizar la actualización.
- <campo>
	El campo de la tabla al que le queremos asignar un nuevo valor.
- <valor>
	El nuevo valor.
- <condicion>
	La condición (o condiciones) que el registro debe cumplir para ser afectado
    por la consulta.
*/
-- Vamos a agregar un nombre al usuario 2.
UPDATE usuarios
SET nombre = 'Pepe'
WHERE usuario_id = 2;

-- Le agregamos un apellido y le cambiamos el password al usuario 2.
UPDATE usuarios
SET apellido = 'Trueno', password = 'qweqwe'
WHERE usuario_id = 2;

-- ¿Qué pasa si no ponemos el WHERE? Respuesta: Mass update :D
UPDATE usuarios
SET password = 'asdasd';


/*
# Consultas de eliminación: DELETE
La consulta DELETE nos permite eliminar registros.
No confundir con DROP que elimina tablas o schemas.

Sintaxis:
	DELETE FROM <tabla>
    [WHERE <condiciones>];
*/

-- Borramos el usuario con id 3.
DELETE FROM usuarios
WHERE usuario_id = 3;

-- Tratamos de hacer un "mass delete".
DELETE FROM usuarios;

-- Lo anterior falla, por lo que puede parecer en una primera instancia que
-- no se puede hacer esto.
-- Pero, si leemos el error:
--  Error Code: 1451. Cannot delete or update a parent row: a foreign key 
--  constraint fails (`prog2_2026_1_n`.`noticias`, CONSTRAINT 
--  `fk_noticias_usuarios1` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` 
--  (`usuario_id`))
-- Lo que falla no es el "mass delete" en sí, sino que está protestando por la
-- FK en noticias.
-- Las noticias referencian a algún usuario, que sería quien las publicó. En
-- nuestro caso, todas apuntan al usuario 1.
-- Como hablamos de las FKs, por defecto, no nos permite eliminar registros
-- si están siendo referenciados por alguna FK. Esto asegura la "integridad
-- referencial".
-- Para poder eliminar, vamos a necesitar o:
-- - Deshabilitar los chequeos de FKs.
-- - Configurar las FKs para que eliminen automáticamente los registros que 
-- 	referencian los registros que eliminamos.
-- - Eliminar manualmente primero los registros con las FKs.

-- Para poder borrar todos los usuarios, necesitamos borrar todas las noticias.
-- Para poder borrar las noticas, necesitamos borrar todas las noticias_tienen_equipos.

DELETE FROM noticias_tienen_equipos;
DELETE FROM noticias;
DELETE FROM usuarios;
DELETE FROM equipos;

-- Siempre tengan MUCHO CUIDADO de no olvidar el WHERE en el DELETE (o UPDATE).

/*
# Consultas de selección: SELECT
Las consultas de selección son las que permiten leer y obtener datos.
Se llaman así porque "seleccionan" qué datos queremos obtener como resultado.

Antes de empezar a usarlas, es importante explicar un concepto clave:
	Conjunto de resultados / result set / record set

Un resultset es un conjunto de registros que se genera a pedido de un SELECT.
Todos los registros de un resultset van a tener la misma cantidad de columnas y
el mismo orden de ellas: las que el resultset define.

Los resultsets se crean a pedido, a veces se cachean, pero no es algo que la
base de datos en sí guarde. Se generan para responder un query, y se desechan.

Se pueden crear con valores de todo tipo:
- Constantes
- Ecuaciones
- Llamados a funciones
- Datos de una tabla

Lo más común, por supuesto, es lo último.

SIEMPRE que corramos un SELECT y no haya un error, vamos a recibir un resultset.
En cambio, NUNCA un SELECT puede modificar datos. Es una operación de solo
lectura.

Los resultset suelen representarse como tablas.

## Sintaxis
	SELECT <valores> [FROM <tablas>]
    
Dónde
- <valores>
	La lista, separada por coma, de valores que queremos traer.
    Pueden ser cualquiera de los citados arriba.
- <tablas>
	Las tablas de las cuales queremos leer.
*/
-- Traemos un valor constante.
SELECT 42;

-- Cuando traemos un select, los nombres de las columnas por defecto son los
-- valores que pedimos.
-- Los valores que traen los registros son los resultados.
-- En valores constantes como 42, nos van a coincidir ambos.

-- Traemos una ecuación.
SELECT 40 + 2;

-- En este caso, vemos que la columna se llama "40 + 2", y el valor es el 
-- resultado.
-- En algunos casos, el nombre que se detecta por defecto no es lo que queremos
-- realmente tener. O no es práctico de usar / escribir.
-- Por eso, SQL nos permite poner "alias" a los campos del select.

-- Traemos lo mismo, pero poniendo un alias.
SELECT 40 + 2 AS 'La respuesta a la vida, el universo, y todo lo demás';

-- Si bien lo que acabamos de ver es, estrictamente hablando, el select más
-- básico posible (pedir un solo campo constante), generalmente lo más básico
-- que se suele usar es traer los datos de una tabla.

-- Traemos todas las columnas de la tabla.
SELECT * FROM usuarios;

-- El "*" es un comodín que significa, en este contexto, "todos los campos".
-- Pero puede ser que no nos interesen *todos* los campos. Puede ser que
-- solo me interesen algunos.
-- Por supuesto, podemos especificar eso:
SELECT nombre, apellido FROM usuarios;

-- Podemos usar la base de datos de "basket" que les pasé para ver más variedad
-- de resultados y pruebas.

-- El orden en que los pedimos es en el que lo queremos en el resultset.
SELECT apellido, nombre FROM usuarios;

SELECT * FROM noticias;

-- ¿Qué pasa si queremos filtrar resultados?
-- En ese caso, podemos agregar la cláusula WHERE.
-- Esta cláusula permite especificar todas las condiciones que queremos que
-- los valores de los registros de la tabla cumplan para ser incluidos en el
-- resultset.
SELECT * FROM jugadores;

-- Supongamos que queremos traer los jugadores que llevan 3 años ya jugados
-- en la NBA.
SELECT * FROM jugadores
WHERE anios_pro = 3;

-- Casi siempre, las condiciones van a llevar el formato de:
-- 		campo operador valor
-- Es decir, va a ser un campo comparado con un valor, u opcionalmente:
-- 		campo operador campo
-- Campos cuyos valores se comparan al de otros campos.
-- Dentro de los operadores está el "=" que es comparación "exacta".
-- No es una comparación estricta. Por ejemplo: 
-- - Puede incluir casting de strings a números o viceversa.
-- - Por defecto, los valores en SQL son case-insensitive.

-- Por supuesto, podemos traer también por "no igual".
-- Pedimos los jugadores que *no* lleven 3 años jugando.
SELECT * FROM jugadores
WHERE anios_pro != 3;

-- O alternativamente:
SELECT * FROM jugadores
WHERE anios_pro <> 3;

-- En SQL podemos usar tanto el operador "!=" como "<>" para el "no igual".

-- También podemos hacer búsquedas por rangos.
-- Traemos los jugadores que miden más de 2.01 metros.
SELECT * FROM jugadores
WHERE altura > 2.01;

-- Si queremos incluir los que miden exactamente 2.01:
SELECT * FROM jugadores
WHERE altura >= 2.01;

-- También están el menor y menor o igual:
SELECT * FROM jugadores
WHERE altura < 2.01;

SELECT * FROM jugadores
WHERE altura <= 2.01;

-- Como pueden imaginarse, podemos combinar condiciones usando los operadores
-- lógicos: AND, OR.

-- Traemos los jugadores que miden entre 2.01 y 2.11 metros inclusive.
SELECT * FROM jugadores
WHERE altura >= 2.01
AND altura <= 2.11;

-- Como esto es algo muy común de hacer, y es medio tedioso tener que escribir
-- el nombre del campo dos veces, es que SQL acepta un operador llamado "BETWEEN".

-- Lo mismo pero usando BETWEEN.
SELECT * FROM jugadores
WHERE altura BETWEEN 2.01 AND 2.11;

-- Opcionalmente, puede negarlo con "NOT", si quieren traer lo opuesto.
SELECT * FROM jugadores
WHERE altura NOT BETWEEN 2.01 AND 2.11;

-- Otro caso común que requiere múltiples condiciones es buscar los jugadores
-- que ya hayan jugado 3 años, 5 años o 6 años como profesionales.
SELECT * FROM jugadores
WHERE anios_pro = 3
OR anios_pro = 5
OR anios_pro = 6;

-- Por suerte, SQL también tiene un operador para simplificar lo anterior: IN
SELECT * FROM jugadores
WHERE anios_pro IN (3, 5, 6, 8);

-- Por supuesto, se puede también negar.
SELECT * FROM jugadores
WHERE anios_pro NOT IN (3, 5, 6, 8);

-- Una cosa que podemos observar, es que siempre los valores vinieron ordenados
-- de la misma manera: Por la PK.
-- Pero hay casos donde podemos preferir otros ordenamientos. 
-- Por ejemplo, supongamos que queremos traer los jugadores ordenados por el
-- apellido:
SELECT * FROM jugadores
ORDER BY apellido;

-- También podría ser:
SELECT * FROM jugadores
ORDER BY apellido ASC;

-- ORDER BY va después del WHERE (si está presente).
-- Es seguido por el campo que queremos ordenar.
-- Por defecto, el ordenamiento es ascendente. Si lo preferimos descendente:
SELECT * FROM jugadores
ORDER BY apellido DESC;

-- ¿Qué pasó con los jugadores que tienen apellidos repetidos?
-- Generalmente, suelen venir sub-ordenados por la PK. Aunque no siempre.
-- Queda a criterio del análisis de SQL qué usar.
-- Si tenemos una preferencia, podemos expresamente aclararla:
SELECT * FROM jugadores
ORDER BY apellido, nombre;

-- Cada uno de ellos puede, independientemente, indicar si es ASC o DESC.