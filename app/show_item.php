<?php
require 'setup_session.php';
include 'mysql_secret.php';

//rutas de los archivos de log
$error_file = '/var/www/logs/errores.log';

//comprueba si se ha iniciado sesion
if (!isset($_SESSION['randomID']) ) {
	echo "<script> window.location.href = 'index.php';</script>";
    exit();
}

if ( $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo "<script>
		window.alert('no se puede mostrar el libro, pruebalo mas tarde');
		window.location.href = 'items.php';
	</script>";
    exit();
}

require 'setup_sql.php';

//se guarda el ISBN del libro seleccionado
$ISBN = encrypt(htmlspecialchars($_POST['ISBN'])); 
//guarda la instrucción de SQL que quere utilizar, en este caso un select
$sql = "SELECT idLibro,titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE ISBN = ?";

$sth = $conn->prepare($sql);
$sth->bind_param('s', $ISBN);

// comprobar si la consulta es valida
try {
	$sth->execute();//se ejecuta la consulta
	$result = $sth->get_result();      //el resultado se cuarda en la variable $result
	if($result->num_rows > 0){      
		unset($_SESSION['csrf_token']);    
		//comprueba si hay un libro con ese ISBM (mira si el resultado contiene filas)
		$libro = $result->fetch_assoc();//obtenemos el libro
	}
	else{
		//no hay un libro en la base de datos con ese ISBN
		echo "<script> window.alert('No se ha encontrado ningun libro'); </script>";
	}
}catch(Exception $e){
	echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
	$error_message = 'Excepcion de select en show_item: ' . htmlspecialchars($e->getMessage()). '. Error: ' . htmlspecialchars($conn->error);
	file_put_contents($error_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
}

$idLibro = htmlspecialchars($libro['idLibro']);
//se obtiene el nombre de la imagen a partir del titulo
$nombimagen = "libros/" . htmlspecialchars(strval($idLibro)) . ".jpeg"; //imágenes
$nombimagen = str_replace(" ", "-", $nombimagen);

$sth->close();
?>


<html>
<head>
	<meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'none';">
	<title> Información de libro </title>
	<link nonce="abc123" rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="show_item_form" method="POST">
		<p align="center"> Introduzca la información pedida a continuación:</p>
		<?php
        //el readonly es para que no se pueda editar, es un formulario pero sin poder editarlo
		echo
		"
		Título:<br>
		<input type= text name= titulo value='" . decrypt(htmlspecialchars($libro['titulo'])) . "' readonly>
        Autor: <br>
		<input type= text  name= autor value=  '" . decrypt(htmlspecialchars($libro['autor'])) ."' readonly> <br>
  		Fecha de Publicación:<br>
  		<input type= text  name= f_publicacion value= '" . decrypt(htmlspecialchars($libro['f_publicacion'])) . "' readonly> <br>
		ISBN:<br>
		<input type= text  name= ISBN value= '" . decrypt(htmlspecialchars($ISBN)) . "' ><br>
		Nº de Páginas:<br>
		<input type= text  name= n_paginas value= '" . decrypt(htmlspecialchars($libro['n_paginas'])) . "' readonly> <br>
		Imagen:<br>
		<img src='" . $nombimagen . "' class='imagen_show'> <br>
		"
		?>
	</form>
	
	<div class="button-container">
		<a class="button" href="items.php">Volver</a>
	</div>	
	
<html>