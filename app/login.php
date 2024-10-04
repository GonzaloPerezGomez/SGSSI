<?php  session_start();

?>

<html>
<head>
<title> login </title> 
<link rel="stylesheet" href="estilo.css">
 </head>
	
	
<body>
 <header>
        <h1>Mi sitio web</h1>
       
    </header>

    <main>
        <section>
            <h2>Bienvenido</h2>  

            <!---<p align="center">Este es el contenido principal de mi página.</p>--->
        </section>
    </main>

    <?php

// Connect to the database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	
}

// Check if the form has been submitted
if (isset($_POST['login_submit'])) {
	// Get the name and password from the form
    $usuario = $_POST['nombreUsuario'];
    $contraseña=$_POST['contraseña'];
    $sql = "SELECT idUsuario from usuarios where usuario = '" . $usuario . "' and contrasena='" . $contraseña . "'";

$result = $conn->query($sql);

// Check if any attributes were found
    if ($result->num_rows > 0) {
        $returnedValues = $result->fetch_assoc();
        $_SESSION['user_id'] = $returnedValues['idUsuario'];
        echo "<script>window.location.href = 'index.php';</script>";
    }
    else {
      echo "<script>alert('Usuario o contraseña incorrectos');</script>";
    }
}


    
	$conn->close();


    ?>
    

<form name="login_form" method="post">
	<p>Introduzca el nombre del usuario y su contraseña:</p>
	Nombre de usuario:<input type="text" name="nombreUsuario" value=""> 
	Contraseña:<input type="text" name="contraseña" value=""> 
  	<br>
  
	<br>
	<input type="submit" name="login_submit" value="Acceder" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
    
</form>

<a href="index.php" class="button">Volver a inicio</a>
	
<footer>
        <p align="center">&copy; 2023 Mi sitio web</p>
</footer>
 
</body>
<html>
