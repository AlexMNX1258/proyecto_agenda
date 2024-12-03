<?php

require_once '../conexion.php';

class Eventos {
    public $titulo, $usuario, $estado, $fecha_creacion, $fecha_limite, $prioridad, $descripcion, $nombre_usuario;
    public $conexion;

    public function __construct($conexion, $titulo = null, $usuario = null, $estado = null, $fecha_creacion = null, $fecha_limite = null, $prioridad = null, $descripcion = null, $nombre_usuario = null) {
        $this->conexion = $conexion;
        $this->titulo = $titulo;
        $this->usuario = $usuario;
        $this->estado = $estado;
        $this->fecha_creacion = $fecha_creacion;
        $this->fecha_limite = $fecha_limite;
        $this->prioridad = $prioridad;
        $this->descripcion = $descripcion;
        $this->nombre_usuario = $nombre_usuario; // Nueva propiedad
    }

    public function registrarTareas() {
        $sql = "INSERT INTO `tareas`( `titulo`, `descripcion`, `fecha_creacion`, `fecha_limite`, `usuario`, `nombre_usuario`, `estado`, `prioridad`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = mysqli_prepare($this->conexion, $sql);
    
        if (!$stmt) {
            return "Error en la preparación de la consulta: " . mysqli_error($this->conexion);
        }
    
        // Asignar valores a las variables desde las propiedades de la clase
        mysqli_stmt_bind_param($stmt, 'ssssssss', 
            $this->titulo, 
            $this->descripcion, 
            $this->fecha_creacion, 
            $this->fecha_limite, 
            $this->usuario, 
            $this->nombre_usuario, 
            $this->estado, 
            $this->prioridad // Agregar prioridad
        );
    
        // Ejecutar consulta
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Inserción exitosa
        } else {
            $error = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            return "Error al ejecutar la consulta: " . $error;
        }
    }
    

    public static function mostrarTareas($conexion) {
        $sql = "SELECT * FROM tareas";
        $resultado = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "ID: " . $fila["id"] . " - Titulo: " . $fila["titulo"] . " - Usuario: " . $fila["usuario"] . " - Estado: " . $fila["estado"] . 
                     " - Fecha de Creacion: " . $fila["fecha_creacion"] . " - Fecha de Limite: " . $fila["fecha_limite"] . 
                     " - Prioridad: " . $fila["prioridad"] . " - Descripcion: " . $fila["descripcion"] . "<br>";
            }
        } else {
            echo "0 resultados";
        }
    }

    public function actualizarTareas($id) {
        $sql = "UPDATE tareas 
                SET titulo = ?, usuario = ?, estado = ?, fecha_creacion = ?, fecha_limite = ?, prioridad = ?, descripcion = ? 
                WHERE id = ?";
    
        $stmt = mysqli_prepare($this->conexion, $sql);
    
        if (!$stmt) {
            return "Error en la preparación de la consulta: " . mysqli_error($this->conexion);
        }
    
        mysqli_stmt_bind_param($stmt, 'sssssssi', 
            $this->titulo, 
            $this->usuario, 
            $this->estado, 
            $this->fecha_creacion, 
            $this->fecha_limite, 
            $this->prioridad, 
            $this->descripcion, 
            $id
        );
    
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            $error = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            return "Error al actualizar la tarea: " . $error;
        }
    }
    
    public function eliminarTareas($id) {
        $sql = "DELETE FROM tareas WHERE id = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        if (!$stmt) {
            return "Error en la preparación de la consulta: " . mysqli_error($this->conexion);
        }

        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            $error = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            return "Error al eliminar la tarea: " . $error;
        }
    }
}
?>
