<?php
require_once('../conexion.php');
require_once('../clases/Eventos.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Crear instancia y eliminar estudiante
    $evento = new Eventos($conexion);
    $evento->eliminarTareas($id);

    header("Location: index.php"); // Redirige al índice después de eliminar
}
?>