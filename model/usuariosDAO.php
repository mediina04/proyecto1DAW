<?php
require_once 'models/usuario.php';
require_once 'config/dataBase.php';

class UsuariosDAO {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Guardar un nuevo usuario
    public function guardar($usuario) {
        $query = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono, direccion) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "sssssss",
            $usuario->getUsuario(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getEmail(),
            $usuario->getContraseña(),
            $usuario->getTelefono(),
            $usuario->getDireccion()
        );
        return $stmt->execute();
    }

    // Validar login de un usuario
    public function validateLogin($usuario, $password) {
        $query = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['contrasena'])) {
                return new Usuario(
                    $row['id_usuario'],
                    $row['usuario'],
                    $row['nombre'],
                    $row['apellido'],
                    $row['contrasena'],
                    $row['email'],
                    $row['telefono'],
                    $row['direccion']
                );
            }
        }
        return null;
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
                $row['usuario'],
                $row['nombre'],
                $row['apellido'],
                $row['contrasena'],
                $row['email'],
                $row['telefono'],
                $row['direccion']
            );
        }
        return null;
    }

    // Actualizar los datos de un usuario
    public function actualizar($usuario) {
        $query = "UPDATE usuarios SET usuario = ?, nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "ssssssi",
            $usuario->getUsuario(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getEmail(),
            $usuario->getTelefono(),
            $usuario->getDireccion(),
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
                $row['usuario'],
                $row['nombre'],
                $row['apellido'],
                $row['contrasena'],
                $row['email'],
                $row['telefono'],
                $row['direccion']
            );
        }
        return $usuarios;
    }
}
