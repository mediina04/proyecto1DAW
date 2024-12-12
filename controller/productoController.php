<?php
require_once 'config/data_base.php';
require_once 'model/producto.php';
require_once 'model/productosDAO.php';

class ProductoController {

    private $productosDAO;

    public function __construct() {
        $conexion = DataBase::connect();
        $this->productosDAO = new ProductosDAO($conexion);
    }

    // Mostrar la página de inicio con los productos
    public function index() {
        try {
            $productos = $this->productosDAO->obtenerTodos();
            require_once('views/Inicio.php');
        } catch (Exception $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
        }
    }

    // Añadir un producto al carrito
    public function agregarAlCarrito() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

        if (!$id_plato || $id_plato <= 0) {
            $_SESSION['error'] = "Producto no válido.";
            $this->redirectBack();
        }

        try {
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }

            if (isset($_SESSION['carrito'][$id_plato])) {
                $_SESSION['carrito'][$id_plato]['cantidad'] += 1;
            } else {
                $producto = $this->productosDAO->obtenerPorId($id_plato);

                if (!$producto) {
                    $_SESSION['error'] = "Producto no encontrado.";
                    $this->redirectBack();
                }

                $_SESSION['carrito'][$id_plato] = [
                    'producto' => $producto,
                    'cantidad' => 1
                ];
            }

            $_SESSION['carrito'][$id_plato]['subtotal'] = 
                $_SESSION['carrito'][$id_plato]['producto']->getPrecio() *
                $_SESSION['carrito'][$id_plato]['cantidad'];

            $this->redirectTo('producto', 'mostrarCarrito');
        } catch (Exception $e) {
            error_log("Error al agregar al carrito: " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error al procesar el carrito.";
            $this->redirectBack();
        }
    }

    // Actualizar la cantidad de un producto en el carrito
    public function actualizarCantidad() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_plato = filter_input(INPUT_GET, 'id_plato', FILTER_VALIDATE_INT);
        $accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING); // '+' o '-'

        if (!$id_plato || !$accion || ($accion !== '+' && $accion !== '-')) {
            $_SESSION['error'] = "Acción no válida.";
            $this->redirectBack();
        }

        try {
            if (!isset($_SESSION['carrito'][$id_plato])) {
                $_SESSION['error'] = "";
                $this->redirectBack();
            }

            // Modificar la cantidad según la acción
            if ($accion === '+') {
                $_SESSION['carrito'][$id_plato]['cantidad']++;
            } elseif ($accion === '-' && $_SESSION['carrito'][$id_plato]['cantidad'] > 1) {
                $_SESSION['carrito'][$id_plato]['cantidad']--;
            }

            // Actualizar el subtotal
            $_SESSION['carrito'][$id_plato]['subtotal'] = 
                $_SESSION['carrito'][$id_plato]['producto']->getPrecio() *
                $_SESSION['carrito'][$id_plato]['cantidad'];

            // Si la cantidad es 0, eliminar el producto
            if ($_SESSION['carrito'][$id_plato]['cantidad'] == 0) {
                unset($_SESSION['carrito'][$id_plato]);
                $_SESSION['success'] = "";
            }

            $this->redirectTo('producto', 'mostrarCarrito');
        } catch (Exception $e) {
            error_log("Error al actualizar la cantidad: " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error al actualizar la cantidad.";
            $this->redirectBack();
        }
    }

    // Función para eliminar un plato del carrito
    public function eliminarPlatoDeCesta($id_plato) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$id_plato])) {
            unset($_SESSION['carrito'][$id_plato]);
            $_SESSION['success'] = "";
        } else {
            $_SESSION['error'] = "El plato que intentas eliminar no existe en el carrito.";
        }
    }

    // Mostrar el carrito
    public function mostrarCarrito() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once('views/Cesta.php');
    }

    // Finalizar la compra (implementación futura)
    public function finalizarCompra() {
        // Lógica para finalizar compra
    }

    // Redirigir a una acción
    private function redirectTo($controller, $action) {
        header('Location: ' . URL_BASE . "index.php?controller=$controller&action=$action");
        exit();
    }

    // Redirigir a la página anterior
    private function redirectBack() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>
