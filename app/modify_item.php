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

//obtenemos el id del libro
$idLibro = $_GET['idLibro'];
//guardamso la instruccion select en una variable
$query = "SELECT titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE idLibro = '" . $idLibro . "'";

//si da no fallo la preparacion del select
if($stmt = $conn->prepare($query)){
	//se hace el select en la base de datos 
	$stmt->execute();
	//se guarda el resultado del select en una variable
	$result = $stmt->get_result();
	//si hay al menos una fila(si se ha encontrado elemento)
	if($result->num_rows > 0){
		//obtenemos la primera fila
		$libro = $result->fetch_assoc();
	}
	//si no
	else{
		//no se ha encontrado el libro con esa id
		echo "No se ha encontrado ningun libro";
	}
}
//si no
else{
	//imprimimos el fallo de conexion
	echo "Conexion fallida";
}

//guardamos nombre de la portada del libro
$nombimagen = "libros/" . strval($idLibro) . ".jpeg"; //imágenes
//quitemos los espacios por guiones
$nombimagen = str_replace(" ", "-", $nombimagen);

// cuando se pulsa el botón "Guardar" entra en el if:
if (isset($_POST['item_modify_submit'])) {
    // guardar la info del formulario

	//el titulo
    $titulo = $_POST['titulo'];
	//el autor
    $autor= $_POST['autor'];
	//la fecha de publicacion
    $f_publicacion = $_POST['f_publicacion'];
	//el ISBN
    $ISBN=$_POST['ISBN'];
	//el numero de paginas
    $n_paginas=$_POST['n_paginas'];
	//el id del libro
	$idLibro = $_GET['idLibro'];
	//guardamos la instruccion updeta
    $sql = "UPDATE libro SET titulo='$titulo', autor='$autor' , f_publicacion='$f_publicacion' , ISBN='$ISBN' , n_paginas='$n_paginas' WHERE idLibro = '$idLibro'";
	if (isset($_FILES["imagen"])) {
		$target_dir = "/var/www/imagen/";
		$target_file = $target_dir . strval($idLibro) . ".jpeg"; //imágenes
		move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
	}

	//si la instruccion se realiza correctamente(resulatdo del update es true)
	if ($conn->query($sql) === TRUE) {
		//imprimimos por pantalla
        echo "<script>
			<!--la informacion es correcta-->
			window.alert('Infromacion actualizada correctamente');
			<!--redirigimos a la pagina items.php
			window.location.href = 'items.php';
		</script>";
		//se cierra conexion
		$conn->close();
		exit();
	//si no
	} else {
		//indicamso el fallo
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
	//cerramos conexion
    $conn->close();
}
$stmt->close();

?>


<html>
<head>
<!-- título que se pondrá en la página --> 
<title> Editar libro </title>
<!-- indica desde que script realizará las comprobaciones --> 
<script src="comprobarDatosLibro.js"></script>
<!-- indica desde que script modela la página web--> 
<link rel="stylesheet" href="estilo.css">
</head>	
	<body>
	<!-- crea un formulario con el nombre item_add_form que realizará un método post en base al resultado del método comprobardatosModificar--> 	
	<form name="item_modify_form" method="POST" onsubmit="return comprobardatosModificar()" enctype="multipart/form-data">
		<!-- centra el párrafo que contendra todos los campos a tendran el valor actual del objeto --> 
		<p align="center"> Introduzca la información pedida a continuación:</p>
		<?php
		echo 
		"
		Título:<br>
		<input type= text name= titulo value= '{$libro['titulo']}'>
        Autor: <br>
		<input type= text  name= autor value=  '{$libro['autor']}'> <br>
  		Fecha de Publicación:<br>
  		<input type= text  name= f_publicacion value= " . $libro['f_publicacion'] . "> <br>
		ISBN:<br>
		<input type= text  name= ISBN value= " . $libro['ISBN'] . " ><br>
		Nº de Páginas:<br>
		<input type= text  name= n_paginas value= " . $libro['n_paginas'] . "> <br>
		Imagen:<br>
		<img src='" . $nombimagen . "' style='height: 150px;'> <br>
		Cambiar imagen (.jpeg):<br>
		<input type='file' name='imagen' accept='.jpeg'> <br>
		"
		?>
		<br>
		<!-- se trata de un botón del tipo submit, que tras ser pulsado, comienza las comprobaciones para modificar los datos del libro en la base de datos--> 	
		<input type="submit" name="item_modify_submit" class ="button" value="Guardar" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
		
	</form>
	<!-- contenedor de botones--> 
	<div class="button-container">
		<!-- botón normal que al pulsar redirige página a items.php --> 
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	
<html>
