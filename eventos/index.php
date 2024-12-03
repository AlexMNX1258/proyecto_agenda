<?php
// Iniciar la sesión
require_once '../auth.php';
require_once '../conexion.php';

// Validar que la sesión contiene el email del usuario
if (!isset($_SESSION['email'])) {
    die("Error: El usuario no está autenticado.");
}

// Prioritize getting the username from session
$nombre_usuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 
                  (isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 
                  (isset($_SESSION['user']) ? $_SESSION['user'] : 'Usuario Invitado'));

// Preparar consulta
$sql = "SELECT * FROM tareas WHERE usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    die("Error en la consulta preparada: " . mysqli_error($conexion));
}

mysqli_stmt_bind_param($stmt, "s", $_SESSION['email']);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($resultado === false) {
    die("Error al obtener el resultado: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .table-custom {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-light sidebar py-4 d-flex flex-column">
            <div class="user-section">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-person-circle me-2" style="font-size: 3rem; color: #007bff;"></i>
                    <div>
                        <h4 class="mb-0">Bienvenido,</h4>
                        <p class="mb-0"><?php echo htmlspecialchars($nombre_usuario); ?></p>
                    </div>
                </div>
            </div>
            <div class="mt-auto">
                <a href="../logout.php" class="btn btn-outline-danger w-100">Cerrar sesión</a>
            </div>
        </div>
        <div class="col-md-10 main-content">
            <div class="container-fluid mt-4">
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h1 class="title">Gestión de Tareas</h1>
                    </div>
                    <div class="col text-end">
                        <a href="guardar.php" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i>Agregar Nueva Tarea
                        </a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-custom">
                        <thead class="table-light">
                            <tr>
                                <th>Título</th>
                                <th>Estado</th>
                                <th>Fecha De Creacion</th>
                                <th>Fecha limite</th>
                                <th>Prioridad</th>
                                <th>Descripcion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
<?php
if (mysqli_num_rows($resultado) > 0):
    while ($fila = mysqli_fetch_assoc($resultado)): ?>
        <tr>
            <td><?php echo isset($fila['titulo']) ? $fila['titulo'] : 'No especificado'; ?></td>
            <td><?php echo isset($fila['estado']) ? $fila['estado'] : 'No especificado'; ?></td>
            <td><?php echo isset($fila['fecha_creacion']) ? $fila['fecha_creacion'] : 'No especificado'; ?></td>
            <td><?php echo isset($fila['fecha_limite']) ? $fila['fecha_limite'] : 'No especificado'; ?></td>
            <td><?php echo isset($fila['prioridad']) ? $fila['prioridad'] : 'No especificado'; ?></td>
            <td><?php echo isset($fila['descripcion']) ? $fila['descripcion'] : 'No especificado'; ?></td>
            <td>
                <a href="editar.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="eliminar.php?id=<?php echo $fila['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; 
else: ?>
    <tr>
        <td colspan="7" class="text-center">No hay datos disponibles</td>
    </tr>
<?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
$stmt->close();
$conexion->close(); 
?>