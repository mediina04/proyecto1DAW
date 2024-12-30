<?php
require_once 'model/Usuario.php';
require_once 'model/UsuariosDAO.php';
require_once 'model/PedidosDAO.php';

class UsuarioController {

    public function __construct() {
        session_start();
    }

    private function verificarSesion() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    public function login() {
        if (isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=usuario&action=menu_usuario");
            exit;
        }
        $view = "views/login.php";
        include_once 'views/Inicio.php';
   }
   

   public function registrar() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validar datos
        $usuario = htmlspecialchars(trim($_POST['usuario']));
        $nombre = htmlspecialchars(trim($_POST['name']));
        $apellido = htmlspecialchars(trim($_POST['lastname']));
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        $telefono = htmlspecialchars(trim($_POST['phone']));
        $direccion = htmlspecialchars(trim($_POST['address']));

        if (!$email || strlen($password) < 6) {
            header("Location: index.php?controller=usuario&action=sign_up&error=invalid_data");
            exit;
        }

        // Verificar si ya existe el usuario
        $usuarioExistente = UsuariosDAO::findByEmailOrUsername($email, $usuario);
        if ($usuarioExistente) {
            header("Location: index.php?controller=usuario&action=sign_up&error=user_exists");
            exit;
        }

        // Crear objeto usuario
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $usuario_obj = new Usuario($usuario, $nombre, $apellido, $passwordHash, $email, $telefono, $direccion);

        // Insertar en base de datos
        $result = UsuariosDAO::insert($usuario_obj);
        if ($result) {
            $_SESSION['usuario'] = $usuario_obj;
            header("Location: index.php?controller=usuario&action=menu_usuario");
            exit;
        } else {
            header("Location: index.php?controller=usuario&action=sign_up&error=register_failed");
            exit;
        }
    }
}


    public function menu_usuario() {
        $this->verificarSesion();
        $view = "views/menu_usuario.php";
        include_once 'views/Inicio.php';
    }

    public function pedidos_info() {
        $this->verificarSesion();
        $view = "views/pedidos_info.php";
        include_once 'views/Inicio.php';
    }

    public function cerrar_sesion() {
        session_unset(); // Limpia todas las variables de sesión
        session_destroy(); // Destruye la sesión
        header("Location: index.php");
        exit;
    }
   

    public function pedir_pedido_anterior() {
        $this->verificarSesion();
        $usuario = $_SESSION['usuario'];
        $id_usuario = $usuario->id;

        try {
            $pedidosDAO = new PedidosDAO(DataBase::connect());
            $pedido = $pedidosDAO->getLatestPedidoByUsuarioId($id_usuario);

            if ($pedido) {
                $_SESSION['cart'] = $pedidosDAO->getProductosByPedidoId($pedido['id_pedido']);
                header("Location: index.php?controller=producto&action=compra");
                exit;
            } else {
                echo "No se encontró ningún pedido anterior.";
            }
        } catch (Exception $e) {
            echo "Error al recuperar el pedido: " . $e->getMessage();
        }
    }

    public function panel_admin() {
        $this->verificarSesion();
        if ($_SESSION['usuario']->getTipoUsuario() === 'admin') {
            include_once 'api/panel_admin.html';
        } else {
            header("Location: index.php?controller=usuario&action=menu_usuario");
            exit;
        }
    }
   
}
?>
