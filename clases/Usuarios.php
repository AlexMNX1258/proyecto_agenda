<?php
class Usuarios {
    private $conexion;
    private $nombre, $email, $password;

    public function __construct($conexion, $nombre, $email) {
        $this->conexion = $conexion;
        $this->nombre = $nombre;
        $this->email = $email;
    }
    public function obtenerUsuarios() {
        $sql = "SELECT * FROM usuarios";
        $resultado = $this->conexion->query($sql);
        
        $usuarios = [];
        while ($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }
        
        return $usuarios;
    }

}
?>