<?php
// Connect to the database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "aaaaaaa";
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['item_add_submit'])) {
    // Get the name and password from the form
    $titulo = $_POST['titulo'];
    echo 'titulo';
    $autor= $_POST['autor'];
    echo 'autor';
    $f_publicacion = $_POST['f_publicacion'];
    echo 'f_publicacion';
    $ISBN=$_POST['ISBN'];
    echo 'ISBN';
    $n_paginas=$_POST['n_paginas'];
    echo 'n_paginas';
    $sql = "INSERT INTO libro (titulo, autor,f_publicacion,ISBN,n_paginas)
    VALUES ('". $titulo ."', '" . $autor . "' , '" . $f_publicacion . "', '" . $ISBN . "' , '" . $n_paginas . "')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
				window.alert('Infromacion actualizada correctamente.');
				window.location.href = 'items.php';
			</script>";
		exit();
	} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
}


$conn->close();

?>



<html>
<head>
<title> Añadir libro </title>
<script src="comprobarDatosLibro.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<form name="item_add_form" method="post" onsubmit="return comprobardatosAnnadir()">
    <p align="center">Introduzca la información pedida a continuación:</p>
		Título:<br>
		<input type="text" name="titulo"> 
        Autor: <br>
		<input type="text" name="autor"> <br>
  		Fecha de Publicación:<br>
  		<input type="text" name="f_publicacion" placeholder="AAAA-MM-DD"> <br>
		ISBN:<br>
		<input type="text" name="ISBN"><br>
		Nº de Páginas:<br>
		<input type="text" name="n_paginas"> <br>
		Imagen (.jpeg):<br>
		<input type="file" name="imagen" accept=".jpeg"> <br>
	
		<br>
		<input type="submit" name="item_add_submit" class ="button" value="Añadir">
	</form>
	
	<div class="button-container">
		
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>
