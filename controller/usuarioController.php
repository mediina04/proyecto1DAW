<?php
require_once 'model/usuario.php';
require_once 'model/usuariosDAO.php';

class UsuarioController {

    // Registrar un nuevo usuario
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $tipo_usuario = 'cliente'; // Asignar tipo de usuario por defecto

            $usuario = new Usuario(null, $nombre, $email, $password, $tipo_usuario);
            $usuarioDAO = new UsuariosDAO(DataBase::connect());
            if ($usuarioDAO->guardar($usuario)) {
                echo "Usuario registrado con éxito.";
            } else {
                echo "Error al registrar el usuario.";
            }
        }
    }

    // Iniciar sesión de usuario
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $usuarioDAO = new UsuariosDAO(DataBase::connect());
            $usuario = $usuarioDAO->buscarPorEmail($email);

            if ($usuario && password_verify($password, $usuario->getContraseña())) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                echo "Inicio de sesión exitoso.";
            } else {
                echo "Credenciales incorrectas.";
            }
        }
    }

    // Actualizar los datos del usuario
    public function actualizar() {
        session_start();
        if (isset($_SESSION['usuario']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_SESSION['usuario'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];

            $usuario->setNombre($nombre);
            $usuario->setEmail($email);

            $usuarioDAO = new UsuariosDAO(DataBase::connect());
            if ($usuarioDAO->actualizar($usuario)) {
                echo "Datos actualizados con éxito.";
            } else {
                echo "Error al actualizar los datos.";
            }
        }
    }
}
