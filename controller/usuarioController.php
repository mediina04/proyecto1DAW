<?php
include_once __DIR__ . '/../model/Usuario.php';
include_once __DIR__ . '/../model/UsuariosDAO.php';

class UsuarioController {
    // Funciones para controlar las vistas de los usuarios
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['username'];
            $password = $_POST['password'];
    
            // Obtener el usuario desde la base de datos
            $user = UsuariosDAO::getUserByUsername($usuario);
    
            if ($user && password_verify($password, $user['contrasena'])) {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['usuario'] = new Usuario(
                    $user['usuario'], 
                    $user['nombre'], 
                    $user['apellido'], 
                    $user['contrasena'], 
                    $user['email'], 
                    $user['telefono'], 
                    $user['direccion']
                );
    
                // Redirigir al menú del usuario
                header("Location: Info-Usuario.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                // Redirigir al login con un mensaje de error
                header("Location: Login.php?controller=usuario&action=login&error=1");
                exit;
            }
        } else {
            // Verificar si hay un error en la URL
            $error = isset($_GET['error']) ? "Nombre de usuario o contraseña incorrectos." : null;
    
            // Mostrar la vista de inicio de sesión
            include_once 'Login.php';
        }
    }
    

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $nombre = $_POST['name'];
            $apellido = $_POST['lastname'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña
            $telefono = $_POST['phone'];
            $direccion = $_POST['address'];

            $user = new Usuario($usuario, $nombre, $apellido, $password, $email, $telefono, $direccion);

            if (UsuariosDAO::insert($user)) {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['usuario'] = $user;

                // Redirigir a Inicio.php después del registro
                header("Location: views/Inicio.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                echo "<div>Error: No se ha completado el registro.</div>";
                include_once 'views/Sign-Up.php';
            }
        } else {
            include_once 'views/Sign-Up.php';
        }
    }

    public function cerrar_sesion() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: views/Login.php");
        exit;
    }
}
