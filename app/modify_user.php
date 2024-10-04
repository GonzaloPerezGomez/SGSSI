<?php

session_start();

// Establish a connection to the MySQL database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  

}

if (isset($_SESSION['user_id'])) {


$userId=$_SESSION['user_id'];

$query = "SELECT nombre,apellido,numeroDNI,letraDNI,telefono,nacimiento,email,usuario FROM usuarios WHERE idUsuario = " . $userId;

if($stmt = $conn->prepare($query)){     //prepara la consulta
	$userId = $_GET['user'];              //se obtiene el user
	//$stmt->bind_param("i", $userId);      //s=string
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id_usuario = $_POST['idUsuario'];  

    $nuevo_nombre = $_POST['nombre'];
    $nuevo_apellido = $_POST['apellido'];
    $nuevo_email = $_POST['email'];
    // ... otros campos a actualizar

    // Preparar la consulta SQL (utilizando prepared statements para prevenir inyecciones SQL)
    $sql = "UPDATE usuarios SET nombre='" . $nuevo_nombre . "', apellido='" . $nuevo_apellido."', email='" .$nuevo_email ."' WHERE idUsuario= " . $_SESSION['user_id'];
    $stmt = $conn->prepare($sql);
    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Cambios guardados correctamente.";
    } else {
        echo "Error al guardar los cambios: " . $stmt->error;
    }

    $stmt->close();
}

// Close the statement and the connection
$conn->close();
}
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
        if (isset($_SESSION['user_id'])) {

		echo
		"
		Nombre completo:<br>
		<input type= text name= nombre value= " . $infousuario['nombre'] . " >
		<input type= text  name= apellido value=  " . $infousuario['apellido'] . " > <br>
  		DNI:<br>
  		<input type= text  name= numeroDNI value= " . $infousuario['numeroDNI'] . " readonly> <br>
		<input type= text  name= letraDNI value= " . $infousuario['letraDNI'] . " readonly> <br>
		Teléfono:<br>
		<input type= text  name= telefono value= " . $infousuario['telefono'] . " > <br>
        Fecha de Nacimiento:<br>
		<input type= text  name= nacimiento value= " . $infousuario['nacimiento'] . " > <br>
        Email:<br>
		<input type= text  name= email value= " . $infousuario['email'] . " > <br>
        Usuario:<br>
		<input type= text  name= usuario value= " . $infousuario['usuario'] . "  <br>
		";
        }
        else {
            echo "You are not logged in";
        }
		?>
		<input type="submit" value="Guardar cambios"name="modify_submit" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
        
	</form>

	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>
