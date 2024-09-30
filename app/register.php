<html>
<head>
<title> registro </title>
<script src="comprobacionDeDatos.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<?php include 'register-database.php'; ?>	
	<form name="formulario" action="register-database.php" method="post">
		<p align="center">Introduzca la información pedida a continuación:</p>
		Nombre completo:<br>
		<input type="text" name="nombre" placeholder="Nombre">  <input type="text" name="apellido" placeholder="Apellido"><br>
		DNI: <br>
		<input type="text" name="numeroDNI" placeholder="1234567"> <input type="text" name="letraDNI" placeholder="Letra DNI"> <br>
  	
  		Teléfono:<br>
  		<input type="text" name="telefono" placeholder=""> <br>
		Fecha de Nacimiento:<br>
		<input type="text" name="fechaNacimiento" placeholder="AAAA-MM-DD"/><br>
		Email:<br>
		<input type="text" name="correo" placeholder="example@xxx.yyy" > <br>
		Nombre Del usuario<br>
		<input type="text" name="nombreUsuario"><br>
		Contraseña:<br>
		<input type="text" name="contrasena"> <br>

		<br>
		<input type="submit" name="submit" class ="button" value="Enviar" onclick="comprobardatos()">
	</form>
		
	<a href="index.php" class="button">Volver a inicio</a>

	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>


	
	
	
	
	
	
	
	
	
	
	
