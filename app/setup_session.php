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

//se encarga de realizar un conteo del tiempo no activo, si pasan 20 minutos se cierra sesion
$tiempoExpiracion = 1200;//20 minutos

if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $tiempoExpiracion) {
        session_unset();     
        session_destroy();   
    }
}
$_SESSION['last_activity'] = time();
?>