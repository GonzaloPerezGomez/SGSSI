<?php  
//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();

// Rutas de los archivos de log
$log_file = 'logs/login_intentos.log';
$error_log_file = 'logs/errores.log';

// Límite de intentos fallidos
$intentos_maximos = 5;

// Inicializar el contador de intentos fallidos
if (!isset($_SESSION['intentos_fallidos'])) {
    $_SESSION['intentos_fallidos'] = 0;
}


//comprueba si se ha iniciado sesion
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
            file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - Error CSRF: " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
            echo "<script> window.alert('No ha sido posible iniciar sesión, pruébalo más tarde');</script>";
            echo "<script> window.location.href = 'index.php';</script>";
            exit();
        }

        if ($_SESSION['intentos_fallidos'] >= $intentos_maximos) {
            $error_message = 'Demasiados intentos fallidos con token: ' . htmlspecialchars($_SESSION['csrf_token']);
            file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
            echo "<script> window.alert('Demasiados intentos fallidos. Por favor, intenta más tarde.');</script>";
            echo "<script> window.location.href = 'index.php';</script>";
        } else {
            // obtener el usuario y contraseña del formulario y meterlos en una variable
            $usuario = htmlspecialchars($_POST['nombreUsuario']);
            $contraseña=htmlspecialchars($_POST['contraseña']);

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
                        // Registrar intento exitoso
                        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Login exitoso: " . htmlspecialchars($usuario) . "\n", FILE_APPEND);
                        //redirige el sistema a la pagina index.php
                        echo "<script> window.alert('Sesión Iniciada');</script>";
                        echo "<script>window.location.href = 'index.php';</script>";
                    }
                    //si no
                    else {
                        //imprime por pantalla un mensaje que indica que la contraseña o usuario no es correcto
                        //echo "<script> window.alert('El usuario o la contraseña no coinciden');</script>";
                        $error_message = 'El usuario o la contraseña no coinciden';
                        echo "<script> window.alert('$error_message');</script>";
                        $_SESSION['intentos_fallidos']++;
                        // Registrar intento fallido
                        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Login fallido. Usuario: " . htmlspecialchars($usuario) . " Error: " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
                    }
                }
                else{
                    $error_message = 'No existe un usuario con ese nombre de usuario';
                    echo "<script> window.alert('$error_message');</script>";
                    $_SESSION['intentos_fallidos']++;
                    // Registrar intento fallido
                    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Login fallido. Usuario: " . htmlspecialchars($usuario) . " Error: " . htmlspecialchars($error_message) .  "\n", FILE_APPEND);      
                }
            }catch(Exception $e){
                echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
                $error_message = 'Excepcion: ' . htmlspecialchars($e);
                file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
            }
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
        Contraseña:<input type="text" name="contraseña" autocomplete="off" required> 
    
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

