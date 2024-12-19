<?php
require_once 'model/usuario.php';
require_once 'model/usuariosDAO.php';

class UsuarioController {

    // Mostrar la vista de login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $usuarioDAO = new UsuariosDAO(DataBase::connect());
            $usuario = $usuarioDAO->buscarPorEmail($email);

            if ($usuario && password_verify($password, $usuario->getContraseña())) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                header("Location: index.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                echo "Credenciales incorrectas.";
            }
        } else {
            $view = "views/login.php";
            include_once 'views/main.php';
        }
    }

    // Registrar un nuevo usuario
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $tipo_usuario = 'cliente'; // Asignar tipo de usuario por defecto

            $usuario = new Usuario(null, $nombre, $email, $password, $tipo_usuario, $apellido);
            $usuarioDAO = new UsuariosDAO(DataBase::connect());

            if ($usuarioDAO->guardar($usuario)) {
                header("Location: index.php?controller=usuario&action=login");
                exit;
            } else {
                echo "Error al registrar el usuario.";
            }
        } else {
            $view = "views/registro.php";
            include_once 'views/main.php';
        }
    }

    // Menú del usuario
    public function menu_usuario() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $view = "views/menu_usuario.php";
            include_once 'views/main.php';
        } else {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    // Mostrar información de pedidos
    public function pedidos_info() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $view = "views/pedidos_info.php";
            include_once 'views/main.php';
        } else {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    // Cerrar sesión
    public function cerrar_sesion() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // Actualizar datos del usuario
    public function actualizar_datos() {
        session_start();
        if (isset($_SESSION['usuario']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_SESSION['usuario'];

            $nombre = $_POST['nombre'] ?? $usuario->getNombre();
            $apellido = $_POST['apellido'] ?? $usuario->getApellido();
            $email = $_POST['email'] ?? $usuario->getEmail();
            $telefono = $_POST['telefono'] ?? $usuario->getTelefono();
            $direccion = $_POST['direccion'] ?? $usuario->getDireccion();

            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            $usuario->setEmail($email);
            $usuario->setTelefono($telefono);
            $usuario->setDireccion($direccion);

            $usuarioDAO = new UsuariosDAO(DataBase::connect());
            if ($usuarioDAO->actualizar($usuario)) {
                $_SESSION['usuario'] = $usuario;
                header("Location: index.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                echo "Error al actualizar los datos.";
            }
        } else {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    // Eliminar usuario
    public function eliminar_usuario() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $usuario = $_SESSION['usuario'];
            $id_usuario = $usuario->getId();

            $usuarioDAO = new UsuariosDAO(DataBase::connect());

            if ($usuarioDAO->eliminar($id_usuario)) {
                session_unset();
                session_destroy();
                header("Location: index.php");
                exit;
            } else {
                echo "Error al eliminar el usuario.";
            }
        } else {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    // Recuperar el último pedido del usuario
    public function pedir_pedido_anterior() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $usuario = $_SESSION['usuario'];
            $id_usuario = $usuario->getId();

            $pedido = pedidosDAO::getLatestPedidoByUsuarioId($id_usuario);
            if ($pedido) {
                $_SESSION['cart'] = pedidosDAO::getProductosByPedidoId($pedido['id_pedido']);
                header("Location: index.php?controller=producto&action=compra");
                exit;
            } else {
                echo "No se encontró ningún pedido anterior.";
            }
        } else {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    // Redirigir al panel de administrador
    public function panel_admin() {
        session_start();
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']->getTipoUsuario() === 'admin') {
            include_once 'api/panel_admin.html';
        } else {
            echo "Acceso denegado.";
        }
    }
}
