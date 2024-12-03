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
    public function registrarUsuario($nombre, $email, $password) {
        // Verificar primero si el email ya existe
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = mysqli_prepare($this->conexion, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        
        // Si ya existe un usuario con este email, devolver un error
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            mysqli_stmt_close($stmt_check);
            return "alert('El correo electrónico ya está registrado.');";
        }
        mysqli_stmt_close($stmt_check);
        
        // Si no existe, proceder con el registro
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($this->conexion, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return "alert('Usuario registrado exitosamente.');";
            } else {
                $error = mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
                return "alert('Error al registrar: " . $error . "');";
            }
        } else {
            return "alert('Error al preparar la consulta: " . mysqli_error($this->conexion) . "');";
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
    public function verificarEmailExistente($email) {
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0; // Retorna true si hay coincidencias
    }

}
?>