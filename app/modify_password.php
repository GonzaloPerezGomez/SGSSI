<?php

//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();


//comprueba si se ha iniciado sesion
if (!isset($_SESSION['randomID']) ) {
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
    $sql = "SELECT contrasena, salt FROM usuarios WHERE idUsuario = ?";

    $sth = $conn->prepare($sql);
	$sth->bind_param('i', $userId);

    //se obtiene la contraseña actual del usuario para poder compararla con la nueva
    if($sth->execute()){//se ejecuta la consulta
        $result = $sth->get_result();      //el resultado se guarda en la variable $result
        if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
            $result = $result->fetch_assoc();//obtenemos la contraseña
            $contrasena = $result['contrasena'];
            $salt = $result['salt'];
        }
        else{
            //no se ha encontrado un usuario con ese id
            echo "No attributes found for user ID: " . $userId;
        }
    }
    else{
        //la instrucción no es valida
        echo "Conexion fallida";
    }
    $sth->close();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificación del token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo "<script>
                        window.alert('no ha sido posible modificar la contraseña, pruebalo mas tarde');
                        window.location.href = 'items.php';
                    </script>";
            exit();
        }
        // Obtener los datos del formulario
        $actualcontrasena = htmlspecialchars($_POST['actualcontrasena']);
        $nuevacontrasena1 = htmlspecialchars($_POST['nuevacontrasena1']);
        $nuevacontrasena2 = htmlspecialchars($_POST['nuevacontrasena2']);
        
        $actualcontrasena1 = $actualcontrasena . $salt;
        $actualcontrasena1 = hash('sha256', $actualcontrasena1);

        //se comprueba si la contraseña introducida es correcata
        if ($contrasena==$actualcontrasena1) {
            if ($actualcontrasena != $nuevacontrasena1){
                //se comprueba si las nuevas contraseñas con la misma
                if($nuevacontrasena1==$nuevacontrasena2 ){
                    //para que la nueva contraseña no puedan ser espacios en blanco y que sea una sola palabra
                    if(trim($nuevacontrasena1)!= '' && strpos(trim($nuevacontrasena1), ' ') === false) {
                        $nuevacontrasena = $nuevacontrasena1 . $salt;
                        $nuevacontrasena = hash('sha256', $nuevacontrasena);
                        // Preparar la consulta SQL (utilizando prepared statements para prevenir inyecciones SQL)
                        $sql = "UPDATE usuarios SET contrasena= ? WHERE idUsuario= ?";
                        $sth = $conn->prepare($sql);
                        $sth->bind_param('si', $nuevacontrasena, $userId);
                        // Ejecutar la consulta
                        if ($sth->execute()) {
                            unset($_SESSION['csrf_token']);
                            echo "<script>
                            window.alert('Cambios guardados correctamente.');
                            window.location.href = 'show_user.php';
                            </script>";
                        } else {
                            //la instrucción no se ha ejecutado correctamente
                            echo "Error al guardar los cambios: " . $sth->error;
                        }
                    } else {echo "<script> window.alert('La contraseña nueva no es válida'); </script>";}
                }
                else {echo "<script> window.alert('Las nuevas contraseñas no coinciden'); </script>";}

            } else{echo "<script> window.alert('No se puede repetir la contraseña'); </script>";}
        }
        else {echo "<script> window.alert('La contraseña actual no coincide con tu contraseña'); </script>";}
    }
}

// cerrar conexión
$conn->close();
?>

<html>
<head>
    <meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'self';">
    <title> Modificar Contraseña </title>
    <link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="user_modify_password" method="POST" id="user_modify_password">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
		<br>
  		Introduzca su contraseña actual:<br>
  		<input type= text  name= actualcontrasena placeholder="contraseña actual" autocomplete="off" required> <br>
		Introduzca la nueva contraseña:<br>
		<input type= text  name= nuevacontrasena1 placeholder="nueva contraseña" autocomplete="off" required> 
		<input type= text  name= nuevacontrasena2 placeholder="repita la nueva contraseña" autocomplete="off" required> <br>

		<input type="submit" value="Guardar cambios" name="modify_password_submit" class="button-submit">
	</form>

	<div class="button-container">
		<a class="button" href="show_user.php">Volver</a>
	</div>	
	<script nonce="abc123" src="comprobacionDeDatos.js"></script>
    </body>
</html>
