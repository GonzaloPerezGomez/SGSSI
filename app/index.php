<?php  session_start();?>
<html>
<head>
<meta charset="UTF-8">
<title> Página principal </title>
<script src="comprobacionDeDatos.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>

<body>
    
<h1>PÁGINA PRINCIPAL</h1>

<?php
if (isset($_SESSION['user_id'])) { //si ha iniciado sesión
    echo
    '
    <div class="button-container">
        <a class="button" href="items.php">Catálogo</a>
        <a class="button" href=show_user.php>
        <img src="image/user.png" style="height:20px;"></a>
    </div>';}
else {                              //si no ha iniciado sesión
    echo
    '
	<div class="button-container">
        <a href="login.php" class="button">Iniciar Sesión</a>
        <a href="register.php" class="button">Registrarse</a>
        <a class="button" href="items.php">Catálogo</a>
    </div>';}


?>
</body>
</html>

		

		