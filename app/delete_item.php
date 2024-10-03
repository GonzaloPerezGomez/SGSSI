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



<html>
<head>
<meta charset="UTF-8">
<title> Borrar libro </title>
<link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div><h1>¿ESTAS SEGURO DE QUE QUIERES ELIMINARLO?</h1></div>

    <div>
        <a type="button" class="button" href="items.php">Cancelar</a>
        <input type="submit" name="item_add_submit" class ="button" value="Confirmar">
    </div>
    
</body>
</html>