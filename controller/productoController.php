<?php
require_once 'config/data_base.php';
require_once 'model/Producto.php';
require_once 'model/ProductosDAO.php';

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
            $carrito = &$this->obtenerCarrito();

            if (isset($carrito[$id_plato])) {
                $carrito[$id_plato]['cantidad'] += 1;
            } else {
                $producto = $this->productosDAO->obtenerPorId($id_plato);

                if (!$producto) {
                    $_SESSION['error'] = "Producto no encontrado.";
                    $this->redirectBack();
                }

                $carrito[$id_plato] = [
                    'producto' => $producto,
                    'cantidad' => 1,
                    'subtotal' => $producto->getPrecio()
                ];
            }

            $carrito[$id_plato]['subtotal'] =
                $carrito[$id_plato]['producto']->getPrecio() *
                $carrito[$id_plato]['cantidad'];

            $_SESSION['success'] = "Producto agregado al carrito.";
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

        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
        $accion = filter_input(INPUT_POST, 'accion');

        $carrito = &$this->obtenerCarrito();

        if (isset($carrito[$id_plato])) {
            if ($accion === '+'&& $carrito[$id_plato]['cantidad'] < 1) {
                $carrito[$id_plato]['cantidad']++;
            } elseif ($accion === '-' && $carrito[$id_plato]['cantidad'] > 1) {
                $carrito[$id_plato]['cantidad']--;
            } elseif ($accion === '-' && $carrito[$id_plato]['cantidad'] === 1) {
                unset($carrito[$id_plato]);
                $_SESSION['success'] = "Producto eliminado del carrito.";
                $this->redirectTo('producto', 'mostrarCarrito');
            }

            $carrito[$id_plato]['subtotal'] =
                $carrito[$id_plato]['producto']->getPrecio() *
                $carrito[$id_plato]['cantidad'];

            $_SESSION['success'] = "Cantidad actualizada.";
        } else {
            $_SESSION['error'] = "El producto no existe en el carrito.";
        }

        $this->redirectTo('producto', 'mostrarCarrito');
    }

    // Eliminar un producto del carrito
    public function eliminarPlatoDeCesta() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
        $carrito = &$this->obtenerCarrito();

        if (isset($carrito[$id_plato])) {
            unset($carrito[$id_plato]);
            $_SESSION['success'] = "Producto eliminado del carrito.";
        } else {
            $_SESSION['error'] = "El plato no existe en el carrito.";
        }

        $this->redirectTo('producto', 'mostrarCarrito');
    }

    // Mostrar el carrito
    public function mostrarCarrito() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once('views/Cesta.php');
    }

    // Obtener o inicializar el carrito
    private function &obtenerCarrito() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        return $_SESSION['carrito'];
    }

    // Redirigir a una acción
    private function redirectTo($controller, $action) {
        header("Location: index.php?controller=$controller&action=$action");
        exit();
    }

    // Redirigir a la página anterior
    private function redirectBack() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
