<?php
require_once('conexion.php');
require_once('clases/Login.php');

$usuario = new Login($conexion);
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el email ya existe en la base de datos
    if ($usuario->verificarEmailExistente($email)) {
        // Email ya registrado
        $mensaje = '<div class="alert alert-warning text-center" role="alert">
                        El email ya está registrado. Intenta con otro email.
                    </div>';
    } else {
        // Registrar al usuario si el email no existe
        $respuesta = $usuario->registrarUsuario($nombre, $email, $password);

        if ($respuesta) {
            $mensaje = '<div class="alert alert-success text-center" role="alert">
                            Usuario registrado exitosamente.
                        </div>';
            header("Location: index.php"); 
            exit;
        } else {
            $mensaje = '<div class="alert alert-danger text-center" role="alert">
                            Hubo un error al registrar al usuario. Inténtalo de nuevo.
                        </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <title>Registrar para Iniciar Sesión</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 position-absolute top-50 start-50 translate-middle">
        <div class="card">
          <div class="card-body">
            <!-- Mostrar mensaje de estado -->
            <?php echo $mensaje; ?>
            <!-- Icono centrado -->
            <div class="text-center mb-3">
              <i class="bi bi-person-circle" style="font-size: 7rem; color: #007bff;" ></i>
            </div>
            <h5 class="card-title text-center">Registrar para Iniciar Sesión</h5>
            <form method="POST">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre"required>
                <p class="mensaje">Por favor, ingresa tu nombre completo.</p>
                <style>
                  .mensaje {
                    color: #555; /* Color del texto */
                    font-size: 13px; /* Tamaño del texto */
                    margin-top: 2px; /* Espacio superior */
                  }
                </style>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <p class="mensaje">Por favor, ingresa un correo electrónico válido.</p>
                <style>
                  .mensaje {
                    color: #555; /* Color del texto */
                    font-size: 13px; /* Tamaño del texto */
                    margin-top: 2px; /* Espacio superior */
                  }
                </style>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <p class="mensaje">La contraseña debe tener al menos 12 caracteres entre caracteres especiales($,%,&,/) y números.</p>
                <style>
                  .mensaje {
                    color: #555; /* Color del texto */
                    font-size: 13px; /* Tamaño del texto */
                    margin-top: 2px; /* Espacio superior */
                  }
                </style>

              </div>
              <center><div>
                <button type="submit" class="btn btn-outline-primary">Registrar</button>
                <a href="login.php" >Iniciar Sesión</a>
              </div></center>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9/fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz" crossorigin="anonymous"></script>
</body>
</html>