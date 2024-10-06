<?php
// conexión a la base de datos
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión
if ($conn->connect_error) {
    echo "aaaaaaa";
    die("Connection failed: " . $conn->connect_error);
}

$ISBN = $_GET['ISBN'];

if (isset($_POST['item_delete_submit'])) {
    $sql = "DELETE FROM libro WHERE ISBN=" . $ISBN ;
    if ($conn->query($sql) === TRUE) {
        echo "<script>
				window.alert('Libro eliminado correctamente.');
				window.location.href = 'items.php';
			</script>";
		$conn->close();
		exit();
	} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    $conn->close();
}

?>

<html>
<head>
<meta charset="UTF-8">
<title> Borrar libro </title>
<link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div><h1>¿ESTÁS SEGURO DE QUE QUIERES ELIMINARLO?</h1></div>

    <form method="post">
        <input type="submit" name="item_delete_submit" value='Confirmar' style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
        <br>
        <a type="button" class="button" href="items.php">Cancelar</a>
    </form>
    
</body>
</html>