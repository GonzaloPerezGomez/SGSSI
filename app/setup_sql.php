<?php

// conexión a la base de datos
//guarda el nombre del servidor a conectar
$servername = getenv('MYSQL_SERVER');
//guarda el nombre del usuario necesario para acceder al servidor
$username = getenv('MYSQL_USER');
//guarda la contraseña del usuario en una variable
$password = getenv('MYSQL_PASSWORD');
//guarda el nombre del de la base de datos a la que quiere acceder
$dbname = getenv('MYSQL_DATABASE');

//se realiza la conexión en el servidor con el usuario introducido en la base de datos introducida (db, database)
$conn = new mysqli($servername, $username, $password, $dbname);

// comprobar conexión

// si la variable que guarda la conexión es un error 
if ($conn->connect_error) {
	//detiene el proceso(die) e indica por pantalla la causa del fallo en la conexión 
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

?>