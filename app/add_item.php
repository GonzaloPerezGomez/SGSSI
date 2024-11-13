<?php

require 'setup_session.php';

//rutas de los archivos de log
$error_log_file = '/var/www/logs/errores.log';

//comprueba si se ha iniciado sesion
if (!isset($_SESSION['randomID']) ||  $_SESSION['tipo'] != 'admin') {
    echo "<script> window.location.href = 'items.php';</script>";
    exit();
}


// Genera el token CSRF si no existe en la sesión
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio seguro
}


require 'setup_sql.php';

//si se ha pulsado el botón que llama a item_add_submit
if (isset($_POST['item_add_submit'])) {

	// Verificación del token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		$error_message = 'sin token:' . htmlspecialchars($_POST['csrf_token']) . ' o tokens diferentes: ' . htmlspecialchars($_POST['csrf_token']) . ' != ' . htmlspecialchars($_SESSION['csrf_token']);
        file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - Error CSRF: " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
		echo "<script>
					window.alert('no ha sido posible añadir el libro, pruebalo mas tarde');
					window.location.href = 'items.php';
				</script>";
		exit();
	}

    // guardar la información del formulario
    //guarda el título del libro
    $titulo = htmlspecialchars($_POST['titulo']);
    //guarda el autor del libro
    $autor= htmlspecialchars($_POST['autor']);
    //guarda la fecha de publicación del libro
    $f_publicacion = htmlspecialchars($_POST['f_publicacion']);
    //guarda el ISBN del libro
    $ISBN=htmlspecialchars($_POST['ISBN']);
    //guarda el número de páginas del libro
    $n_paginas=htmlspecialchars($_POST['n_paginas']);
	
	//guarda la instrucción de SQL que quire utilizar, en este caso un select
	$sql = "SELECT ISBN from libro where ISBN = ?";
	//se prepara la instrucción la evitar la inyección SQL
	$sth = $conn->prepare($sql);
	$sth->bind_param('s', $ISBN);
	//realiza el comando en la base de datos y almacena el resultado en una variable
	try {
		$sth->execute();
		//se ejecuta la instrucción
		$result = $sth->get_result();
		//si el select nos devuelve un valor mayor que 0,(hay otro libro en la bd con ese isbn)
		if ($result ->num_rows > 0){ 
			//imprime por pantalla un mensaje indicando que ya existe un libro con ese ISBN
			echo "<script> window.alert('No se puede añadir, ya existe un libro con ese ISBN'); </script>";}
		//si no
		else{
			//prepara la inserción del nuevo libro con el comando de SQL insert into
			$sql = "INSERT INTO libro (titulo, autor,f_publicacion,ISBN,n_paginas)
			VALUES (?,?,?,?,?)";
			
			$sth = $conn->prepare($sql);
			$sth->bind_param("sssss", $titulo, $autor, $f_publicacion, $ISBN, $n_paginas);

			//si al realizar el insert into en sql, el resultado es true(se ha realizado la introducción)
			try {
                $sth->execute();

				unset($_SESSION['csrf_token']);
				$sqlId = "SELECT idLibro from libro where ISBN = ?";

				$sth = $conn->prepare($sqlId);
				$sth->bind_param('s', $ISBN);

				//realiza el comando en la base de datos y almacena el resultado en una variable
				try {
					$sth->execute();                   //se ejecuta la consulta
					$resultId = $sth->get_result();  
					$libroId = $resultId->fetch_assoc();    //el resultado se cuarda en la variable $result
					$idLibro = $libroId['idLibro'];
					// Procesar la imagen        
					$target_dir = "/var/www/imagen/";
					$target_file = $target_dir . strval($idLibro) . ".jpeg"; //imágenes
					move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
			
					//pone por pantalla:
					echo "<script>
							<!--un aviso de que el libro se ha añadido correctamente -->
							window.alert('Libro añadido correctamente.');
							<!--nos lleva a la pagina items.php-->
							window.location.href = 'items.php';
						</script>";

					// Para eliminar la cookie del CSRF Token
					exit();
				}catch(Exception $e){
					echo "<script> window.alert('Ocurrió un error con la imagen, intente más tarde.');</script>";
					$error_message = 'Excepcion de select para imagen en add_item: ' . htmlspecialchars($e->getMessage()). '. Error: ' . htmlspecialchars($conn->error);
					file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
				}
			}catch(Exception $e){
				echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
				$error_message = 'Excepcion de insert into en add_item: ' . htmlspecialchars($e->getMessage()). '. Error: ' . htmlspecialchars($conn->error);
				file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
			}
		}
	}catch(Exception $e){
		echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
		$error_message = 'Excepcion de select en add_item: ' . htmlspecialchars($e->getMessage()). '. Error: ' . htmlspecialchars($conn->error);
		file_put_contents($error_log_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
	}
}

//cierra conexión con la base de datos
$conn->close(); 

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
	<title> Añadir libro </title>
	<!-- indica desde que script modela la página web--> 
	<link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<!-- crea un formulario con el nombre item_add_form que realizará un método post en base al resultado del método comprobardatosAnnadir --> 
	<form name="item_add_form" method="post" id="item_add_form" enctype="multipart/form-data">
    <!-- Campo oculto para el token CSRF -->
	<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

	<!-- centra el párrafo que contendra todos los campos a rellenar obligatoriamente (gracias al required) --> 
	<p align="center">Introduzca la información pedida a continuación:</p>
		Título:<br>
		<input type="text" name="titulo" autocomplete="off" required> 
        Autor: <br>
		<input type="text" name="autor" autocomplete="off" required> <br>
  		Fecha de Publicación:<br>
  		<input type="text" name="f_publicacion" placeholder="AAAA-MM-DD" autocomplete="off" required> <br>
		ISBN:<br>
		<input type="text" name="ISBN" autocomplete="off" required><br>
		Nº de Páginas:<br>
		<input type="text" name="n_paginas" autocomplete="off" required> <br>
		Imagen (.jpeg):<br>
		<input type="file" name="imagen" accept=".jpeg" autocomplete="off" required> <br>
		<br>

		<!-- se trata de un botón del tipo submit, que tras ser pulsado, comienza las comprobaciones para introducir el libro en la base de datos--> 
		<input type="submit" name="item_add_submit" class ="button" value="Añadir" >
	</form>

	<!-- contenedor de botones--> 
	<div class="button-container">
	<!-- botón normal que al pulsar redirige página a items.php --> 
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	<!-- indica desde que script realizará las comprobaciones --> 
	<script nonce="abc123" src="comprobarDatosLibro.js"></script>
	</body>
<html>
