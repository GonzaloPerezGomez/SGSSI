<?php

session_start();

//comprueba si se ha iniciado sesion
if (!isset($_SESSION['user_id']) || $_SESSION['tipo'] != 'admin') {
    header("Location: items.php");
    exit();
}


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token inválido.");
}


// conexión a la base de datos
//guarda el nombre del servidor a conectar
$servername = "db";
//guarda el nombre del usuario necesario para acceder al servidor
$username = "admin";
//guarda la contraseña del usuario en una variable
$password = "test";
//guarda el nombre del de la base de datos a la que quere acceder
$dbname = "database";

//se realiza la conexión en el servidor con el usuario introducido en la besa de datos introducida (db, database)
$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión

// si la variable que guarda la conexión es un error 
if ($conn->connect_error) {
    //para el proceso(die) e indica por pantalla la causa del fallo de conexión 
    die("Connection failed: " . $conn->connect_error);
}

//
$ISBN = isset($_POST['ISBN']) ? trim($_POST['ISBN']) : '';

 //si se ha pulsado el botón que llama a item_delete_submit
if (isset($_POST['item_delete_submit'])) {

    // Verificación del token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido. Operación no permitida.");
    }

    //se guarda la instrucción de SQL que se quiere aplicar en la base de datos en este caso delete 
    $sql = "DELETE FROM libro WHERE ISBN = ?" ;

    $sth = $conn->prepare($sql);
	$sth->bind_param('s', $ISBN);

    //si al realizar el delete en sql el resultado es true(se ha realizado la introduccion)
    if ($sth->execute() === TRUE) {
        unset($_SESSION['csrf_token']);

        //pone por pantalla
        echo "<script>
            <!--un aviso de que el libro se ha añadido correctamente -->
			window.alert('Libro eliminado correctamente.');
            <!--nos lleva a la pagina items.php-->
			window.location.href = 'items.php';
		</script>";
        //cierra conexión con la base de datos
		$conn->close();

        // Para eliminar la cookie del CSRF Token
		exit();
    } 
    //si no
    else {
        //indica el error al introducir el nuevo libro
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    //cierra conexión con la base de datos
    $conn->close();
}

?>

<html>
<head>
    <!-- comentario--> 
    <meta charset="UTF-8">
    <!-- titulo que se pondra en la pagina --> 
    <title> Borrar libro </title>
    <!-- indica desde que script realizara las comprobaciones --> 
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <br>
    <div><h1>¿ESTÁS SEGURO DE QUE QUIERES ELIMINARLO?</h1></div>
    <!-- crea un formulario que realizará un metodo post  --> 
    <form method="post">

    <!-- Campo oculto para el token CSRF -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="hidden" name="ISBN" value="<?php echo htmlspecialchars($ISBN); ?>">
        <!-- se trata de un boton del tipo submit-->
        <input type="submit" name="item_delete_submit" value='Confirmar' style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
        
        <br>
        <!-- botton normal que al pulsar redirige la pagina a items.php --> 
        <a type="button" class="button" href="items.php">Cancelar</a>
    </form>
</body>
</html>