<?php
session_start();
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
    $letraDNI = $_POST['letraDNI'];
    $telefono=$_POST['telefono'];
    $nacimiento=$_POST['fechaNacimiento'];
    $email=$_POST['correo'];
    $usuario=$_POST['nombreUsuario'];
    $contraseña=$_POST['contrasena'];

	$sql = "SELECT usuario from usuarios where usuario = '" . $usuario . "'";
	$result = $conn->query($sql);
	if ($result ->num_rows > 0){
		echo "<script> window.alert('Usuario repetido'); </script>";}
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
			exit();} 
		else {
    		echo "Error: " . $sql . "<br>" . $conn->error;
    	}

    // Prepare and execute the SQL statement to insert the data
	// Close the database connection
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
  		<input type="text" name="telefono" placeholder="123456789"><br>
		Fecha de Nacimiento:<br>
		<input type="text" name="fechaNacimiento" placeholder="AAAA-MM-DD"/><br>
		Email:<br>
		<input type="text" name="correo" placeholder="example@xxx.yyy" required> <br>
		Nombre de usuario<br>
		<input type="text" name="nombreUsuario" required><br>
		Contraseña:<br>
		<input type="text" name="contrasena" required> <br>

		<br>
		<input type="submit" value="Registrarme" name="register_submit" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
	</form>
		
	<a href="index.php" class="button">Volver a inicio</a>

	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>


	
	
	
	
	
	
	
	
	
	
	
