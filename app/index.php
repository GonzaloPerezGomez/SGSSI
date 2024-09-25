<<<<<<< HEAD
<html>
<head>
<title> registro </title>
<script src="comprobacionDeDatos.js"></script>
</head>

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

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['id']}</td>
    <td>{$row['nombre']}</td>
   </tr>";

}
  
  echo '<a href="login.php">Sign in</a>';
  echo '<a href="register.php">Register</a>';

?>
