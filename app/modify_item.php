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

//se realiza la conexión en el servidor con el usuario introducido en la besa de datos introducida (db, database)
$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión

// si la variable que guarda la conexión es un error 
if ($conn->connect_error) {

	//para el proceso(die) e indica por pantalla la causa del fallo de conexión 
    die("Connection failed: " . $conn->connect_error);
}

$idLibro = $_GET['idLibro'];
$query = "SELECT titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE idLibro = '" . $idLibro . "'";

if($stmt = $conn->prepare($query)){
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows > 0){
		$libro = $result->fetch_assoc();
	}
	else{
		echo "No se ha encontrado ningun libro";
	}
}
else{
	echo "Conexion fallida";
}

$nombimagen = "libros/" . strtolower($libro['titulo'] . ".jpeg"); //imágenes
$nombimagen = str_replace(" ", "-", $nombimagen);

// cuando se pulsa el botón "Guardar" entra en el if:

if (isset($_POST['item_modify_submit'])) {
    // guardar la info del formulario
    $titulo = $_POST['titulo'];
    $autor= $_POST['autor'];
    $f_publicacion = $_POST['f_publicacion'];
    $ISBN=$_POST['ISBN'];
    $n_paginas=$_POST['n_paginas'];
	$idLibro = $_GET['idLibro'];
    $sql = "UPDATE libro SET titulo='$titulo', autor='$autor' , f_publicacion='$f_publicacion' , ISBN='$ISBN' , n_paginas='$n_paginas' WHERE idLibro = '$idLibro'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
				window.alert('Infromacion actualizada correctamente.');
				window.location.href = 'items.php';
			</script>";
		$conn->close();
		exit();
	} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    $conn->close();
}
$stmt->close();

?>


<html>
<head>
<title> Editar libro </title>
<script src="comprobarDatosLibro.js"></script>
<link rel="stylesheet" href="estilo.css">
</head>	
	<body>
	<form name="item_modify_form" method="POST" onsubmit="return comprobardatosModificar()">
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
		<input type="submit" name="item_modify_submit" class ="button" value="Guardar" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
		
	</form>
	
	<div class="button-container">
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	
<html>
