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

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get the name and password from the form
    $titulo = $_POST['titulo'];
    $autor= $_POST['autor'];
    $f_publicacion = $_POST['f_publicacion'];
    $ISBN=$_POST['ISBN'];
    $n_paginas=$_POST['n_paginas'];
    $sql = "INSERT INTO libro (titulo, autor,f_publicacion,ISBN,n_paginas)
    VALUES ('". $titulo ."', '" . $autor . "' , '" . $f_publicacion . "', '" . $ISBN . "' , '" . $n_paginas . "')";
    
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Prepare and execute the SQL statement to insert the data
   
}

// Close the database connection
$conn->close();
?>