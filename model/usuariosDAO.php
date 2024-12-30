<?php
include_once __DIR__ . '/../config/data_base.php';
include_once __DIR__ . '/../model/Usuario.php';

class UsuariosDAO {
    // Esta funcion permite insertar un usuario en la base de datos
    public static function insert(Usuario $usuario) {
        $con = DataBase::connect();
        $query = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono, direccion) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $con->prepare($query)) {
            // Asociamos los parámetros
            $stmt->bind_param("sssssss", 
                $usuario->usuario, 
                $usuario->nombre, 
                $usuario->apellido, 
                $usuario->email, 
                $usuario->contrasena, 
                $usuario->telefono, 
                $usuario->direccion
            );

            // Ejecutamos la consulta
            if ($stmt->execute()) {
                $stmt->close();
                $con->close();
                return true;  // Si la inserción fue exitosa
            } else {
                // Mostrar el error si no se ejecutó la consulta
                echo "Error en la ejecución del INSERT: " . $stmt->error;
                $stmt->close();
                $con->close();
                return false;
            }
        } else {
            // Mostrar el error si no se pudo preparar la consulta
            echo "Error en la preparación de la consulta: " . $con->error;
            $con->close();
            return false;
        }
    }

    // Esta funcion permite validar el login de un usuario
    public static function validateLogin($usuario, $password) {
        $con = DataBase::connect();
        $sql = "SELECT id_usuario FROM usuarios WHERE usuario='$usuario' AND contrasena='$password'";
        $result = $con->query($sql);
        $con->close();
        return $result->num_rows > 0;
    }

    public static function getAll() {
        $con = DataBase::connect();
        $sql = "SELECT * FROM usuarios";
        $result = $con->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        $con->close();
        return $usuarios;
    }
}
?>