<?php  
require 'setup_session.php';
include 'mysql_secret.php';

//rutas de los archivos de log
$log_file = '/var/www/logs/login_intentos.log';
$error_file = '/var/www/logs/errores.log';

//límite de intentos fallidos
$intentos_maximos = 5; //5 intentos para logearse
$bloqueo_duracion = 300;//300; //5 minutos de bloqueo si el usuario pone 5 veces datos incorrectos al logearse

//inicializar el contador de intentos fallidos
if (!isset($_SESSION['intentos_fallidos'])) {
    $_SESSION['intentos_fallidos'] = 0;
}
if (!isset($_SESSION['tiempo_bloqueo'])) {
    $_SESSION['tiempo_bloqueo'] = 0;
}
//comprobamos si el usuario está bloqueado
if ($_SESSION['intentos_fallidos'] >= $intentos_maximos) {
    $tiempo_transcurrido = time() - $_SESSION['tiempo_bloqueo'];
    if ($tiempo_transcurrido < $bloqueo_duracion) {
        $espera = $bloqueo_duracion - $tiempo_transcurrido;
        $espera_minutos = ceil($espera / 60);
        if ($espera_minutos==1){echo "<script> window.alert('Demasiados intentos fallidos. Intenta de nuevo en {$espera_minutos} minuto.');</script>";}
        else{echo "<script> window.alert('Demasiados intentos fallidos. Intenta de nuevo en {$espera_minutos} minutos.');</script>";} 
        echo "<script> window.location.href = 'index.php';</script>";
    } else {
        //reiniciamos el contador cuando acabe el tiempo de bloqueo
        $_SESSION['intentos_fallidos'] = 0;
        $_SESSION['tiempo_bloqueo'] = 0;
    }
}


//comprueba si se ha iniciado sesion
if (isset($_SESSION['randomID'])) {
    echo "<script> window.location.href = 'index.php';</script>";
    exit();
}

// Genera el token CSRF si no existe en la sesión
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio seguro
    }


require 'setup_sql.php';

    //si se ha pulsado el botón que llama a login_submit
    if (isset($_POST['login_submit'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error_message = 'sin token:' . $_POST['csrf_token'] . ' o tokens diferentes: ' . $_POST['csrf_token'] . ' != ' . htmlspecialchars($_SESSION['csrf_token']);
            file_put_contents($error_file, date('Y-m-d H:i:s') . " - Error CSRF: " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
            echo "<script> window.alert('No ha sido posible iniciar sesión, pruébalo más tarde');</script>";
            echo "<script> window.location.href = 'index.php';</script>";
            exit();
        }
        //obtenemos el usuario y contraseña del formulario y meterlos en una variable
        $usuario = htmlspecialchars($_POST['nombreUsuario']);
        $contraseña= htmlspecialchars($_POST['contraseña']);
        
        $usuario = encrypt($usuario);

        $sql = "SELECT idUsuario, tipo, contrasena, salt from usuarios where usuario = ?";
        $sth = $conn->prepare($sql);
        $sth->bind_param('s', $usuario);
        
        try {
            $sth->execute();
            $result = $sth->get_result();
            if ($result->num_rows > 0){
                $result = $result->fetch_assoc();

                $hash_usuario = hash("sha256", $contraseña . $result['salt']);

                if ($hash_usuario == $result['contrasena']) {
                    unset($_SESSION['csrf_token']);
                    //guarda en la variable global sesion el id del usuario que se acaba de registrar
                    $_SESSION['user_id'] = $result['idUsuario'];
                    $_SESSION['tipo'] = $result['tipo'];
                    $_SESSION['randomID'] = bin2hex(random_bytes(32));
                    //registramos intento exitoso
                    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Login exitoso: " . htmlspecialchars($_POST['nombreUsuario']) . "\n", FILE_APPEND);
                    //reiniciamos intentos fallidos en caso de login éxito
                    $_SESSION['intentos_fallidos'] = 0;
                    $_SESSION['tiempo_bloqueo'] = 0;
                    //redirige el sistema a la pagina index.php
                    echo "<script> window.alert('Sesión Iniciada');</script>";
                    echo "<script>window.location.href = 'index.php';</script>";
                }
                    //si no
                else {
                    //imprime por pantalla un mensaje que indica que la contraseña o usuario no es correcto
                    echo "<script> window.alert('El usuario o la contraseña no coinciden');</script>";
                    $_SESSION['intentos_fallidos']++;
                    // Registrar intento fallido
                    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Login fallido. Usuario: " . htmlspecialchars($_POST['nombreUsuario']) . " Error: El usuario o la contraseña no coinciden \n", FILE_APPEND);
                }

            }
            else{
                echo "<script> window.alert('No existe un usuario con ese nombre de usuario');</script>";
                $_SESSION['intentos_fallidos']++;
                // Registrar intento fallido
                file_put_contents($log_file, date('Y-m-d H:i:s') . " - Login fallido. Usuario: " . htmlspecialchars($_POST['nombreUsuario']) . " Error: No existe un usuario con ese nombre de usuario \n", FILE_APPEND);      
            }
        }catch(Exception $e){
            echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
            $error_message = 'Excepcion de select en login: ' . htmlspecialchars($e->getMessage()). '. Error: ' . htmlspecialchars($conn->error);
            file_put_contents($error_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
        }
        if ($_SESSION['intentos_fallidos'] >= $intentos_maximos) {
            $_SESSION['tiempo_bloqueo'] = time(); //registramos el inicio del bloqueo
            $error_message = 'Demasiados intentos fallidos con token: ' . htmlspecialchars($_SESSION['csrf_token']);
            file_put_contents($error_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
            echo "<script> window.alert('Demasiados intentos fallidos. Por favor, intenta más tarde.');</script>";
            echo "<script> window.location.href = 'index.php';</script>";
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
    <title> login </title> 
    <!-- indica desde que script modela la pagina web --> 
    <link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
	
	
<body>
    <br>
    <header>
        <h1>Bienvenido</h1>
    </header>
    <main>
        <section>
            <h2>Iniciar sesión</h2>  
        </section>
    </main>
    
    <!-- crea un formulario con el nombre login_form que realizará un metodo post  --> 
    <form name="login_form" method="post" id="login_form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <p>Introduzca el nombre del usuario y su contraseña:</p>
        Nombre de usuario:<input type="text" name="nombreUsuario" autocomplete="off" required> 
        Contraseña:<input type="password" name="contraseña" id="contraseña" class="form-control form-control-lg" value="" autocomplete="current-password" required> 
        <br>
        <!-- se trata de un boton del tipo submit, que al pulsar realiza el login_submit--> 
        <input type="submit" name="login_submit" value="Acceder" >
        
    </form>

    <br>
    <div class="button-container">
        <a href="index.php" class="button">Volver a inicio</a>
    </div>

    </body>
<html>

