<?php
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php"); 
    exit();
}

// conexión a la base de datos
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);
//comprobar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId=$_SESSION['user_id'];
$query = "SELECT nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena FROM usuarios WHERE idUsuario = " . $userId;

if($stmt = $conn->prepare($query)){     //prepara la consulta
	$stmt->execute();                   //se ejecuta la consulta
	$result = $stmt->get_result();      //el resultado se cuarda en la variable $result
	if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
		$infousuario = $result->fetch_assoc();//obtenemos el usuario
	}
	else{
		echo "No attributes found for user ID: " . $userId;
	}
}
else{
	echo "Conexión fallida";
}

// cerrar conexión
$conn->close();
?>


<html>
<head>
	<title> Información de usuario </title>
	<link rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="show_item_form" method="POST">
		<?php
        //el readonly es para que no se pueda editar, es un formulario pero sin poder editarlo

		if (isset($_SESSION['user_id'])) {
		echo
		"<br>
		Nombre completo:<br>
		<input type= text name= nombre value= " . $infousuario['nombre'] . " readonly>
		<input type= text  name= apellido value=  " . $infousuario['apellido'] . " readonly> <br>
  		DNI:<br>
  		<input type= text  name= numeroDNI value= " . $infousuario['numeroDNI'] . " readonly> <br>
		<input type= text  name= letraDNI value= " . $infousuario['letraDNI'] . " readonly><br>
		Teléfono:<br>
		<input type= text  name= telefono value=" . $infousuario['telefono'] . " readonly> <br>
        Fecha de Nacimiento:<br>
		<input type= text  name= nacimiento value= " . $infousuario['nacimiento'] . " readonly> <br>
        Email:<br>
		<input type= text  name= email value= " . $infousuario['email'] . " readonly> <br>
        Usuario:<br>
		<input type= text  name= usuario value= " . $infousuario['usuario'] . " readonly>
		";
		}
		else {
			echo "Not logged in";
		}
		?>		
	</form>

	<div class="button-container">
			<a class="button" href="modify_user.php">Editar Datos</a>
			<a class="button" href="modify_password.php">Cambiar contraseña</a>
	</div>

	<div class="button-container">
		<a class="button" href="show_user.php?action=logout">Cerrar Sesión</a>
		<a class="button" href="index.php">Volver</a>
	</div>
	
<html>
