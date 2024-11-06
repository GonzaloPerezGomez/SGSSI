<?php
session_start();


//comprueba si se ha iniciado sesion
if (!isset($_SESSION['user_id']) ) {
   header("Location: index.php");
   exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<html>
    <head>
    <meta http-equiv="Content-Security-Policy"
		content="default-src 'none';
			style-src 'self' 'nonce-abc123' ;
			img-src 'self' http://localhost:81/image/background.jpg ;
			form-action 'none';">
    <meta charset="UTF-8">
    <!-- título que se pondrá en la página --> 
    <title> Catálogo </title>
    <!-- indica desde que script modela la pagina web --> 
    <link nonce="abc123" rel="stylesheet" href="estilo.css">
    </head>

    <body>
    <br>
        <?php
        if ($_SESSION['tipo']=='admin'){
            echo 
                "
                <!--Contenedor de botones-->
                <div class=button-container>
                    <!--botón normal que al pulsar se redirige a la página add_item.php-->
                    <a class=button href=add_item.php>Añadir libro</a>
                    <!-- botón normal que al pulsar se redirige a la página index.php-->
                    <a class=button href=index.php>Volver a inicio</a>
                </div>";}
        else{
            echo 
                "
                <!--Contenedor de botones-->
                <div class=button-container>
                    <!-- botón normal que al pulsar se redirige a la página index.php-->
                    <a class=button href=index.php>Volver a inicio</a>
                </div>";}


        // conexión a la base de datos
        //guarda el nombre del servidor a conectar
        $hostname = "db";
        //guarda el nombre del usuario necesario para acceder al servidor
        $username = "admin";
        //guarda la contraseña del usuario en una variable
        $password = "test";
        //guarda el nombre del de la base de datos a la que quiere acceder
        $db = "database";

        //se realiza la conexión en el servidor con el usuario introducido en la base de datos introducida (db, database)
        $conn = mysqli_connect($hostname,$username,$password,$db);

        // comprobar conexión
        
        // si la variable que guarda la conexión es un error 
        if ($conn->connect_error) {
            //detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión
            die("Database connection failed: " . $conn->connect_error);
        }

        ?>

        <?php
        //Guardamos el valor que obtenemos al realizar una select en la base de datos, si hay error paramos el proceso
        $query = mysqli_query($conn, "SELECT idLibro, titulo, autor, ISBN  FROM libro")
        or die (mysqli_error($conn));

        if ($_SESSION['tipo']=='admin'){
            echo 
                "
                <!--crea una tabla-->
                <table border='1'>
                    <thead>
                        <tr>
                            <!--titulo de la primera columna-->
                            <th>Imagen</th>
                            <!--titulo de la segunda columna-->
                            <th>Título</th>
                            <!--titulo de la tercera columna-->
                            <th>Autor</th>
                            <!--titulo de la cuarta columna-->
                            <th>Editar / Borrar</th>
                        </tr>
                    </thead>
                <tbody>";}
        else{
            echo 
                "
                <!--crea una tabla-->
                <table border='1'>
                    <thead>
                        <tr>
                            <!--titulo de la primera columna-->
                            <th>Imagen</th>
                            <!--titulo de la segunda columna-->
                            <th>Título</th>
                            <!--titulo de la tercera columna-->
                            <th>Autor</th>
                        </tr>
                    </thead>
                <tbody>";
            }

        //mientras haya filas sin estudiar
        while ($row = mysqli_fetch_array($query)) {
            //guardamos nombre de la portada del libro
            $nombimagen = "libros/" .htmlspecialchars(strval($row['idLibro'])) . ".jpeg"; //imágenes
            //imprimimos por pantalla
            if ($_SESSION['tipo']=='admin'){
                $idLibro = htmlspecialchars($row['idLibro']);
                $ISBN = htmlspecialchars($row['ISBN']);
                echo 
                "
                <tr>
                    <td>
                    
                        <!--referencia as show_item.php cargado con el ISBN del libro-->
                        <form method='POST' action='show_item.php'>
                                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' name='ISBN' value=" . htmlspecialchars($ISBN) . ">
                                <button type='submit'>
                                    <img src='$nombimagen' class='imagen_catalogo'>
                                </button>
                        </form>

                    </td>
                    <!--informacion del titulo del libro-->
                    <td>" . htmlspecialchars($row['titulo']) . "</td>
                    <!--informacion del autor del libro-->
                    <td>" . htmlspecialchars($row['autor']) . "</td> 
                    <td>
                        <!--contenedor de los botones de modificacion y eliminacion con su respectivas imagenes-->
                        <div class=button-container>
                            <form method='POST' action='modify_item.php'>
                                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' name='idLibro' value=" . htmlspecialchars($idLibro) . ">
                                <button type='submit'>
                                    <img src='image/editar.png' class='imagen_funcionalidades'>
                                </button>
                            </form>
                            <form method='POST' action='delete_item.php'>
                                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' name='ISBN' value='" . htmlspecialchars($ISBN) . "'>
                                <button type='submit'>
                                    <img src='image/borrar.png' class='imagen_funcionalidades'>
                                </button>
                            </form>


                        </div>
                    </td>
                </tr>";}
            else{
                echo 
                "
                <tr>
                    <td>
                        <!--referencia as show_item.php cargado con el ISBN del libro-->
                        <form method='POST' action='show_item.php'>
                                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>
                                <input type='hidden' name='ISBN' value='" . htmlspecialchars($ISBN) . "'>
                                <button type='submit'>
                                    <img src='$nombimagen' class='imagen_catalogo'>
                                </button>
                        </form>
                    </td>
                    <!--informacion del titulo del libro-->
                    <td>" . htmlspecialchars($row['titulo']) . "</td>
                    <!--informacion del autor del libro-->
                    <td>" . htmlspecialchars($row['autor']) . "</td>
                </tr>";}
        }

        ?>

    </tbody>
    </table>

    </body>
</html>
