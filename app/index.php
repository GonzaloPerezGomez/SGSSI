<html>
<head>
<meta charset="UTF-8">
<title> index </title>
<script src="comprobacionDeDatos.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>

<body>

<h1>Página principal</h1>

<div class="button-container">
    <a href="login.php" class="button">Sign in</a>
    <a href="register.php" class="button">Register</a>
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



$query = mysqli_query($conn, "SELECT * FROM usuarios")
   or die (mysqli_error($conn));
?>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody>
    <?php
while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['id']}</td>
    <td>{$row['nombre']}</td>
   </tr>";

}
?>
</tbody>
</table>

<a class="button" href="anadir_libro.php">Añadir libro</a>

<?php
$query = mysqli_query($conn, "SELECT titulo, autor FROM libro")
   or die (mysqli_error($conn));
?>

<table border="1">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Titulo</th>
            <th>Autor</th>
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
            <a class=button href=edit_libro.php></a>
            <a class=button></a>
        </div>
    </td>
   </tr>";

}
?>
</tbody>
</table>




</body>
</html>
