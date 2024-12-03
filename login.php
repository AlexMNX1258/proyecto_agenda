<?php
session_start();
require_once('conexion.php');
require_once 'clases/Login.php';

$error_message = null;
$pre_filled_email = '';

// If an ID is passed, fetch the user's email
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "SELECT email FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $pre_filled_email = htmlspecialchars($row['email']);
    }
    
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $login = new Login($conexion);

    try {
        $login->iniciar_sesion($email, $password);
        $_SESSION["usuario"] = [
            'nombre' => $_SESSION["user"],
            'email' => $_SESSION["email"],
        ];
        $_SESSION["autenticado"] = true;
        header("Location: eventos/index.php");
        exit;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Verificar si hay sesión activa
if (isset($_SESSION["usuario"]) && isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] === true) {
    $nombre_usuario = $_SESSION["usuario"]['nombre'];
    
} else {
    $nombre_usuario = "Inicio de Sesión"; // Valor genérico
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Iniciar Sesión - <?php echo htmlspecialchars($nombre_usuario); ?></title>
    <style>
        body {
            background-color: #f4f6f9;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .user-icon {
            font-size: 6rem;
            color: #007bff;
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-6">
                <div class="login-container">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle user-icon"></i>
                        <h3><?php echo htmlspecialchars($nombre_usuario); ?></h3>
                    </div>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                        <label> Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo $pre_filled_email; ?>">
                            
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
            
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="index.php" class="text-muted">
                            <i class="bi bi-arrow-left me-2"></i>Volver a usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <?php if ($error_message): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error de Inicio de Sesión',
                text: '<?php echo htmlspecialchars($error_message); ?>'
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
