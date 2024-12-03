<?php

// Configuración de la base de datos
$host = "localhost";  // Nombre del host
$username = "root";   // Usuario de la base de datos
$password = "";       // Contraseña de la base de datos
$database = "bd_agenda2";  // Nombre de la base de datos

// Crear conexión
$conexion = mysqli_connect($host, $username, $password, $database);

// Verificar la conexión
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

?>