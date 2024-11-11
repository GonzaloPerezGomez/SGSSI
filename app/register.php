<?php
require 'setup_session.php';

if (isset($_SESSION['randomID'])) {
    echo "<script> window.location.href = 'index.php';</script>";
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio seguro
}

require 'setup_sql.php';

// comprobar si se ha enviado el formulario
if ( isset($_POST['register_submit'])) {


	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		$error_message = 'sin token:' . htmlspecialchars($_POST['csrf_token']) . ' o tokens diferentes: ' . htmlspecialchars($_POST['csrf_token']) . ' != ' . htmlspecialchars($_SESSION['csrf_token']);
        file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - Error CSRF: " . htmlspecialchars($error_message) . "\n", FILE_APPEND);	
		echo "<script>
				window.alert('no ha sido posible registrarse, pruebalo mas tarde');
				window.location.href = 'items.php';
			</script>";
			exit();
		}
		

	// guardar la información del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido= htmlspecialchars($_POST['apellido']);
    $DNI = htmlspecialchars($_POST['numeroDNI']);
    $letraDNI = htmlspecialchars($_POST['letraDNI']);
    $telefono=htmlspecialchars($_POST['telefono']);
    $nacimiento=htmlspecialchars($_POST['nacimiento']);
    $email=htmlspecialchars($_POST['email']);
    $usuario=htmlspecialchars($_POST['usuario']);
    $contraseña=htmlspecialchars($_POST['contrasena']);
	
	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$sql = "SELECT usuario from usuarios where usuario = ? OR numeroDNI = ?";
	$sth = $conn->prepare($sql);
	$sth->bind_param('si', $usuario, $DNI);
	try {
		$sth->execute();
		//se ejecuta la instrucción
		$result = $sth->get_result();
		if ($result ->num_rows > 0){ //comprobar si hay otro usuario con ese nombre de usuario
			echo "<script> window.alert('El nombre de usuario ya está cogido o ya tiene una cuenta') </script>";}
		else{
			//generamos una semilla de 255 bytes
			$salt = bin2hex(random_bytes(255));
			//generamos el hash apartir de la contraseña mas la semilla 
			$contraseña_completa = $contraseña . $salt;
			$hash_contraseña = hash("sha256", $contraseña_completa);
			//guarda la instrucción de SQL que quere utilizar, en este caso un insert
			$sql = "INSERT INTO usuarios (nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena,salt) VALUES (?,?,?,?,?,?,?,?,?,?)";
			
			$sth = $conn->prepare($sql);
			$sth->bind_param('ssisisssss', $nombre, $apellido, $DNI, $letraDNI, $telefono, $nacimiento, $email, $usuario, $hash_contraseña, $salt);
			//se comprueba si la instrucción se ha ejecutado de forma correcta
			try {
				$sth->execute();

				unset($_SESSION['csrf_token']); // Borrar el token CSRF

				//se recoge el id del usuario para despues crear su sesión
				$sql = "SELECT idUsuario from usuarios where usuario = ? and contrasena= ?";
				
				$sth = $conn->prepare($sql);
				$sth->bind_param('ss', $usuario, $hash_contraseña);
				try {
					$sth->execute();

					$result = $sth->get_result();
					$returnedValues = $result->fetch_assoc();
					$_SESSION['user_id'] = $returnedValues['idUsuario'];
					$_SESSION['tipo'] = $returnedValues['tipo'];
          $_SESSION['randomID'] = bin2hex(random_bytes(32));
					echo "<script>
						window.alert('Se ha registrado correctamente :)');
						window.location.href = 'index.php';
					</script>";
				}catch(Exception $e){
					echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
					$error_message = 'Excepcion de select: ' . htmlspecialchars($e). '. Error: ' . htmlspecialchars($conn->error);
					file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
				}
				//se cierra la conexión
				$conn->close();
				// Borra la cookie del CSRF token
				//exit();(cuando se solucione lo de que no hace nada del script quitarlo)
			}catch(Exception $e){
				echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
				$error_message = 'Excepcion de insert into: ' . htmlspecialchars($e). '. Error: ' . htmlspecialchars($conn->error);
				file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
			}
		

		}
	}catch(Exception $e){
		echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
		$error_message = 'Excepcion de select: ' . htmlspecialchars($e). '. Error: ' . htmlspecialchars($conn->error);
		file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
	}
	
}
$conn->close();

?>

<html>
<head>
	<meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			script-src 'self' 'nonce-abc123' ;
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'self';">
			
	<title> Registrarse </title>
	<link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="register_form" method="post"  id="register_form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
		<p align="center">Introduzca la información pedida a continuación para registrarse:</p>
		Nombre completo:<br>
		<input type="text" name="nombre" placeholder="Nombre" autocomplete="off" required>  <input type="text" name="apellido" placeholder="Apellido" autocomplete="off" required><br>
		DNI: <br>
		<input type="text" name="numeroDNI" placeholder="12345678" autocomplete="off" required> <input type="text" name="letraDNI" placeholder="Letra DNI" autocomplete="off" required> <br>
  		Teléfono:<br>
  		<input type="text" name="telefono" placeholder="123456789" autocomplete="off" required><br>
		Fecha de Nacimiento:<br>
		<input type="text" name="nacimiento" placeholder="AAAA-MM-DD" autocomplete="off" required/><br>
		Email:<br>
		<input type="text" name="email" placeholder="example@xxx.yyy" autocomplete="off" required> <br>
		Nombre de usuario<br>
		<input type="text" name="usuario" autocomplete="off" required><br>
		Contraseña:<br>
		<input type="text" name="nuevacontrasena1" autocomplete="off" required> <br>

		<br>
		<input type="submit" value="Registrarme" name="register_submit" class="button-submit">
	</form>
		
	<div class="button-container">
		<a href="index.php" class="button">Volver a inicio</a>
	</div>

	<script nonce="abc123" src="comprobacionDeDatos.js"></script>

	</body>
	
<html>