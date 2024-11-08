<?php  
require 'setup_session.php';?>

<html>
<head>
    <meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
            form-action 'self'">
    <meta charset="UTF-8">
    <title> Página principal </title>
    <link nonxw="abc123" rel="stylesheet" href="estilo.css">
</head>

<body>
    <br><h1>PÁGINA PRINCIPAL</h1>

    <?php
    //si la sesión esta iniciada
    if (isset($_SESSION['randomID'])) { 
        //se muestra
        echo
        '
        <!--contenedor de botones con los siguientes botones-->
        <div class="button-container">
            <!-- botón normal que al pulsar se redirige a la página items.php-->
            <a class="button" href="items.php">Catálogo</a>
            <!-- botón normal que al pulsar redirige página a show_user.php -->
            <a class="button" href=show_user.php>
            <!--se carga la imagen de una carpeta ubicada en el repositorio-->
            <img src="image/user.png"  class="imagen_funcionalidades"></a>
        </div>';}
    //si no ha iniciado sesión
    else {                              
        echo
        '
        <!--contenedor de botones con los siguientes botones-->
        <div class="button-container">
            <!-- botón normal que al pulsar se redirige a la página login.php-->
            <a href="login.php" class="button">Iniciar Sesión</a>
            <!-- botón normal que al pulsar se redirige a la página register.php-->
            <a href="register.php" class="button">Registrarse</a>
        </div>';}?>

    </body>
</html>
		

		