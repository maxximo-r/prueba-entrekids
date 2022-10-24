# prueba-entrekids
Codigos para la solucion de prueba EntreKids
Preguntas

1)	Basado en el modelo de la imagen 1 obtener lo siguiente:
a)	La venta mensual por proveedor, mencionando si la venta pertenece a una experiencia o a un producto físico.
b)	El “activo” más vendido en cantidad y el “activo” que generó más dinero por proveedor mensualmente.
c)	El “activo” más cancelado por proveedor mensualmente.
A considerar:
i)	Debe ser obtenido por una consulta en MySQL.
ii)	La transacción contiene el monto de la venta en la columna total.
iii)	Se genera una fila en ítem por cada “activo” vendido.
iv)	Para poder diferenciar si el “activo” es un producto físico, o una experiencia, se puede ver si existe una fila en paquete (en caso de ser producto) o si existe una fila en entrada (en caso de ser experiencia).
v)	En la tabla item, la columna evento_id hace referencia a la tabla actividad_evento.
vi)	Para obtener los “activos” cancelados, el estado de estos es “Cancelado”.
2)	Basándose en el modelo de la imagen 1, proponer que otro dato “relevante” se podría obtener que sea diferente al de la pregunta 1. La respuesta a esta pregunta debe ser una descripción solo con palabras, no con SQL o código.
3)	Basándose en los datos obtenidos en la respuesta 1.a, escribir una función en PHP que recorra los datos y genere un código HTML en donde haya un listado donde se muestre el proveedor, lo vendido y a qué categoría pertenece (experiencia o producto físico) y que al hacer clic en el nombre del proveedor me dirija a la página del proveedor la cual se encuentra en “admin.entrekids.cl/proveedor/<id>” (pagina de fantasia).
A considerar:
a)	El código generado en HTML por PHP debe ser guardado en un archivo de extensión .html
b)	No es necesario que el HTML tenga CSS o algún tipo de estilo.
c)	El código en PHP debe poder ejecutarse a través de la consola, ejemplo: “php prueba.php”.
d)	La data dummy pueden obtenerla desde el siguiente link.
4)	Al hacer clic en el link mencionado en la respuesta 3, deben verse los datos obtenidos en la respuesta 1.b y 1.c en una tabla que tenga por filas el mes y por columnas el:
a)	"Activo" más vendido en cantidad.
b)	"Activo" que generó más dinero.
c)	"Activo" más cancelado.


Condiciones
1)	Las respuestas pueden estar en un repositorio el cual deben dejar público para poder ingresar y revisar las respuestas, o puede enviarlas dentro de una carpeta de un drive que también este público.
2)	La respuesta deben enviarla a vicente.gh@entrekids.cl con el asunto: "Nombre Apellido | Developer Full-Stack" y dentro agregar el link en donde encontrar las respuestas.
3)	Si existe alguna duda, pueden enviarla al mismo correo mencionado anteriormente.

<img src="https://i.imgur.com/Wnll6Px.png">
