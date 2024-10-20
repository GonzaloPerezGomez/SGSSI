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

//se guarda el ISBN del libro seleccionado
$ISBN = $_GET['ISBN'];
//guarda la instrucción de SQL que quere utilizar, en este caso un select
$query = "SELECT idLibro,titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE ISBN = '" . $ISBN . "'";

// comprobar si la consulta es valida
if($stmt = $conn->prepare($query)){     //prepara la consulta
	$stmt->execute();                   //se ejecuta la consulta
	$result = $stmt->get_result();      //el resultado se cuarda en la variable $result
	if($result->num_rows > 0){          //comprueba si hay un libro con ese ISBM (mira si el resultado contiene filas)
		$libro = $result->fetch_assoc();//obtenemos el libro
	}
	else{
		//no hay un libro en la base de datos con ese ISBN
		echo "No se ha encontrado ningun libro";
	}
}
else{
	//la instruccion SQL no es valida
	echo "Conecxion fallida";
}

$idLibro = $libro['idLibro'];
//se obtiene el nombre de la imagen a partir del titulo
$nombimagen = "libros/" . strval($idLibro) . ".jpeg"; //imágenes
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