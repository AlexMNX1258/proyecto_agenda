<?php
session_start();

// Verifica si el usuario no está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); // Redirige al login
    exit;
}
?>