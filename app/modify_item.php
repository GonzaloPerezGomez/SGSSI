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

if (isset($_POST['submit'])) {
    // Get the name and password from the form
    $titulo = $_POST['titulo'];
    $autor= $_POST['autor'];
    $f_publicacion = $_POST['f_publicacion'];
    $ISBN=$_POST['ISBN'];
    $n_paginas=$_POST['n_paginas'];
    $sql = "UPDATE libro SET titulo='$titulo', autor='$autor' , f_publicacion='$f_publicacion' ,
    ISBN='$ISBN' , n_paginas='$n_paginas' WHERE ISBN = '$ISBN'";
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
     
}


$query = "SELECT titulo, autor, f_publicacion, n_paginas FROM libro WHERE ISBN = ? ";

if($stmt = $conn->prepare($query)){
	$ISBN = $_GET['ISBN'];
	$stmt->bind_param("s", $ISBN);
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
	echo "Conecxion fallida";
}

$stmt->close();
?>



<html>
<head>
<title> Editar libro </title>
<link rel="stylesheet" href="estilo.css">
</head>
	
	
	<body>
	<form name="item_modify_form" method="POST">
		<p align="center"> Introduzca la información pedida a continuación:</p>
		<?php
		echo
		"
		Título:<br>
		<input type= text name= titulo value= " . $libro['titulo'] . "> 
        Autor: <br>
		<input type= text  name= autor value=  " . $libro['autor'] . "> <br>
  		Fecha de Publicación:<br>
  		<input type= text  name= f_publicacion value= " . $libro['f_publicacion'] . "> <br>
		ISBN:<br>
		<input type= text  name= ISBN value= " . $ISBN . " ><br>
		Nº de Páginas:<br>
		<input type= text  name= n_paginas value= " . $libro['n_paginas'] . "> <br>
		"
		?>
		<br>
		<input type="submit" name="item_modify_submit" class ="button" value="Guardar" >
		
	</form>
	
	<div class="button-container">
		<a class="button" href="items.php">Cancelar</a>
	</div>	
	
	<footer>
        	<p align="center">&copy; 2023 Mi sitio web</p>
	</footer>
<html>