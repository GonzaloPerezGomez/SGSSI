<html>
<head>
<!--                    --> 
<meta charset="UTF-8">
<!-- titulo que se pondra en la pagina --> 
<title> Catálogo </title>
<!-- indica desde que script modela la página web--> 
<link rel="stylesheet" href="estilo.css">
</head>

<body>


<!--contenedor de botones con los siguientes botones--> 

<div class="button-container">
    // botón normal que al pulsar se redirige a la página add_item.php
    <a class="button" href="add_item.php">Añadir libro</a>
    // botón normal que al pulsar se redirige a la página index.php
    <a class="button" href="index.php">Volver a inicio</a>

</div>

<?php
// conexión a la base de datos

//guarda el nombre del servidor a conectar
$hostname = "db";
//guarda el nombre del usuario necesario para acceder al servidor
$username = "admin";
//guarda la contraseña del usuario en una variable
$password = "test";
//guarda el nombre del de la base de datos a la que quiere acceder
$db = "database";

//se realiza la conexión en el servidor con el usuario introducido en la besa de datos introducida (db, database)
$conn = mysqli_connect($hostname,$username,$password,$db);

// comprobar conexión

// si la variable que guarda la conexión es un error
if ($conn->connect_error) {
    //para el proceso(die) e indica por pantalla la causa del fallo de conexión
    die("Database connection failed: " . $conn->connect_error);
  }

?>

<?php
//realiza el comando select en la base de datos y almacena el resultado en una variable si no  es posible quita el proceso e indica el error
$query = mysqli_query($conn, "SELECT idLibro, titulo, autor, ISBN  FROM libro")
   or die (mysqli_error($conn));
?>
<!--Crea una tabla con bordes de x1-->
<table border="1">
    <thead>
        <tr>
            <!--titulo de la primera columna de la tabla -->
            <th>Imagen</th>
            <!--titulo de la segunda columna de la tabla -->
            <th>Título</th>
            <!--titulo de la tercera columna de la tabla -->
            <th>Autor</th>
            <!--titulo de la cuarta columna de la tabla -->
            <th>Editar / Borrar</th>
        </tr>
    </thead>
    <tbody>
    <?php
//mientra haya filas sin analizar
while ($row = mysqli_fetch_array($query)) {
  //se guarda la imagen del libro que aparece en esa fila en una variable
  $nombre = "libros/" . strtolower($row['titulo'] . ".jpeg"); //imagen
  //se cambia los espacios por guiones
  $nombre = str_replace(" ", "-", $nombre);
  echo 
   "
   <tr>
    <td>
        <!--al pulsar la imagen va a show_item.php del libro pulsado-->
        <a href=show_item.php?ISBN=" . $row['ISBN'] . ">
         <!--refencia a la imagen de la portada del libro para mostrarlo por pantalla-->
        <img src='$nombre' style=width:60px ; height:auto ;>
        </a>
    </td>
     <!--muestra el nombre del libro en la tabla-->
    <td>{$row['titulo']}</td>
     <!--muestra el nombre del autor del libro en la tabla-->
    <td>{$row['autor']}</td>
    <td>
        <!--contenedor de botones-->
        <div class=button-container>
             <!--boton normal que al pulsar te redirige a modify_item.php -->
            <a class=button href=modify_item.php?idLibro=" . $row['idLibro'] . ">
             <!--aparencia que tendra el boton de modificar-->
            <img src='image/editar.png' style='height:20px;'></a>
             <!-- boton normal que al pulsar te redirige a delete_item.php-->
            <a class=button href=delete_item.php?ISBN=" . $row['ISBN'] . ">
             <!--aparencia que tendra el boton de borrado -->
            <img src='image/borrar.png' style='height:20px;'></a>
        </div>
    </td>
   </tr>";

}
?>

</tbody>
</table>

</body>
</html>
