<?php
// conexión a la base de datos

require 'setup_session.php';

if (!isset($_SESSION['randomID'])) {
    echo "<script> window.location.href = 'items.php';</script>";
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	echo "<script>
		window.alert('no se puede modificar el libro, pruebelo mas tarde');
		window.location.href = 'items.php';
	</script>";
    exit();
}



require 'setup_sql.php';

//obtenemos el id del libro
$idLibro = isset($_POST['idLibro']) ? intval(trim($_POST['idLibro'])) : 0;

//guardamso la instruccion select en una variable
$sql = "SELECT titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE idLibro = ?";
$sth = $conn->prepare($sql);
$sth->bind_param('i', $idLibro);

//si da no fallo la preparacion del select
try {
	$sth->execute();
	//se guarda el resultado del select en una variable
	$result = $sth->get_result();
	//si hay al menos una fila(si se ha encontrado elemento)
	if($result->num_rows > 0){
		//obtenemos la primera fila
		$libro = $result->fetch_assoc();
	}
	//si no
	else{
		//no se ha encontrado el libro con esa id
		echo "<script> window.alert(" . $_POST['csrf_token'] !== $_SESSION['csrf_token'] . "); </script>";
	}
}catch(Exception $e){
	echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
	$error_message = 'Excepcion de select: ' . htmlspecialchars($e). '. Error: ' . htmlspecialchars($conn->error);
	file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
}
//guardamos nombre de la portada del libro
$nombimagen = "libros/" . strval($idLibro) . ".jpeg"; //imágenes
echo "<script> window.alert($nombimagen); </script>";
//quitemos los espacios por guiones
$nombimagen = str_replace(" ", "-", $nombimagen);

// cuando se pulsa el botón "Guardar" entra en el if:
if (isset($_POST['item_modify_submit'])) {
	// Verificación del token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		$error_message = 'sin token:' . htmlspecialchars($_POST['csrf_token']) . ' o tokens diferentes: ' . htmlspecialchars($_POST['csrf_token']) . ' != ' . htmlspecialchars($_SESSION['csrf_token']);
        file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - Error CSRF: " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
		echo "<script>
					window.alert('no ha sido modificar el libro, pruebalo mas tarde');
					window.location.href = 'items.php';
				</script>";
        exit();
    }

    // guardar la info del formulario

	//el titulo
    $titulo = htmlspecialchars($_POST['titulo']);
	//el autor
    $autor= htmlspecialchars($_POST['autor']);
	//la fecha de publicacion
    $f_publicacion = htmlspecialchars($_POST['f_publicacion']);
	//el ISBN
    $ISBN=htmlspecialchars($_POST['ISBN']);
	//el numero de paginas
    $n_paginas=htmlspecialchars($_POST['n_paginas']);
	//el id del libro
	$idLibro = htmlspecialchars($_POST['idLibro']);
	//guardamos la instruccion update
    $sql = "UPDATE libro SET titulo= ?, autor= ? , f_publicacion= ? , ISBN= ? , n_paginas= ? WHERE idLibro = ?";

	$sth = $conn->prepare($sql);
	$sth->bind_param("sssssi", $titulo, $autor, $f_publicacion, $ISBN, $n_paginas, $idLibro);

	//si la instruccion se realiza correctamente(resulatdo del update es true)
	try {
		$sth->execute();

		unset($_SESSION['csrf_token']);

		if (isset($_FILES["imagen"])) {
			$target_dir = "/var/www/imagen/";
			$target_file = $target_dir . strval($idLibro) . ".jpeg"; //imágenes
			if (file_exists($target_file)) {
				unlink($target_file);  // Eliminar la imagen anterior
			}
			move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
		}

		//imprimimos por pantalla
        echo "<script>
			<!--la informacion es correcta-->
			window.alert('Infromacion actualizada correctamente');
			<!--redirigimos a la pagina items.php
			window.location.href = 'items.php';
		</script>";
		exit();
	}catch(Exception $e){
		echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
		$error_message = 'Excepcion de select: ' . htmlspecialchars($e). '. Error: ' . htmlspecialchars($conn->error);
		file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
	}
	//cerramos conexion
    $conn->close();
}
$sth->close();

?>


<html>
<head>
	<meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			script-src 'self' 'nonce-abc123' ;
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'self';">
	<!-- título que se pondrá en la página --> 
	<title> Editar libro </title>
	<!-- indica desde que script realizará las comprobaciones --> 
	
	<!-- indica desde que script modela la página web--> 
	<link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>	
	<body>
	<!-- crea un formulario con el nombre item_add_form que realizará un método post en base al resultado del método comprobardatosModificar--> 	
	<form name="item_modify_form" method="POST" id="item_modify_form" enctype="multipart/form-data" autocomplete="off">
		<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
		<input type="hidden" name="idLibro" value="<?php echo htmlspecialchars($idLibro); ?>">
		<!-- centra el párrafo que contendra todos los campos a tendran el valor actual del objeto --> 
		<p align="center"> Introduzca la información pedida a continuación:</p>
		<?php
		echo 
		"
		
		Título:<br>
		<input type= text name= titulo value= '". htmlspecialchars($libro['titulo']). "'>
        Autor: <br>
		<input type= text  name= autor value=  '". htmlspecialchars($libro['autor']). "'><br>
  		Fecha de Publicación:<br>
  		<input type= text  name= f_publicacion value= '". htmlspecialchars($libro['f_publicacion']). "'><br>
		ISBN:<br>
		<input type= text  name= ISBN value= '". htmlspecialchars($libro['ISBN']). "'><br>
		Nº de Páginas:<br>
		<input type= text  name= n_paginas value= '". htmlspecialchars($libro['n_paginas']). "'> <br>
		Imagen:<br>
		<img src='" . $nombimagen . "' class='imagen_show'> <br>
		Cambiar imagen (.jpeg):<br>
		<input type='file' name='imagen' accept='.jpeg'> <br>
		"
		?>
		<br>
		<!-- se trata de un botón del tipo submit, que tras ser pulsado, comienza las comprobaciones para modificar los datos del libro en la base de datos--> 	
		<input type="submit" name="item_modify_submit" class ="button" value="Guardar" >
		
	</form>
	<!-- contenedor de botones--> 
	<div class="button-container">
		<!-- botón normal que al pulsar redirige página a items.php --> 
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	<script nonce="abc123" src="comprobarDatosLibro.js"></script>
	</body>
</html>
