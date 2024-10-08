<?php

session_start();

// conexión a la base de datos
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);
//comprobar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  

}

if (isset($_SESSION['user_id'])) {


    $userId=$_SESSION['user_id'];

    $query = "SELECT contrasena FROM usuarios WHERE idUsuario = " . $userId;

    if($stmt = $conn->prepare($query)){     //prepara la consulta
        $userId = $_GET['user'];              //se obtiene el user
        //$stmt->bind_param("s", $userId);      //s=string
        $stmt->execute();                   //se ejecuta la consulta
        $result = $stmt->get_result();      //el resultado se cuarda en la variable $result
        if($result->num_rows > 0){          //comprueba si hay un usuario con esa id (mira si el resultado contiene filas)
            $cont = $result->fetch_assoc();//obtenemos la contraseña
            $contrasena = $cont['contrasena'];
        }
        else{
            echo "No attributes found for user ID: " . $userId;
        }
    }
    else{
        echo "Conecxion fallida";
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $actualcontrasena = $_POST['actualcontrasena'];
        $nuevacontrasena1 = $_POST['nuevacontrasena1'];
        $nuevacontrasena2 = $_POST['nuevacontrasena2'];
        
        if ($contrasena==$actualcontrasena) {
            if($nuevacontrasena1==$nuevacontrasena2){
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
                    echo "Error al guardar los cambios: " . $stmt->error;
                }
            }
            else {
                echo "<script> window.alert('Las nuevas contraseñas no coinciden'); </script>";
            }
        }
        else {
            echo "<script> window.alert('La contraseña actual no coincide con tu contraseña'); </script>";
        }
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
