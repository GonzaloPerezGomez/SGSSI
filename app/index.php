<html>
<head>
<title> registro </title>
<script src="comprobacionDeDatos.js"></script>
</head>
	
	
	<body>
	<form name="formulario">
		Introduzca la información pedida a continuación:<br>
		Nombre completo:<br>
		<input type="text" name="nombre" placeholder="Nombre">  <input type="text" name="apellido" placeholder="Apellido"><br>
		DNI: <br>
		<input type="text" name="numeroDNI" placeholder="1234567"> <input type="text" name="letraDNI" placeholder="A"> <br>
  	
  		Teléfono:<br>
  		<input type="text" name="telefono" placeholder=""> <br>
		Fecha de Nacimiento:<br>
		<input type="text" name="fechaNacimiento" placeholder="DD/MM/AAAA"><br>
		Email:<br>
		<input type="text" name="correo" placeholder="example@xxx.yyy" > <br>
		
		<br>
		<input type="button" value="Enviar" onclick="comprobardatos()">
	</form>
<html>


	
	
}
  
  echo '<a href="login.php">Sign in</a>';
  echo '<a href="register.php">Register</a>';

?>
