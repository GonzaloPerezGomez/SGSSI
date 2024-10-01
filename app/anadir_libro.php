<html>
<head>
<title> Añadir libro </title>
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<?php include 'add_item_database.php'; ?>
	<form name="item_add_form" action="add_item_database.php" method="post">
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
		<input type="submit" name="submit" class ="button" value="Añadir">
	</form>
	
	<div class="button-container">
		
		<a class="button" href="index.php">Cancelar</a>
	</div>	
	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>