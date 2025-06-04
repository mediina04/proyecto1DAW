<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../model/usuario.php';
include_once __DIR__ . '/../model/usuariosDAO.php';

class UsuarioController {

    // LOGIN
    public function login() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = UsuariosDAO::getUserByUsername($usuario);

        $loginCorrecto = false;

        if ($user) {
            // Si la contraseña en BD es un hash, usar password_verify
            if (strlen($user['contrasena']) > 30 && strpos($user['contrasena'], '$2y$') === 0) {
                $loginCorrecto = password_verify($password, $user['contrasena']);
            } else {
                $loginCorrecto = ($password === $user['contrasena']);
            }
        }

        if ($loginCorrecto) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['login'] = true;
            $_SESSION['usuario'] = new Usuario(
                $user['usuario'],
                $user['nombre'],
                $user['apellido'],
                '', // No guardar la contraseña en sesión
                $user['email'],
                $user['telefono'],
                $user['direccion'],
                $user['id_usuario'],
                $user['rol'] ?? null
            );

            header("Location: index.php?controller=usuario&action=perfil");
            exit;
        } else {
            // Redirige con error y conserva el nombre de usuario
            $error = urlencode("Nombre de usuario o contraseña incorrectos.");
            header("Location: /proyecto1DAW/views/Login.php?error=$error&usuario=" . urlencode($usuario));
            exit;
        }
    }
}


    // REGISTRO
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'] ?? null;

            if ($_POST['password'] !== $_POST['confirm_password']) {
                echo "Las contraseñas no coinciden.";
                exit;
            }

            $user = new Usuario($usuario, $nombre, $apellido, $password, $email, $telefono, $direccion);
            if (UsuariosDAO::insert($user)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $userDB = UsuariosDAO::getUserByUsername($usuario);
                $_SESSION['login'] = true;
                $_SESSION['usuario'] = new Usuario(
                    $userDB['usuario'],
                    $userDB['nombre'],
                    $userDB['apellido'],
                    '', // No guardar la contraseña en sesión
                    $userDB['email'],
                    $userDB['telefono'],
                    $userDB['direccion'],
                    $userDB['id_usuario'],
                    $userDB['rol'] ?? null
                );
                echo "<div id='registro-exitoso' style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: green; color: white; padding: 20px; font-size: 24px; text-align: center; border-radius: 10px;'>Registro exitoso</div>";
                echo "<script>
                        setTimeout(function() { 
                            document.getElementById('registro-exitoso').style.display = 'none'; 
                            window.location.href = 'index.php?controller=usuario&action=perfil';
                        }, 1000);
                      </script>";
                exit;
            } else {
                echo "Error: No se pudo completar el registro.";
            }
        }
        $view = "views/Sign-Up.php";
        include_once 'views/Inicio.php';
    }

    // PERFIL DE USUARIO
    public function perfil() {
        session_start();
        include_once __DIR__ . '/../model/usuario.php';

        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        // Conversión: si el usuario en sesión no es instancia de Usuario, conviértelo
        if (isset($_SESSION['usuario']) && !($_SESSION['usuario'] instanceof Usuario)) {
            $u = $_SESSION['usuario'];
            $_SESSION['usuario'] = new Usuario(
                $u->usuario ?? '',
                $u->nombre ?? '',
                $u->apellido ?? '',
                '', // No guardar la contraseña
                $u->email ?? '',
                $u->telefono ?? '',
                $u->direccion ?? '',
                $u->id_usuario ?? null,
                $u->rol ?? null
            );
        }

        // ...cargar datos extra si hace falta...

        require __DIR__ . '/../views/Info-Usuario.php';
    }

    // CERRAR SESIÓN
    public function cerrar_sesion() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?controller=producto&action=index");
        exit;
    }

    // ACTUALIZAR DATOS DEL USUARIO
    public function actualizar_datos() {
        session_start(); // <-- Añade esto al principio
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'] ?? null;

            if (UsuariosDAO::actualizar($id, $nombre, $apellido, $email, $telefono, $direccion)) {
                // Actualiza los datos en la sesión
                $_SESSION['usuario']->setNombre($nombre);
                $_SESSION['usuario']->setApellido($apellido);
                $_SESSION['usuario']->setEmail($email);
                $_SESSION['usuario']->setTelefono($telefono);
                $_SESSION['usuario']->setDireccion($direccion);
                header("Location: index.php?controller=usuario&action=perfil");
                exit;
            } else {
                echo "Error al actualizar los datos.";
            }
        }
    }

    // ELIMINAR USUARIO
    public function eliminar_usuario() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $id_usuario = $_SESSION['usuario']->getIdUsuario();

            // Elimina pedidos y usuario
            include_once __DIR__ . '/../config/data_base.php';
            $con = DataBase::connect();

            // Eliminar pedidos del usuario
            $query = "DELETE FROM pedidos WHERE id_usuario = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $id_usuario);
            $stmt->execute();
            $stmt->close();

            // Eliminar usuario
            $query = "DELETE FROM usuarios WHERE id_usuario = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $id_usuario);
            $stmt->execute();
            $stmt->close();

            session_unset();
            session_destroy();
            $con->close();
            header("Location: index.php");
            exit;
        } else {
            die("La sesión actual no tiene un usuario ID.");
        }
    }

    // INDEX
    public function index() {
        // Puedes redirigir a login, perfil o mostrar un mensaje
        header("Location: index.php?controller=usuario&action=login");
        exit;
    }
}
?>
