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
if (isset($_POST['register_submit'])) {
	// Get the name and password from the form
    $nombre = $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $DNI = $_POST['numeroDNI'];
    $telefono=$_POST['telefono'];
    $nacimiento=$_POST['fechaNacimiento'];
    $email=$_POST['correo'];
    $usuario=$_POST['nombreUsuario'];
    $contraseña=$_POST['contrasena'];
    $sql = "INSERT INTO usuarios (nombre, apellido,numeroDNI,telefono,nacimiento,email,usuario,contrasena)
    VALUES ('". $nombre ."', '" . $apellido . "' , '" . $DNI . "', '" . $telefono . "' , '" . $nacimiento . "' , '" . $email . "' , '" . $usuario . "' , '" . $contraseña . "'  )";
    if ($conn->query($sql) === TRUE) {
		echo "<script>
				window.alert('Se ha registrado correctamente :)');
				window.location.href = 'login.php';
			</script>";
		$conn->close();
		exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Prepare and execute the SQL statement to insert the data
   // Close the database connection
	$conn->close();
}


?>

<html>
<head>
<title> Registrarse </title>
<script src="comprobacionDeDatos.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<form name="register_form" method="post">
		<p align="center">Introduzca la información pedida a continuación:</p>
		Nombre completo:<br>
		<input type="text" name="nombre" placeholder="Nombre">  <input type="text" name="apellido" placeholder="Apellido"><br>
		DNI: <br>
		<input type="text" name="numeroDNI" placeholder="1234567"> <input type="text" name="letraDNI" placeholder="Letra DNI"> <br>
  	
  		Teléfono:<br>
  		<input type="text" name="telefono" placeholder="123456789"> <br>
		Fecha de Nacimiento:<br>
		<input type="text" name="fechaNacimiento" placeholder="AAAA-MM-DD"/><br>
		Email:<br>
		<input type="text" name="correo" placeholder="example@xxx.yyy" > <br>
		Nombre Del usuario<br>
		<input type="text" name="nombreUsuario"><br>
		Contraseña:<br>
		<input type="text" name="contrasena"> <br>

		<br>
		<input type="submit" name="register_submit" class ="button" value="Enviar" style="color:black;font-weight:bold;" onclick="comprobardatos()">
	</form>
		
	<a href="index.php" class="button">Volver a inicio</a>

	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>


	
	
	
	
	
	
	
	
	
	
	
