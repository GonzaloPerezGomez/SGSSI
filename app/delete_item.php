<?php

require 'setup_session.php';
include 'mysql_secret.php';

//rutas de los archivos de log
$error_file = '/var/www/logs/errores.log';

//comprueba si se ha iniciado sesion
if (!isset($_SESSION['randomID']) || $_SESSION['tipo'] != 'admin') {
    echo "<script> window.location.href = 'items.php';</script>";
    exit();
}


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo "<script>
			window.alert('no es posible eliminar el libro en estos momentos, pruebalo mas tarde');
			window.location.href = 'items.php';
		</script>";
}


require 'setup_sql.php';

//
$ISBN = isset($_POST['ISBN']) ? trim($_POST['ISBN']) : '';

 //si se ha pulsado el botón que llama a item_delete_submit
if (isset($_POST['item_delete_submit'])) {
    // Verificación del token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $ip_usuario = $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible';
		$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'User-Agent no disponible';
		$metodo_http = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN'; //metodo HTTP
		$uri = $_SERVER['REQUEST_URI'] ?? '/'; //URI solicitada
		$version_http = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0'; //versión del protocolo
		$peticion = "$metodo_http $uri $version_http"; //construcción completa
        file_put_contents($error_file, date('Y-m-d H:i:s') . " - Error CSRF. Error con el token en delete_item. Token:" . htmlspecialchars($_POST['csrf_token']) . " IP: " . htmlspecialchars($ip_usuario) . ". User-Agent: " . htmlspecialchars($user_agent) . ". Peticion: " . htmlspecialchars($peticion) . " \n", FILE_APPEND);
		echo "<script>
					window.alert('no ha sido posible borrar el libro, pruebalo mas tarde');
					window.location.href = 'items.php';
				</script>";
        exit();
    }

    //se guarda la instrucción de SQL que se quiere aplicar en la base de datos en este caso delete 
    $sql = "DELETE FROM libro WHERE ISBN = ?" ;

    $sth = $conn->prepare($sql);
	$sth->bind_param('s', $ISBN);

    //si al realizar el delete en sql el resultado es true(se ha realizado la introduccion)
    try {
        $sth->execute();

        unset($_SESSION['csrf_token']);

        //pone por pantalla
        echo "<script>
            <!--un aviso de que el libro se ha añadido correctamente -->
			window.alert('Libro eliminado correctamente.');
            <!--nos lleva a la pagina items.php-->
			window.location.href = 'items.php';
		</script>";
        //cierra conexión con la base de datos
		$conn->close();

        // Para eliminar la cookie del CSRF Token
		exit();
    }catch(Exception $e){
        echo "<script> window.alert('Ocurrió un error con la imagen, intente más tarde.');</script>";
        file_put_contents($error_file, date('Y-m-d H:i:s') . " - " . "Excepcion de delete en delete_item: " . htmlspecialchars($e->getMessage()) . "\n", FILE_APPEND);
    }

    //cierra conexión con la base de datos
    $conn->close();
}

?>

<html>
<head>
    <meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			script-src 'self';
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'self';">
    <!-- comentario--> 
    <meta charset="UTF-8">
    <!-- titulo que se pondra en la pagina --> 
    <title> Borrar libro </title>
    <!-- indica desde que script realizara las comprobaciones --> 
    <link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
<body>
    <br>
    <div><h1>¿ESTÁS SEGURO DE QUE QUIERES ELIMINARLO?</h1></div>
    <!-- crea un formulario que realizará un metodo post  --> 
    <form method="post">

    <!-- Campo oculto para el token CSRF -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="hidden" name="ISBN" value="<?php echo htmlspecialchars($ISBN); ?>">
        <!-- se trata de un boton del tipo submit-->
        <input type="submit" name="item_delete_submit" value='Confirmar'>
        <br>
        <!-- botton normal que al pulsar redirige la pagina a items.php --> 
        <a type="button" class="button" href="items.php">Cancelar</a>
    </form>
</body>
</html>