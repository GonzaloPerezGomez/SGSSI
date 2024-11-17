<?php
function id_gen($formato){

    if($formato != "usuarios" && $formato != "libro"){return -1;}

    require 'setup_sql.php';

    $encontrado = false;
    while(!$encontrado){
        $id = random_int(100000,999999);

        if($formato == "usuarios"){
            $sql = "SELECT usuario from usuarios where idUsuario = ?";
        }
        elseif($formato == "libro"){
            $sql = "SELECT titulo from libro where idLibro = ?";
        }

	    $sth = $conn->prepare($sql);
	    $sth->bind_param('i', $id);

	    try {
            $sth->execute();
            //se ejecuta la instrucción
            $result = $sth->get_result();
            if ($result ->num_rows == 0){ //comprobar si hay otro usuario con ese nombre de usuario
                $encontrado = true;
            }
        }catch(Exception $e){
            $error_file = '/var/www/logs/errores.log';

            echo "<script> window.alert('Ocurrió un error, intente más tarde.');</script>";
            $error_message = 'Excepcion al generar id en ' . $formato . ': ' . htmlspecialchars($e->getMessage());
            file_put_contents($error_file, date('Y-m-d H:i:s') . " - " . htmlspecialchars($error_message) . "\n", FILE_APPEND);
            $id = -1;
        }
    }
    $conn->close();
    return $id;
}
?>