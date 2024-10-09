<?php  session_start();?>
<html>
<head>
<meta charset="UTF-8">
<title> Página principal </title>
<! -- indica desde que script modela la página web-- > 
<link rel="stylesheet" href="estilo.css">
</head>

<body>
    
<h1>PÁGINA PRINCIPAL</h1>

<?php
<! -- si la sesión esta iniciada-- > 
if (isset($_SESSION['user_id'])) { //si ha iniciado sesión
    //se muestra
    echo
    '
    //contenedor de botones con los siguientes botones
    <div class="button-container">
        // botón normal que al pulsar se redirige a la página items.php
        <a class="button" href="items.php">Catálogo</a>
        // botón normal que al pulsar redirige página a show_user.php 
        <a class="button" href=show_user.php>
        //se carga la imagen de una carpeta ubicada en el repositorio
        <img src="image/user.png" style="height:20px;"></a>
    </div>';}
//si no ha iniciado sesión
else {                              
    echo
    '
    //contenedor de botones con los siguientes botones
	<div class="button-container">
        // botón normal que al pulsar se redirige a la página login.php
        <a href="login.php" class="button">Iniciar Sesión</a>
        // botón normal que al pulsar se redirige a la página register.php
        <a href="register.php" class="button">Registrarse</a>
        <a class="button" href="items.php">Catálogo</a>
    </div>';}


?>
</body>
</html>

		

		