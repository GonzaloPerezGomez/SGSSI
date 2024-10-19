<html>
<head>
<meta charset="UTF-8">
<!-- título que se pondrá en la página --> 
<title> Catálogo </title>
<!-- indica desde que script modela la pagina web --> 
<link rel="stylesheet" href="estilo.css">
</head>

<body>
<br>
<!--Contenedor de botones-->
<div class="button-container">
    <!--botón normal que al pulsar se redirige a la página add_item.php-->
    <a class="button" href="add_item.php">Añadir libro</a>
    <!-- botón normal que al pulsar se redirige a la página index.php-->
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

  //se realiza la conexión en el servidor con el usuario introducido en la base de datos introducida (db, database)
  $conn = mysqli_connect($hostname,$username,$password,$db);

  // comprobar conexión
  
  // si la variable que guarda la conexión es un error 
  if ($conn->connect_error) {
    //detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión
    die("Database connection failed: " . $conn->connect_error);
  }

?>

<?php
//Guardamos el valor que obtenemos al realizar una select en la base de datos, si hay error paramos el proceso
$query = mysqli_query($conn, "SELECT idLibro, titulo, autor, ISBN  FROM libro")
   or die (mysqli_error($conn));
?>
<!--crea una tabla-->
<table border="1">
    <thead>
        <tr>
            <!--titulo de la primera columna-->
            <th>Imagen</th>
            <!--titulo de la segunda columna-->
            <th>Título</th>
            <!--titulo de la tercera columna-->
            <th>Autor</th>
            <!--titulo de la cuarta columna-->
            <th>Editar / Borrar</th>
        </tr>
    </thead>
<tbody>

<?php
//mientras haya filas sin estudiar
while ($row = mysqli_fetch_array($query)) {
  //guardamos nombre de la portada del libro
  $nombre = "libros/" . strtolower($row['titulo'] . ".jpeg"); //imagen
  //quitemos los espacios por guiones
  $nombre = str_replace(" ", "-", $nombre);
  //imprimimos por pantalla
  echo 
   "
   <tr>
    <td>
        <!--referencia as how_item.php cargado con el ISBN del libro-->
        <a href=show_item.php?ISBN=" . $row['ISBN'] . ">
        <!--la foto de la portada del libro-->
        <img src='$nombre' style=width:60px ; height:auto ;>
        </a>
    </td>
    <!--informacion del titulo del libro-->
    <td>{$row['titulo']}</td>
    <!--informacion del autor del libro-->
    <td>{$row['autor']}</td>
    <td>
        <!--contenedor de los botones de modificacion y eliminacion con su respectivas imagenes-->
        <div class=button-container>
            <a class=button href=modify_item.php?idLibro=" . $row['idLibro'] . ">
            <img src='image/editar.png' style='height:20px;'></a>
            <a class=button href=delete_item.php?ISBN=" . $row['ISBN'] . ">
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
