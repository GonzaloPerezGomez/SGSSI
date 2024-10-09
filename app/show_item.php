<?php
// conexión a la base de datos
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ISBN = $_GET['ISBN'];              //se obtiene el ISBN
$query = "SELECT titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE ISBN = '" . $ISBN . "'";

if($stmt = $conn->prepare($query)){     //prepara la consulta
	$stmt->execute();                   //se ejecuta la consulta
	$result = $stmt->get_result();      //el resultado se cuarda en la variable $result
	if($result->num_rows > 0){          //comprueba si hay un libro con ese ISBM (mira si el resultado contiene filas)
		$libro = $result->fetch_assoc();//obtenemos el libro
	}
	else{
		echo "No se ha encontrado ningun libro";
	}
}
else{
	echo "Conecxion fallida";
}

$nombimagen = "libros/" . strtolower($libro['titulo'] . ".jpeg"); //imágenes
$nombimagen = str_replace(" ", "-", $nombimagen);

$stmt->close();

?>


<html>
<head>
	<title> Información de libro </title>
	<link rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="show_item_form" method="POST">
		<p align="center"> Introduzca la información pedida a continuación:</p>
		<?php
        //el readonly es para que no se pueda editar, es un formulario pero sin poder editarlo
		echo
		"
		Título:<br>
		<input type= text name= titulo value= '{$libro['titulo']}' readonly>
        Autor: <br>
		<input type= text  name= autor value=  '{$libro['autor']}' readonly> <br>
  		Fecha de Publicación:<br>
  		<input type= text  name= f_publicacion value= " . $libro['f_publicacion'] . " readonly> <br>
		ISBN:<br>
		<input type= text  name= ISBN value= " . $ISBN . " ><br>
		Nº de Páginas:<br>
		<input type= text  name= n_paginas value= " . $libro['n_paginas'] . " readonly> <br>
		Imagen:<br>
		<img src='" . $nombimagen . "' style='height: 150px;'> <br>
		"
		?>
	</form>
	
	<div class="button-container">
		<a class="button" href="items.php">Volver</a>
	</div>	
	
<html>