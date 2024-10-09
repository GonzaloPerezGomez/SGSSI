<?php
session_start();
// conexión a la base de datos
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// comprobar si se ha enviado el formulario
if (isset($_POST['register_submit'])) {
	// guardar la info del formulario
    $nombre = $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $DNI = $_POST['numeroDNI'];
    $letraDNI = $_POST['letraDNI'];
    $telefono=$_POST['telefono'];
    $nacimiento=$_POST['nacimiento'];
    $email=$_POST['email'];
    $usuario=$_POST['usuario'];
    $contraseña=$_POST['contrasena'];

	$sql = "SELECT usuario from usuarios where usuario = '" . $usuario . "'";
	$result = $conn->query($sql);
	if ($result ->num_rows > 0){ //comprobar si hay otro usuario con ese nombre de usuario
		echo "<script> window.alert('Escoja otro nombre de usuario, ese no está disponible'); </script>";}
	else{
		$sql = "INSERT INTO usuarios (nombre, apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena) VALUES ('". $nombre ."', '" . $apellido . "' , '" . $DNI . "', '" . $letraDNI . "', '" . $telefono . "' , '" . $nacimiento . "' , '" . $email . "' , '" . $usuario . "' , '" . $contraseña . "'  )";
    	if ($conn->query($sql) === TRUE) {
			$sql = "SELECT idUsuario from usuarios where usuario = '" . $usuario . "' and contrasena='" . $contraseña . "'";
			$result = $conn->query($sql);
			$returnedValues = $result->fetch_assoc();
			$_SESSION['user_id'] = $returnedValues['idUsuario'];
			echo "<script>
			window.alert('Se ha registrado correctamente :)');
			window.location.href = 'index.php';
			</script>";
			$conn->close();
			exit();
		} 
		else {
    		echo "Error: " . $sql . "<br>" . $conn->error;
    	}

	$conn->close();
}
}

?>

<html>
<head>
	<title> Registrarse </title>
	<script src="comprobacionDeDatos.js"></script>
	<link rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="register_form" method="post"  onsubmit="return comprobardatosRegistro()">
		<p align="center">Introduzca la información pedida a continuación para registrarse:</p>
		Nombre completo:<br>
		<input type="text" name="nombre" placeholder="Nombre" required>  <input type="text" name="apellido" placeholder="Apellido" required><br>
		DNI: <br>
		<input type="text" name="numeroDNI" placeholder="12345678" required> <input type="text" name="letraDNI" placeholder="Letra DNI" required> <br>
  		Teléfono:<br>
  		<input type="text" name="telefono" placeholder="123456789"required><br>
		Fecha de Nacimiento:<br>
		<input type="text" name="nacimiento" placeholder="AAAA-MM-DD"required/><br>
		Email:<br>
		<input type="text" name="email" placeholder="example@xxx.yyy" required> <br>
		Nombre de usuario<br>
		<input type="text" name="usuario" required><br>
		Contraseña:<br>
		<input type="text" name="contrasena" required> <br>

		<br>
		<input type="submit" value="Registrarme" name="register_submit" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
	</form>
		
	<div class="button-container">
		<a href="index.php" class="button">Volver a inicio</a>
	</div>
	
<html>