<html>
<head>
<meta charset="UTF-8">
<title> catalogo </title>
<link rel="stylesheet" href="estilo.css">
</head>

<body>

<div class="button-container">
    <a class="button" href="add_item.php">Añadir libro</a>
    <a class="button" href="index.php">Voler a inicio</a>

</div>

<?php
  // phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }

?>

<?php
$query = mysqli_query($conn, "SELECT titulo, autor, ISBN  FROM libro")
   or die (mysqli_error($conn));
?>

<table border="1">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Editar / Borrar</th>
        </tr>
    </thead>
    <tbody>
    <?php
while ($row = mysqli_fetch_array($query)) {
  $nombre = "image/" . strtolower($row['titulo'] . ".jpeg");
  $nombre = str_replace(" ", "-", $nombre);
  echo 
   "
   <tr>
    <td><img src=$nombre
    style=width:60px ; height:auto ;></td>
    <td>{$row['titulo']}</td>
    <td>{$row['autor']}</td>
    <td>
        <div class=button-container>
            <a class=button href=modify_item.php?ISBN=" . $row['ISBN'] . ">
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
