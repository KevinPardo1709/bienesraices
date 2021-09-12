<?php 

// Importar la conexión
require 'includes/config/database.php';
$db = conectarDB();

// Crear un email y pasword
$email = "k.pardo22@hotmail.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Query para crear el usuario
$query = "INSERT INTO usuarios (email, password) VALUES ( '${email}', '${passwordHash}');";



// Agregarlo a la BD
mysqli_query($db, $query);

