<?php
require_once '../auth.php';
require_once('../conexion.php');
require_once '../clases/Eventos.php';

// Asegúrate de iniciar la sesión

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Obtener el ID de la tarea
    $id = intval($_GET['id']);

    // Consultar la tarea desde la base de datos
    $query = $conexion->prepare("SELECT * FROM tareas WHERE id = ?");
    if (!$query) {
        die('Error en la consulta SQL: ' . $conexion->error);
    }
    $query->bind_param('i', $id);
    $query->execute();
    $resultado = $query->get_result();
    $tarea = $resultado->fetch_assoc();

    if (!$tarea) {
        die('Tarea no encontrada');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar datos del formulario
    $id = intval($_POST['id']);
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $estado = htmlspecialchars(trim($_POST['estado']));
    $fecha_limite = htmlspecialchars(trim($_POST['fecha_limite']));
    $prioridad = htmlspecialchars(trim($_POST['prioridad']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));

    // Actualizar los datos en la base de datos
    $query = $conexion->prepare("UPDATE tareas SET titulo = ?, estado = ?, fecha_limite = ?, prioridad = ?, descripcion = ? WHERE id = ?");
    if (!$query) {
        die('Error en la consulta SQL: ' . $conexion->error);
    }
    $query->bind_param('sssssi', $titulo, $estado, $fecha_limite, $prioridad, $descripcion, $id);
    $resultado = $query->execute();

    if ($resultado) {
        header("Location: index.php?mensaje=actualizado");
        exit;
    } else {
        $error = "Error al actualizar la tarea";
    }
} else {
    die('Solicitud no válida');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <form method="post" class="mx-auto" style="max-width: 600px;">
            <h2 class="text-center">Editar Tarea</h2>
            <hr>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
            <div class="mb-3">
                <input type="text" name="titulo" class="form-control" placeholder="Título" value="<?php echo $tarea['titulo']; ?>" required>
            </div>
            <div class="mb-3">
                <select id="estado" name="estado" class="form-select" required>
                    <option value="" <?php echo $tarea['estado'] === '' ? 'selected' : ''; ?>>Selecciona Estado</option>
                    <option value="Listo" <?php echo $tarea['estado'] === 'Listo' ? 'selected' : ''; ?>>Listo</option>
                    <option value="En curso" <?php echo $tarea['estado'] === 'En curso' ? 'selected' : ''; ?>>En curso</option>
                    <option value="Detenido" <?php echo $tarea['estado'] === 'Detenido' ? 'selected' : ''; ?>>Detenido</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="date" name="fecha_limite" class="form-control" value="<?php echo $tarea['fecha_limite']; ?>" required>
            </div>
            <div class="mb-3">
                <select id="prioridad" name="prioridad" class="form-select" required>
                    <option value="Alta" <?php echo isset($tarea['prioridad']) && $tarea['prioridad'] === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                    <option value="Media" <?php echo isset($tarea['prioridad']) && $tarea['prioridad'] === 'Media' ? 'selected' : ''; ?>>Media</option>
                    <option value="Baja" <?php echo isset($tarea['prioridad']) && $tarea['prioridad'] === 'Baja' ? 'selected' : ''; ?>>Baja</option>
                </select>
            </div>
            <div class="mb-3">
                <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Descripcion..."><?php echo $tarea['descripcion']; ?></textarea>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-outline-primary">Guardar Cambios</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
