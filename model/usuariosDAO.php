<?php
include_once __DIR__ . '/../model/usuario.php';
include_once __DIR__ . '/../config/data_base.php';

class UsuariosDAO {
    // Esta función permite insertar un usuario en la base de datos
    public static function insert(Usuario $usuario) {
        $con = DataBase::connect();
        $query = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono, direccion, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param(
            "ssssssss",
            $usuario->getUsuario(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getEmail(),
            $usuario->getContrasena(),
            $usuario->getTelefono(),
            $usuario->getDireccion(),
            $usuario->getRol()
        );
        $result = $stmt->execute();
        $stmt->close();
        $con->close();
        return $result; // Devuelve si la inserción fue exitosa
    }

    // Esta función valida el login de un usuario
    public static function validateLogin($usuario, $password) {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT id_usuario FROM usuarios WHERE usuario=? AND contrasena=?");
        $stmt->bind_param("ss", $usuario, $password); // Usar parámetros preparados para evitar SQL injection
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $con->close();
        return $result->num_rows > 0; // Retorna verdadero si hay un usuario que coincide
    }

    // Esta función obtiene todos los usuarios de la base de datos
    public static function getAll() {
        $con = DataBase::connect();
        $sql = "SELECT * FROM usuarios";
        $result = $con->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row; // Agrega cada usuario a la lista
        }
        $con->close();
        return $usuarios; // Retorna la lista de todos los usuarios
    }

    // Esta función obtiene un usuario por su nombre de usuario
    public static function getUserByUsername($usuario) {
        $con = DataBase::connect();
        $query = "SELECT id_usuario, usuario, nombre, apellido, email, telefono, direccion, contrasena, rol FROM usuarios WHERE usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $con->close();
        return $user; // Devuelve el usuario encontrado
    }

    // Esta función verifica si un usuario es administrador
    public static function isAdmin($id_usuario) {
        $con = DataBase::connect();
        $query = "SELECT rol FROM usuarios WHERE id_usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $stmt->bind_result($rol);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return $rol === 'admin'; // Retorna verdadero si es administrador
    }

    // Esta función permite actualizar los datos de un usuario
    public static function actualizar($id_usuario, $nombre, $apellido, $email, $telefono, $direccion) {
        $con = DataBase::connect();
        $query = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ? WHERE id_usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssssi", $nombre, $apellido, $email, $telefono, $direccion, $id);
        $result = $stmt->execute();
        $stmt->close();
        $con->close();
        return $result;
    }

    // Esta función permite eliminar un usuario por su ID
    public static function eliminar($id) {
        $con = DataBase::connect();
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        $con->close();
        return $result; // Devuelve si la eliminación fue exitosa
    }
}
?>
