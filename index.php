<?php
// index.php
session_start();
include 'conexion.php'; // Asume que tienes un archivo de conexión a la base de datos
// Consultar usuarios registrados
$sql = "SELECT id, nombre FROM usuarios";
$result = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Usuarios Registrados</title>
    <style>
        .user-card {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .user-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Usuarios Registrados</h1>
        
        <div class="row justify-content-center">
            <?php while($usuario = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="user-card text-center">
                        <img src="img/user.png" class="user-image">
                        
                        <h3><?php echo htmlspecialchars($usuario['nombre']); ?></h3>
                        
                        <a href="login.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary">
    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
</a
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="../proyecto_agenda/registrar.php" class="btn btn-success">
                <i class="bi bi-person-plus me-2"></i>Registrar Nuevo Usuario
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>