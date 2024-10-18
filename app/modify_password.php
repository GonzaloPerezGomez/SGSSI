<?php

//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_start();

// conexión a la base de datos
//guarda el nombre del servidor a conectar
$servername = "db";
//guarda el nombre del usuario necesario para acceder al servidor
$username = "admin";
//guarda la contraseña del usuario en una variable
$password = "test";
//guarda el nombre del de la base de datos a la que quiere acceder
$dbname = "database";

//se realiza la conexión en el servidor con el usuario introducido en la base de datos introducida (db, database)
$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión

// si la variable que guarda la conexión es un error 
if ($conn->connect_error) {
    //detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión
    die("Connection failed: " . $conn->connect_error);
}

// comprobar si se ha enviado el formulario
if (isset($_SESSION['user_id'])) {

    //se obtiene el id del usuario que tenga la sesión iniciada
	$userId=$_SESSION['user_id'];
	//guarda la instrucción de SQL que quere utilizar, en este caso un select
    $query = "SELECT contrasena FROM usuarios WHERE idUsuario = " . $userId;

    //se obtiene la contraseña actual del usuario para poder compararla con la nueva
    if($stmt = $conn->prepare($query)){     //prepara la consulta
        $stmt->execute();                   //se ejecuta la consulta
        $result = $stmt->get_result();      //el resultado se guarda en la variable $result
        if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
            $cont = $result->fetch_assoc();//obtenemos la contraseña
            $contrasena = $cont['contrasena'];
        }
        else{
            //no se ha encontrado un usuario con ese id
            echo "No attributes found for user ID: " . $userId;
        }
    }
    else{
        //la instrucción no es valida
        echo "Conexion fallida";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $actualcontrasena = $_POST['actualcontrasena'];
        $nuevacontrasena1 = $_POST['nuevacontrasena1'];
        $nuevacontrasena2 = $_POST['nuevacontrasena2'];
        
        //se comprueba si la contraseña introducida es correcata
        if ($contrasena==$actualcontrasena) {
            //se comprueba si las nuevas contraseñas con la misma
            if($nuevacontrasena1==$nuevacontrasena2){
                 //para que la nueva contraseña no puedan ser espacios en blanco y que sea una sola palabra
                if(trim($nuevacontrasena1)!= '' && strpos(trim($nuevacontrasena1), ' ') === false) {
                    // Preparar la consulta SQL (utilizando prepared statements para prevenir inyecciones SQL)
                    $sql = "UPDATE usuarios SET contrasena='" . $nuevacontrasena1 . "' WHERE idUsuario= " . $_SESSION['user_id'];
                    $stmt = $conn->prepare($sql);
                    // Ejecutar la consulta
                    if ($stmt->execute()) {
                        echo "<script>
                        window.alert('Cambios guardados correctamente.');
                        window.location.href = 'show_user.php';
                        </script>";
                    } else {
                        //la instrucción no se ha ejecutado correctamente
                        echo "Error al guardar los cambios: " . $stmt->error;
                    }
                } else {echo "<script> window.alert('La contraseña nueva no es válida'); </script>";}
            }
            else {echo "<script> window.alert('Las nuevas contraseñas no coinciden'); </script>";}
        }
        else {echo "<script> window.alert('La contraseña actual no coincide con tu contraseña'); </script>";}
    }
}

// cerrar conexión
$conn->close();
?>

<html>
<head>
    <title> Modificar Contraseña </title>
    <link rel="stylesheet" href="estilo.css">
</head>
	<body>
	<form name="user_modify_password" method="POST">
		<br>
  		Introduzca su contraseña actual:<br>
  		<input type= text  name= actualcontrasena placeholder="contraseña actual" required> <br>
		Introduzca la nueva contraseña:<br>
		<input type= text  name= nuevacontrasena1 placeholder="nueva contraseña" required> 
		<input type= text  name= nuevacontrasena2 placeholder="repita la nueva contraseña" required> <br>

		<input type="submit" value="Guardar cambios"name="modify_password_submit" style="color:black;font-family:'Baskerville',serif;font-weight:bold;">
	</form>

	<div class="button-container">
		<a class="button" href="show_user.php">Volver</a>
	</div>	
	
<html>
