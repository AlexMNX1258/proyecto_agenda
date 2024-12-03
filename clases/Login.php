<?php

class Login {
    public $conexion;
    public $nombre;
    public $email;
    public $password;

    public function __construct($conexion ,$nombre= null, $email= null, $password = null) {
        $this->conexion = $conexion;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
    }
    // Método para registrar un usuario
    public function registrarUsuario($nombre, $email, $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($this->conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt); 
            return "Usuario registrado exitosamente.";
        } else {
            $error = "Error al registrar el usuario: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            return $error;
        }
    } else {
        return "Error al preparar la consulta: " . mysqli_error($this->conexion);
    }
}
    // Método para iniciar sesión
    public function iniciar_sesion($email, $password) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = ?"; 

            $stmt = mysqli_prepare($this->conexion, $sql); 
            
            mysqli_stmt_bind_param($stmt, "s", $email); 
            
            mysqli_stmt_execute($stmt); 
            $resultado = mysqli_stmt_get_result($stmt); 
            
            if (mysqli_num_rows($resultado) > 0) { 
                $usuario = mysqli_fetch_assoc($resultado); 
                
                if (password_verify($password, $usuario["password"])) { 
                    
                    $_SESSION["user"] =  $usuario["nombre"];
                    $_SESSION["email"] =  $usuario["email"];
                    $_SESSION["autenticado"] = true; 
                    header("Location: eventos/index.php"); 
                    exit;
                } else {
                    header("Location: index.php?mensaje=Contraseña incorrecta&resultado=error");
                    exit;
                }
            } else {
                header("Location: index.php?mensaje=El usuario no existe&resultado=error");
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Error al iniciar sesion: ". $e->getMessage());
        } finally {
            mysqli_stmt_close($stmt);
        }
    }
}
?>