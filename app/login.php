<?php  
//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();

//comprueba si se ha iniciado sesion
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Genera el token CSRF si no existe en la sesión
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio seguro
    }

?>

<html>
<head>
    <meta http-equiv="Content-Security-Policy"
            content="default-src 'self'; 
                    script-src 'self'; 
                    img-src 'self'; 
                    style-src 'self'; 
                    base-uri 'self'; 
                    form-action 'self';
                    connect-src 'self'; 
                    font-src 'self';">
    <title> login </title> 
    <!-- indica desde que script modela la pagina web --> 
    <link rel="stylesheet" href="estilo.css">
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

    <?php

    require 'setup_sql.php';

    //si se ha pulsado el botón que llama a login_submit
    if (isset($_POST['login_submit'])) {

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error_message = 'No ha sido posible iniciar sesión, pruébalo más tarde';
            echo "<script> window.location.href = 'items.php';</script>";
            exit();
        }

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
                    //redirige el sistema a la pagina index.php
                    echo "<script>window.location.href = 'items.php';</script>";
                }
                //si no
                else {
                    //imprime por pantalla un mensaje que indica que la contraseña o usuario no es correcto
                    echo "<script> 'El usuario o la contraseña no coinciden</script>";
                }
            }
            else{
                $error_message = 'No existe un usuario con ese nombre de usuario';
                        
            }
        }catch(Exception $e){
            $error_message = 'Ocurrió un error, intente más tarde.';
        }
        
        
    }
    $conn->close();
    ?>
    
<!-- crea un formulario con el nombre login_form que realizará un metodo post  --> 
<form name="login_form" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
	<p>Introduzca el nombre del usuario y su contraseña:</p>
	Nombre de usuario:<input type="text" name="nombreUsuario" autocomplete="off" required> 
	Contraseña:<input type="text" name="contraseña" autocomplete="off" required> 
  
	<br>
    <!-- se trata de un boton del tipo submit, que al pulsar realiza el login_submit--> 
	<input type="submit" name="login_submit" value="Acceder" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
    
</form>

 <!-- Mostrar mensajes de error -->
 <?php if (!empty($error_message)): ?>
        <div style="color:red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

<br>
<div class="button-container">
    <a href="index.php" class="button">Volver a inicio</a>
</div>

</body>
<html>

