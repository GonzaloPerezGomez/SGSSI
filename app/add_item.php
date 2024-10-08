<?php
// conexión a la base de datos

//guarda el nombre del servidor a conectar
$servername = "db";
//guarda el nombre del usuario necesario para acceder al servidor
$username = "admin";
//guarda la contraseña del usuario en una variable
$password = "test";
//guarda el nombre del de la base de datos a la que quere acceder
$dbname = "database";

//se realiza la conexión en el servidor con el usuario introducido en la besa de datos introducida (db, database)
$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión

// si la variable que guarda la conexión es un error 
if ($conn->connect_error) {
    //para el proceso(die) e indica por pantalla la causa del fallo de conexión 
    die("Connection failed: " . $conn->connect_error);
}
//si se ha pulsado el botón que llama a item_add_submit
if (isset($_POST['item_add_submit'])) {
    // guardar la información del formulario
    //guarda el titulo del libro
    $titulo = $_POST['titulo'];
    //guarda el autor del libro
    $autor= $_POST['autor'];
    //guarda la fecha de publicacion del libro
    $f_publicacion = $_POST['f_publicacion'];
    //guarda el ISBN del libro
    $ISBN=$_POST['ISBN'];
    //guarda el número de páginas del libro
    $n_paginas=$_POST['n_paginas'];
	
	//guardala instruccion de SQL que quere utilizar en este caso un select
	$sql = "SELECT ISBN from libro where ISBN = '" . $ISBN . "'";
	//realizamos el comando en la base de datos y almacena el resultado en una variable
	$result = $conn->query($sql);

	//si el select nos devuelve un valor mayor que 0,(hay otro libro en la bd con ese isbn)
	if ($result ->num_rows > 0){ 
		//imprimi por pantalla un mensaje indicando que ya existe un libro con ese ISBN
		echo "<script> window.alert('No se puede añadir, ya existe un libro con ese ISBN'); </script>";}
	else{
		//como no hay problemas prepara la insercion del nuevo libro con el comando de SQL insert into
		$sql = "INSERT INTO libro (titulo, autor,f_publicacion,ISBN,n_paginas)
		VALUES ('". $titulo ."', '" . $autor . "' , '" . $f_publicacion . "', '" . $ISBN . "' , '" . $n_paginas . "')";
		// Procesar la imagen        
        $target_dir = "/var/www/imagen/";
        $target_file = $target_dir . strtolower($titulo) . ".jpeg";
		$target_file = str_replace(" ", "-", $target_file); //reemplaza los espacios con -
		move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);

		//si al realizar el insert into en sql el resultado es true(se ha realizado la introduccion)
		if ($conn->query($sql) === TRUE) {
			//pone por pantalla
			echo "<script>
					//un aviso de que el libro se ha añadido correctamente 
					window.alert('Infromacion actualizada correctamente.');
					//nos lleva a la pagina items.php
					window.location.href = 'items.php';
				</script>";
			exit();
		} 
		//si no 
		else {
			//indica el error al introducir el nuevo libro
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

//cierra conexión con la base de datos
$conn->close(); 

?>



<html>
<head>
<! -- titulo que se pondra en la pagina -- > 
<title> Añadir libro </title>
<! -- indica desde que script realizara las comprobaciones -- > 
<script src="comprobarDatosLibro.js"></script>
<! -- indica desde que script modela la pagina web-- > 
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<! -- crea un formulario con el nombre item_add_form que realizará un metodo post en base al resultado del metodo comprobardatosAnnadir -- > 
	<form name="item_add_form" method="post" onsubmit="return comprobardatosAnnadir()" enctype="multipart/form-data">
    <! -- centra el parrafo que contendra todos los campos a rellenar obligatoriamente (gracias al required) -- > 
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

		<! -- se trata de un boton del tipo submit, que tras pulsar, el sistema comienza con las comprobaciones para introducir el libro en la base de datos-- > 
		<input type="submit" name="item_add_submit" class ="button" value="Añadir" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
	</form>

	<! -- contenedor de botones-- > 
	<div class="button-container">
	<! -- botton normal que al puksar no lleva a la pagina tems.php -- > 
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	
<html>
