<?php
// Connect to the database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "aaaaaaa";
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get the name and password from the form
    $nombre = $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $DNI = $_POST['numeroDNI'];
    $telefono=$_POST['telefono'];
    $nacimiento=$_POST['nacimiento'];
    $email=$_POST['email'];
    $usuario=$_POST['usuario'];
    $contraseña=$_POST['contrasena'];
    $sql = "INSERT INTO usuarios (nombre, apellido,numeroDNI,telefono,nacimiento,email,usuario,contrasena)
    VALUES ('". $nombre ."', '" . $apellido . "' , '" . $DNI . "', '" . $telefono . "' , '" . $nacimiento. "' , '" . $email. "' , '" . $usuario . "' , '" . $contraseña. "'  )";
    echo $sql;
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Prepare and execute the SQL statement to insert the data
   
}

// Close the database connection
$conn->close();
?>


