<?php
// Connect to the database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['item_add_submit'])) {
    // Get the name and password from the form
    $titulo = $_POST['titulo'];
    $autor= $_POST['autor'];
    $f_publicacion = $_POST['f_publicacion'];
    $ISBN=$_POST['ISBN'];
    $n_paginas=$_POST['n_paginas'];
	
	$sql = "SELECT ISBN from libro where ISBN = '" . $ISBN . "'";
	$result = $conn->query($sql);
	if ($result ->num_rows > 0){
		echo "<script> window.alert('No se puede añadir, ya existe un libro con ese ISBN'); </script>";}
	else{
		$sql = "INSERT INTO libro (titulo, autor,f_publicacion,ISBN,n_paginas)
		VALUES ('". $titulo ."', '" . $autor . "' , '" . $f_publicacion . "', '" . $ISBN . "' , '" . $n_paginas . "')";
		// Procesar la imagen        
    $target_dir = "/var/www/imagen/";
    $target_file = $target_dir . strtolower($titulo) . ".jpeg";
		$target_file = str_replace(" ", "-", $target_file);
		move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);

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
	<form name="item_add_form" method="post" onsubmit="return comprobardatosAnnadir()" enctype="multipart/form-data">
    <p align="center">Introduzca la información pedida a continuación:</p>
		Título:<br>
		<input type="text" name="titulo" required> 
        Autor: <br>
		<input type="text" name="autor" required> <br>
  		Fecha de Publicación:<br>
  		<input type="text" name="f_publicacion" placeholder="AAAA-MM-DD" required> <br>
		ISBN:<br>
		<input type="text" name="ISBN" required><br>
		Nº de Páginas:<br>
		<input type="text" name="n_paginas" required> <br>
		Imagen (.jpeg):<br>
		<input type="file" name="imagen" accept=".jpeg" required> <br>
		<br>
		<input type="submit" name="item_add_submit" class ="button" value="Añadir" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
	</form>
	
	<div class="button-container">
		
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>
