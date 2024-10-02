<?php
// Establish a connection to the MySQL database
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  

}

$userId = $_GET['user']; 

$sql = "SELECT nombre,apellido,numeroDNI,telefono,nacimiento,email,usuario,contrasena FROM usuarios WHERE id = " . $userId;

$result = $conn->query($sql);
// Check if any attributes were found
if ($result->num_rows > 0) {
    // Iterate over the result set and display the attributes
    while ($row = $result->fetch_assoc()) {
        echo "<h3>Usuario</h3><br>" . $row['usuario'];
        echo "<br><h3>Nombre</h3><br>" . $row['nombre'];
        echo "<br><h3>Apellido</h3><br>" . $row['apellido'];
        echo "<br><h3>DNI</h3><br>" . $row['numeroDNI'];
        echo "<br><h3>Telefono</h3><br>" . $row['telefono'];
        echo "<br><h3>Nacimiento</h3><br>" . $row['nacimiento'];
        echo "<br><h3>Email</h3><br>" . $row['email'];
        
    }
} else {
    echo "No attributes found for user ID: " . $userId;
}

// Close the statement and the connection
$conn->close();
?>
