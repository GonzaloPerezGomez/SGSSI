<?php
session_start();
// conexión a la base de datos
//guarda el nombre del servidor a conectar
$servername = "db";
//guarda el nombre del usuario necesario para acceder al servidor
$username = "admin";
//guarda la contraseña del usuario en una variable
$password = "test";
//guarda el nombre del de la base de datos a la que quiere acceder
$dbname = "database";

//se realiza la conexión en el servidor con el usuario introducido en la base de datos introducida (db, database)
$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión

// si la variable que guarda la conexión es un error 
if ($conn->connect_error) {
	//detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión 
    die("Connection failed: " . $conn->connect_error);
}

// comprobar si se ha enviado el formulario
if (isset($_POST['register_submit'])) {
	// guardar la información del formulario
    $nombre = $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $DNI = $_POST['numeroDNI'];
    $letraDNI = $_POST['letraDNI'];
    $telefono=$_POST['telefono'];
    $nacimiento=$_POST['nacimiento'];
    $email=$_POST['email'];
    $usuario=$_POST['usuario'];
    $contraseña=$_POST['contrasena'];

	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$sql = "SELECT usuario from usuarios where usuario = '" . $usuario . "'";
	//se ejecuta la instrucción
	$result = $conn->query($sql);
	if ($result ->num_rows > 0){ //comprobar si hay otro usuario con ese nombre de usuario
		echo "<script> window.alert('Escoja otro nombre de usuario, ese no está disponible'); </script>";}
	else{
		//guarda la instrucción de SQL que quere utilizar, en este caso un insert
		$sql = "INSERT INTO usuarios (nombre, apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena) VALUES ('". $nombre ."', '" . $apellido . "' , '" . $DNI . "', '" . $letraDNI . "', '" . $telefono . "' , '" . $nacimiento . "' , '" . $email . "' , '" . $usuario . "' , '" . $contraseña . "'  )";
    	//se comprueba si la instrucción se ha ejecutado de forma correcta
		if ($conn->query($sql) === TRUE) {
			//se recoge el id del usuario para despues crear su sesión
			$sql = "SELECT idUsuario from usuarios where usuario = '" . $usuario . "' and contrasena='" . $contraseña . "'";
			$result = $conn->query($sql);
			$returnedValues = $result->fetch_assoc();
			$_SESSION['user_id'] = $returnedValues['idUsuario'];
			echo "<script>
			window.alert('Se ha registrado correctamente :)');
			window.location.href = 'index.php';
			</script>";

			//se cierra la conexión
			$conn->close();
			exit();
		} 
		else {
			//la instrucción no es válida
    		echo "Error: " . $sql . "<br>" . $conn->error;
    	}
		
	//se cierra la conexión
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