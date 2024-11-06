<?php
//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();

//comprueba si se ha iniciado sesion
if (!isset($_SESSION['user_id']) ) {
    header("Location: index.php");
    exit();
}


//comprueba si se pulsa el botón de cerrar sesión
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
	//elimina la sesión
    session_destroy();
    header("Location: index.php"); 
    exit();
}

require 'setup_sql.php';

//se guarda el id del usuario con la sesión activa
$userId=$_SESSION['user_id'];

//guarda la instrucción de SQL que quere utilizar, en este caso un select
$sql = "SELECT nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena FROM usuarios WHERE idUsuario = ?";

$sth = $conn->prepare($sql);
$sth->bind_param('s', $userId);

// comprobar si la consulta es valida
if($sth->execute()){//se ejecuta la consulta
	$result = $sth->get_result();      //el resultado se cuarda en la variable $result
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
$sth->close();
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
		<input type= text name= nombre value= '" . htmlspecialchars($infousuario['nombre']) . "' readonly>
		<input type= text  name= apellido value=  '" . htmlspecialchars($infousuario['apellido']) . "' readonly> <br>
  		DNI:<br>
  		<input type= text  name= numeroDNI value= '" . htmlspecialchars($infousuario['numeroDNI']) . "' readonly> <br>
		<input type= text  name= letraDNI value= '" . htmlspecialchars($infousuario['letraDNI']) . "' readonly><br>
		Teléfono:<br>
		<input type= text  name= telefono value='" . htmlspecialchars($infousuario['telefono']) . "' readonly> <br>
        Fecha de Nacimiento:<br>
		<input type= text  name= nacimiento value= '" . htmlspecialchars($infousuario['nacimiento']) . "' readonly> <br>
        Email:<br>
		<input type= text  name= email value= '" . htmlspecialchars($infousuario['email']) . "' readonly> <br>
        Usuario:<br>
		<input type= text  name= usuario value= '" . htmlspecialchars($infousuario['usuario']) . "' readonly>
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
