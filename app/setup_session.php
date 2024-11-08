<?php

//funcion que almacena la sesion iniciada en la web a lo largo de todo su funcionamiento
session_set_cookie_params([
    'lifetime' => 0,                // Session cookie (expires when the browser is closed)
    'path' => '/',                  // Cookie is valid throughout the domain
//    'secure' => true,               // Send cookie only over HTTPS connections
    'httponly' => true,              // Cookie is inaccessible to JavaScript
    'samesite' => 'Strict'
]);
session_start();

?>