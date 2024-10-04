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

if (isset($_POST['item_modify_submit'])) {
    // Get the name and password from the form
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
  
      // Prepare and execute the SQL statement to insert the data
    $conn->close();
}


$query = "SELECT titulo, autor, f_publicacion, ISBN, n_paginas FROM libro WHERE idLibro = ? ";

if($stmt = $conn->prepare($query)){
	$idLibro = $_GET['idLibro'];
	$stmt->bind_param("i", $idLibro);
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

$nombimagen = "image/" . strtolower($libro['titulo'] . ".jpeg");
$nombimagen = str_replace(" ", "-", $nombimagen);

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
	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>
