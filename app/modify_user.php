<?php
//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();

//comprueba si se ha iniciado sesion
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


require 'setup_sql.php';

// comprobar si se ha enviado el formulario
if (isset($_SESSION['user_id'])) {

	//se obtiene el id del usuario que tenga la sesión iniciada
	$userId=$_SESSION['user_id'];
	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$sql = "SELECT nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario FROM usuarios WHERE idUsuario = ? ";

	$sth = $conn->prepare($sql);
	$sth->bind_param('i', $userId);

	if($sth->execute()){//se ejecuta la consulta
		$result = $sth->get_result();      //el resultado se cuarda en la variable $result
		if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
			$infousuario = $result->fetch_assoc();//obtenemos el usuario
		}
		else{
			//no se ha encontrado un usuario con ese id
			echo "No attributes found for user ID: " . htmlspecialchars($userId);
		}
	}
	else{
		//la instrucción no es valida
		echo "Conexión fallida";
	}
	$sth->close();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// Verificación del token CSRF
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			echo "<script>
						window.alert('no ha sido posible modificar los datos, pruebalo mas tarde');
						window.location.href = 'items.php';
					</script>";
			exit();
		}
		
		// Obtener los datos del formulario
		$id_usuario = htmlspecialchars($_POST['idUsuario']);
		$nuevo_nombre = htmlspecialchars($_POST['nombre']);
		$nuevo_apellido = htmlspecialchars($_POST['apellido']);
		$nuevo_telefono = htmlspecialchars($_POST['telefono']);
		$nueva_fecha = htmlspecialchars($_POST['nacimiento']);
		$nuevo_email = htmlspecialchars($_POST['email']);
		$nuevo_usuario = htmlspecialchars($_POST['usuario']);
		
		//guarda la instrucción de SQL que quere utilizar, en este caso un select
		$sql = "SELECT usuario from usuarios where usuario = ?";
		//se prepara la instrucción

		$sth = $conn->prepare($sql);
		$sth->bind_param('s', $nuevo_usuario);
		$sth->execute();
		$result = $sth->get_result();
		
		//se comprueba si ya esxiste un usuario con ese nombre de usuario
		if ($result ->num_rows > 0 && $nuevo_usuario!=$infousuario['usuario']){
			echo "<script> window.alert('Escoja otro nombre de usuario, ese no está disponible'); </script>";}
		else{
			// Preparar la consulta SQL (utilizando prepared statements para prevenir inyecciones SQL)
			$sql = "UPDATE usuarios SET nombre= ?, apellido= ?, telefono= ?, nacimiento= ?, email= ?, usuario= ? WHERE idUsuario= ?";
			
			$sth = $conn->prepare($sql);
			$sth->bind_param('ssisssi', $nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $nueva_fecha, $nuevo_email, $nuevo_usuario, $_SESSION['user_id']);

			// Ejecutar la consulta
			if ($sth->execute()) {
				unset($_SESSION['csrf_token']);
				echo "<script>
					window.alert('Cambios guardados correctamente.');
					window.location.href = 'show_user.php';
				</script>";
			} else {
				//la instrucción no es valdia
				echo "Error al guardar los cambios: " . $stmt->error;
			}
		}
		$sth->close();
	
	}
	
	// cerrar conexión
	$conn->close();
}
?>

<html>
<head>
	<meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			script-src 'self' 'nonce-abc123' ;
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'self';">
	<title> Modificar Datos </title>
	<link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="user_modify_form" method="POST" id="user_modify_form">
	<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
		<?php
        //el readonly es para que no se pueda editar, es un formulario pero sin poder editarlo
        if (isset($_SESSION['user_id'])) {
			echo
			"
			Nombre completo:<br>
			<input type= text name= nombre value= '" . htmlspecialchars($infousuario['nombre']) . "' required>
			<input type= text  name= apellido value=  '" . htmlspecialchars($infousuario['apellido']) . "' required> <br>
			DNI:<br>
			<input type= text  name= numeroDNI value= '" . htmlspecialchars($infousuario['numeroDNI']) . "' required> <br>
			<input type= text  name= letraDNI value= '" . htmlspecialchars($infousuario['letraDNI']) . "' required> <br>
			Teléfono:<br>
			<input type= text  name= telefono value= '" . htmlspecialchars($infousuario['telefono']) . "' required> <br>
			Fecha de Nacimiento:<br>
			<input type= text  name= nacimiento value= '" . htmlspecialchars($infousuario['nacimiento']) . "' required> <br>
			Email:<br>
			<input type= text  name= email value= '" . htmlspecialchars($infousuario['email']) . "' required> <br>
			Usuario:<br>
			<input type= text  name= usuario value= '" . htmlspecialchars($infousuario['usuario']) . "' required> <br>
			";
        }
        else {
            echo "You are not logged in";
        }
		?>
		<input type="submit" value="Guardar cambios" name="modify_submit" >
	</form>

	<div class="button-container">
		<a class="button" href="show_user.php">Volver</a>
	</div>	
	<script nonce="abc123" src="comprobacionDeDatos.js"></script>
	</body>
<html>
