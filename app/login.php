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

    // comprobar conexión

    // si la variable que guarda la conexión es un error 
    if ($conn->connect_error) {
        //detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión 
        die("Connection failed: " . $conn->connect_error);
        
    }

    //si se ha pulsado el botón que llama a login_submit
    if (isset($_POST['login_submit'])) {

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Token CSRF inválido. Operación no permitida.");
        }

        // obtener el usuario y contraseña del formulario y meterlos en una variable
        $usuario = $_POST['nombreUsuario'];
        $contraseña=$_POST['contraseña'];

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
                    echo "<script>window.location.href = 'index.php';</script>";
                }
                //si no
                else {
                    //imprime por pantalla un mensaje que indica que la contraseña o usuario no es correcto
                    echo "<script>alert('El usuario o la contraseña no coinciden');</script>";
                }
            }
            else{
                echo "<script>alert('No existe un usuario con ese nombre de usuario');</script>";
                        
            }
        }catch(Exception $e){

        }
        
        
    }
    $conn->close();
    ?>
    
<!-- crea un formulario con el nombre login_form que realizará un metodo post  --> 
<form name="login_form" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
	<p>Introduzca el nombre del usuario y su contraseña:</p>
	Nombre de usuario:<input type="text" name="nombreUsuario" value="" autocomplete="off"> 
	Contraseña:<input type="text" name="contraseña" value="" autocomplete="off"> 
  
	<br>
    <!-- se trata de un boton del tipo submit, que al pulsar realiza el login_submit--> 
	<input type="submit" name="login_submit" value="Acceder" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
    
</form>

<br>
<div class="button-container">
    <a href="index.php" class="button">Volver a inicio</a>
</div>

</body>
<html>
