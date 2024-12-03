<?php
require_once '../auth.php';
require_once('../conexion.php');
require_once '../clases/Eventos.php';

// Asegúrate de iniciar la sesión


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibir y sanitizar datos del formulario
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $usuario = $_SESSION['email'];
    $estado = htmlspecialchars(trim($_POST['estado']));
    $fecha_creacion = date("Y-m-d H:i:s");
    $fecha_limite = htmlspecialchars(trim($_POST['fecha_limite']));
    $prioridad = htmlspecialchars(trim($_POST['prioridad']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    
    // Verificar si el índice 'user' está definido en la sesión
    $nombre_usuario = isset($_SESSION["user"]) ? $_SESSION["user"] : "Usuario desconocido";

    // Crear una instancia de la clase Eventos
    $evento = new Eventos($conexion, $titulo, $usuario, $estado, $fecha_creacion, $fecha_limite, $prioridad, $descripcion, $nombre_usuario);
    $resultado = $evento->registrarTareas();

    if ($resultado === true) {
        header("Location: index.php?mensaje=success");
        exit;
    } else {
        $error = $resultado; // Captura el mensaje de error
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <form method="post" class="mx-auto" style="max-width: 600px;">
            <h2 class="text-center">Guardar Tarea</h2>
            <hr>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <input type="text" name="titulo" class="form-control" placeholder="Título" required>
            </div>
            <div class="mb-3">
                <select id="estado" name="estado" class="form-select" required>
                    <option value="">Selecciona Estado</option>
                    <option value="Listo">Listo</option>
                    <option value="En curso">En curso</option>
                    <option value="Detenido">Detenido</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="date" name="fecha_limite" class="form-control" required>
            </div>
            <div class="mb-3">
                <select id="prioridad" name="prioridad" class="form-select" required>
                    <!-- <option value="">Selecciona Prioridad</option> -->
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>
            <div class="mb-3">
                <textarea id="descripcion" name="descripcion" class="form-control" placeholder="descripcion..."></textarea>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-outline-primary">Guardar</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
