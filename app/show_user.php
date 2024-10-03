<?php
// Establish a connection to the MySQL database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  

}

//$userId = $_GET['user'];
$userId=1;

$query = "SELECT nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario,contrasena FROM usuarios WHERE idUsuario = " . $userId;

if($stmt = $conn->prepare($query)){     //prepara la consulta
	$userId = $_GET['user'];              //se obtiene el user
	$stmt->bind_param("i", $userId);      //s=string
	$stmt->execute();                   //se ejecuta la consulta
	$result = $stmt->get_result();      //el resultado se cuarda en la variable $result
	if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
		$infousuario = $result->fetch_assoc();//obtenemos el usuario
	}
	else{
		echo "No attributes found for user ID: " . $userId;
	}
}
else{
	echo "Conecxion fallida";
}

// Close the statement and the connection
$conn->close();
?>

<html>
<head>
<title> Información de libro </title>
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<form name="show_item_form" method="POST">
		<?php
        //el readonly es para que no se pueda editar, es un formulario pero sin poder editarlo
		echo
		"
		Nombre completo:<br>
		<input type= text name= nombre value= " . $infousuario['nombre'] . " readonly>
		<input type= text  name= apellido value=  " . $infousuario['apellido'] . " readonly> <br>
  		DNI:<br>
  		<input type= text  name= numeroDNI value= " . $infousuario['numeroDNI'] . " readonly> <br>
		<input type= text  name= letraDNI value= " . $infousuario['letraDNI'] . " ><br>
		Teléfono:<br>
		<input type= text  name= telefono value= " . $infousuario['telefono'] . " readonly> <br>
        Fecha de Nacimiento:<br>
		<input type= text  name= nacimiento value= " . $infousuario['nacimiento'] . " readonly> <br>
        Email:<br>
		<input type= text  name= email value= " . $infousuario['email'] . " readonly> <br>
        Usuario:<br>
		<input type= text  name= usuario value= " . $infousuario['usuario'] . " readonly> <br>
        Contraseña: (IGUAL ES MEJOR NO ENSEÑAR LA CONTRASEÑA)<br>
		<input type= text  name= contrasena value= " . $infousuario['contrasena'] . " readonly> <br>
        
		"
		?>
		
	</form>
	
	<div class="button-container">
		<a class="button" href="index.php">Volver</a>
	</div>	
	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>
