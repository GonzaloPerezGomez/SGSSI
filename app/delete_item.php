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
  
      // Prepare and execute the SQL statement to insert the data
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
    <div><h1>¿ESTAS SEGURO DE QUE QUIERES ELIMINARLO?</h1></div>

    <form method="post">
        <a type="button" class="button" href="items.php">Cancelar</a>
        <button type="submit" name="item_delete_submit">Confirmar</button>
    </form>
    
</body>
</html>