<html>
<head>
<title> index </title>
<script src="comprobacionDeDatos.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>

<body>

<h1>Página principal</h1>

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


<div class="button-container">
    <a href="login.php" class="button">Sign in</a>
    <a href="register.php" class="button">Register</a>
</div>

</body>
</html>
