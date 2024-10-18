<?php
//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();

//comprueba si se pulsa el botón de cerrar sesión
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
	//elimina la sesión
    session_destroy();
    header("Location: index.php"); 
    exit();
}

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

//comprobar conexión
if ($conn->connect_error) {
	//detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión 
    die("Connection failed: " . $conn->connect_error);
}

//se guarda el id del usuario con la sesión activa
$userId=$_SESSION['user_id'];

//guarda la instrucción de SQL que quere utilizar, en este caso un select
$query = "SELECT nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena FROM usuarios WHERE idUsuario = " . $userId;

// comprobar si la consulta es valida
if($stmt = $conn->prepare($query)){     //prepara la consulta
	$stmt->execute();                   //se ejecuta la consulta
	$result = $stmt->get_result();      //el resultado se cuarda en la variable $result
	if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
		$infousuario = $result->fetch_assoc();//obtenemos el usuario
	}
	else{
		//no se ha encontrado un usuario con ese id
		echo "No attributes found for user ID: " . $userId;
	}
}
else{
	//la instruccion SQL no es valida
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
