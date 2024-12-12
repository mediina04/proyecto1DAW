<?php
require_once 'usuario.php';

class UsuariosDAO {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener un usuario por su email
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Usuario(
                $row['id_usuario'],
                $row['nombre'],
                $row['email'],
                $row['contraseña'],
                $row['tipo_usuario']
            );
        }
        return null;
    }

    // Guardar un nuevo usuario
    public function guardar($usuario) {
        $query = "INSERT INTO usuarios (nombre, email, contraseña, tipo_usuario) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "ssss",
            $usuario->getNombre(),
            $usuario->getEmail(),
            $usuario->getContraseña(),
            $usuario->getTipoUsuario()
        );
        return $stmt->execute();
    }

    // Actualizar los datos de un usuario
    public function actualizar($usuario) {
        $query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "ssi",
            $usuario->getNombre(),
            $usuario->getEmail(),
            $usuario->getId()
        );
        return $stmt->execute();
    }

    // Obtener todos los usuarios
    public function obtenerTodos() {
        $query = "SELECT * FROM usuarios";
        $result = $this->conexion->query($query);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new Usuario(
                $row['id_usuario'],
                $row['nombre'],
                $row['email'],
                $row['contraseña'],
                $row['tipo_usuario']
            );
        }
        return $usuarios;
    }
}
