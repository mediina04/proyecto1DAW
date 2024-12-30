<?php
require_once 'model/Usuario.php';
require_once 'model/UsuariosDAO.php';
require_once 'model/PedidosDAO.php';

class UsuarioController {

    // Constructor para iniciar sesión
    public function __construct() {
        session_start();
    }

    // Función para comprobar si el usuario está logueado
    private function verificarSesion() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }
    }

    // Mostrar la vista de login
    public function login() {
        $view = "views/login.php";
        include_once 'views/Inicio.php';  // Cambio de main.php a Inicio.php
    }

    // Registrar un nuevo usuario
    // Registrar un nuevo usuario
    public function registrar() {
        // Asegurarse de que la sesión esté iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recoger los datos del formulario
            $usuario = $_POST['usuario'];  // Aquí se recoge 'usuario'
            $nombre = $_POST['name'];  // Aquí se recoge 'name'
            $apellido = $_POST['lastname'];  // Aquí se recoge 'lastname'
            $email = $_POST['email'];  // Aquí se recoge 'email'
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Aquí se recoge 'password' y se hashea
            $telefono = $_POST['phone'];  // Aquí se recoge 'phone'
            $direccion = $_POST['address'];  // Aquí se recoge 'address'
    
            // Depuración para comprobar los datos que llegan
            echo "<pre>";
            var_dump($_POST);  // Muestra todos los datos recibidos
            echo "</pre>";
    
            // Crear el objeto Usuario con los datos recogidos
            $usuario_obj = new Usuario($usuario, $nombre, $apellido, $password, $email, $telefono, $direccion);
    
            // Ahora vamos a insertar el usuario en la base de datos
            include_once ("model/UsuariosDAO.php");
    
            // Llamamos al método insert de la clase UsuariosDAO
            $result = UsuariosDAO::insert($usuario_obj);
    
            // Comprobar si el registro fue exitoso
            if ($result) {
                // Si la inserción fue exitosa, establecemos las variables de sesión
                $_SESSION['loggedin'] = true;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $_SESSION['email'] = $email;
                $_SESSION['telefono'] = $telefono;
                $_SESSION['direccion'] = $direccion;
    
                // Redirigimos al usuario a la página del menú
                header("Location: index.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                echo "Error al registrar el usuario.";
            }
        }
    }
    
    

    
    // Menú del usuario
    public function menu_usuario() {
        $this->verificarSesion();
        $view = "views/menu_usuario.php";
        include_once 'views/Inicio.php';  // Cambio de main.php a Inicio.php
    }

    // Mostrar información de pedidos
    public function pedidos_info() {
        $this->verificarSesion();
        $view = "views/pedidos_info.php";
        include_once 'views/Inicio.php';  // Cambio de main.php a Inicio.php
    }

    // Cerrar sesión
    public function cerrar_sesion() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // Actualizar datos del usuario
    public function actualizar_datos() {
        $this->verificarSesion();
    }

    // Eliminar usuario
    public function eliminar_usuario() {
        $this->verificarSesion();
    }

    // Recuperar el último pedido del usuario
    public function pedir_pedido_anterior() {
        $this->verificarSesion();
        $usuario = $_SESSION['usuario'];
        $id_usuario = $usuario->getId();
    
        // Crear una instancia de PedidosDAO
        $pedidosDAO = new PedidosDAO(DataBase::connect());
    
        // Obtener el último pedido del usuario
        $pedido = $pedidosDAO->getLatestPedidoByUsuarioId($id_usuario);
        
        if ($pedido) {
            // Obtener los productos del pedido
            $_SESSION['cart'] = $pedidosDAO->getProductosByPedidoId($pedido['id_pedido']);
            header("Location: index.php?controller=producto&action=compra");
            exit;
        } else {
            echo "No se encontró ningún pedido anterior.";
        }
    }
    

    // Redirigir al panel de administrador
    public function panel_admin() {
        $this->verificarSesion();
        if ($_SESSION['usuario']->getTipoUsuario() === 'admin') {
            include_once 'api/panel_admin.html';
        } else {
            echo "Acceso denegado.";
        }
    }
}
?>
