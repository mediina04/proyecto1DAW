<?php
require_once 'config/data_base.php';
require_once 'model/Producto.php';
require_once 'model/ProductosDAO.php';
require_once 'model/ReservasDAO.php';

class ProductoController {

    private $productosDAO;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $conexion = DataBase::connect();
        $this->productosDAO = new ProductosDAO($conexion);
    }

    // Mostrar la página de inicio con los productos destacados
    public function index() {
        $productos = $this->productosDAO->obtenerDestacados(8);


        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        require_once 'views/Inicio.php';
    }

    // Mostrar la carta completa
    public function carta() {
        $platos = $this->productosDAO->obtenerTodos();

        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        require_once 'views/Nuestra-Carta.php';
    }

    // Añadir un producto al carrito
    public function agregarAlCarrito() {
        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

        if (!$id_plato || $id_plato <= 0) {
            $_SESSION['error'] = "Producto no válido.";
            $this->redirectBack();
        }

        $carrito = &$this->obtenerCarrito();

        if (isset($carrito[$id_plato])) {
            $carrito[$id_plato]['cantidad']++;
        } else {
            $producto = $this->productosDAO->obtenerPorId($id_plato);
            if (!$producto) {
                $_SESSION['error'] = "Producto no encontrado.";
                $this->redirectBack();
            }

            $carrito[$id_plato] = [
                'producto' => $producto,
                'nombre' => $producto->getNombre(),
                'precio' => $producto->getPrecio(),
                'cantidad' => 1,
                'subtotal' => $producto->getPrecio(),
                'imagen' => $producto->getImagenPrincipal() ?? 'default.jpg',
                'descripcion' => $producto->getDescripcion()
            ];
        }

        // Actualizar subtotal
        $carrito[$id_plato]['subtotal'] = $carrito[$id_plato]['precio'] * $carrito[$id_plato]['cantidad'];

        $_SESSION['success'] = "Producto agregado al carrito.";
        $this->redirectTo('producto', 'mostrarCarrito');
    }

    // Actualizar la cantidad de un producto en el carrito (+ o -)
    public function actualizarCantidad() {
        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
        $accion = filter_input(INPUT_POST, 'accion',);

        if (!$id_plato || !in_array($accion, ['+', '-'])) {
            $_SESSION['error'] = "Datos no válidos para actualizar cantidad.";
            $this->redirectBack();
        }

        $carrito = &$this->obtenerCarrito();

        if (!isset($carrito[$id_plato])) {
            $_SESSION['error'] = "El producto no está en el carrito.";
            $this->redirectBack();
        }

        $cantidad = $carrito[$id_plato]['cantidad'];

        if ($accion === '+') {
            $cantidad++;
        } else {
            $cantidad--;
        }

        if ($cantidad <= 0) {
            unset($carrito[$id_plato]);
            $_SESSION['success'] = "Producto eliminado del carrito.";
        } else {
            $carrito[$id_plato]['cantidad'] = $cantidad;
            $carrito[$id_plato]['subtotal'] = $carrito[$id_plato]['precio'] * $cantidad;
        }

        $this->redirectTo('producto', 'mostrarCarrito');
    }

    // Eliminar un producto del carrito
    public function eliminarPlatoDeCesta() {
        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
        $carrito = &$this->obtenerCarrito();

        if ($id_plato && isset($carrito[$id_plato])) {
            unset($carrito[$id_plato]);
            $_SESSION['success'] = "Producto eliminado del carrito.";
        } else {
            $_SESSION['error'] = "El producto no existe en el carrito.";
        }

        $this->redirectTo('producto', 'mostrarCarrito');
    }

    // Mostrar el carrito
    public function mostrarCarrito() {
        require_once('views/Cesta.php');
    }

    // Obtener o inicializar el carrito (por referencia)
    private function &obtenerCarrito() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        return $_SESSION['carrito'];
    }

    // Redirigir a una acción del controlador
    private function redirectTo($controller, $action) {
        header("Location: index.php?controller=$controller&action=$action");
        exit();
    }

    // Redirigir a la página anterior o a index.php si no hay HTTP_REFERER
    private function redirectBack() {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: index.php');
        }
        exit();
    }
}
