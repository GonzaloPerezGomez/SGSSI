<?php

function encrypt($plaintext){
    $key = getenv('MYSQL_KEY');
    $iv = getenv('MYSQL_IV');
    
    $encrypted = openssl_encrypt(strval($plaintext), 'aes-256-cbc', $key, 0, $iv);
    return $encrypted;
}

function decrypt($plaintext){
    $key = getenv('MYSQL_KEY');
    $iv = getenv('MYSQL_IV');

    $decrypted = openssl_decrypt(strval($plaintext), 'aes-256-cbc', $key, 0, $iv);
    return $decrypted;
}

?>