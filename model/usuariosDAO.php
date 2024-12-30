<?php
require_once __DIR__ . '/../config/data_base.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuariosDAO {
    public static function insert(Usuario $usuario) {
        $con = DataBase::connect();
        $query = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono, direccion) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $con->prepare($query)) {
            // Encriptar la contraseña
            $hashedPassword = password_hash($usuario->getContrasena(), PASSWORD_BCRYPT);

            // Asociamos los parámetros
            $stmt->bind_param("sssssss", 
                $usuario->getUsuario(), 
                $usuario->getNombre(), 
                $usuario->getApellido(), 
                $usuario->getEmail(), 
                $hashedPassword, 
                $usuario->getTelefono(), 
                $usuario->getDireccion()
            );

            // Ejecutamos la consulta
            if ($stmt->execute()) {
                $stmt->close();
                $con->close();
                return true;
            } else {
                echo "Error en la ejecución del INSERT: " . $stmt->error;
                $stmt->close();
                $con->close();
                return false;
            }
        } else {
            echo "Error en la preparación de la consulta: " . $con->error;
            $con->close();
            return false;
        }
    }

    public static function validateLogin($usuario, $password) {
        $con = DataBase::connect();
        $sql = "SELECT id_usuario, contrasena FROM usuarios WHERE usuario=?";
        
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("s", $usuario); // Evitar inyección SQL
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id_usuario, $hashedPassword);
                $stmt->fetch();
                
                // Verificar la contraseña
                if (password_verify($password, $hashedPassword)) {
                    $stmt->close();
                    $con->close();
                    return $id_usuario;
                }
            }
            
            $stmt->close();
            $con->close();
            return false;
        } else {
            echo "Error en la preparación de la consulta: " . $con->error;
            $con->close();
            return false;
        }
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

    public static function findByEmailOrUsername($email, $usuario) {
        $con = DataBase::connect();
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ? OR usuario = ?";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("ss", $email, $usuario);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            $con->close();
            return $exists;
        } else {
            echo "Error al preparar la consulta: " . $con->error;
            $con->close();
            return false;
        }
    }
}

?>
