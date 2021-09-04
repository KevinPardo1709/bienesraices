<?php 


function conectarDB() : mysqli {
    $db = mysqli_connect('localhost','root', '', 'bienesraices');


    if(!$db) {
        echo "Error al conectar a la BD";
        exit;
    }

    return $db;
}