<?php
require_once 'model/Pedido.php';
require_once 'model/PedidosDAO.php';
require_once 'config/data_base.php';

class PedidoController {

    public function crear() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Comprobar que el usuario esté autenticado
        if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar un pedido.";
            header("Location: index.php?controller=usuario&action=login");
            exit();
        }

        if (!empty($_SESSION['carrito'])) {
            $usuarioId = $_SESSION['usuario']->getIdUsuario();
            $productos = $_SESSION['carrito'];

            // Calcular el total del pedido
            $total = array_reduce($productos, function ($sum, $producto) {
                return $sum + (isset($producto['subtotal']) ? $producto['subtotal'] : 0);
            }, 0);

            $db = DataBase::connect();
            $pedidoDAO = new PedidosDAO($db);

            try {
                // Insertar el pedido principal
                $pedidoId = $pedidoDAO->insert($usuarioId, date('Y-m-d H:i:s'), $total, 'pendiente');

                if ($pedidoId) {
                    // Insertar los detalles del pedido
                    $stmt = $db->prepare("INSERT INTO detalles_pedido (id_pedido, id_plato, cantidad, subtotal) VALUES (?, ?, ?, ?)");
                    foreach ($productos as $id_plato => $producto) {
                        $cantidad = $producto['cantidad'];
                        $subtotal = $producto['subtotal'];
                        $stmt->bind_param("iiid", $pedidoId, $id_plato, $cantidad, $subtotal);
                        $stmt->execute();
                    }
                    $stmt->close();

                    $_SESSION['carrito'] = []; // Vaciar el carrito
                    $_SESSION['success'] = "Pedido realizado con éxito. ID del pedido: $pedidoId";
                    header("Location: pedido_finalizado.php");
                    exit();
                } else {
                    throw new Exception("Error al crear el pedido.");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Ocurrió un error al realizar el pedido: " . $e->getMessage();
                header("Location: index.php?controller=pedido&action=cesta");
                exit();
            }
        } else {
            $_SESSION['error'] = "El carrito está vacío.";
            header("Location: index.php?controller=pedido&action=cesta");
            exit();
        }
    }

    public function cesta() {
        session_start();
        $carrito = $_SESSION['carrito'] ?? [];
        $success = $_SESSION['success'] ?? '';
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['success'], $_SESSION['error']);

        // Calcular totales y actualizar datos de productos
        $total = 0;
        if (!empty($carrito)) {
            $productosDAO = new ProductosDAO(DataBase::connect());
            foreach ($carrito as $id_plato => &$producto) {
                $producto_db = $productosDAO->obtenerPorId($id_plato);
                if ($producto_db) {
                    $producto['nombre'] = $producto_db->getNombre();
                    $producto['descripcion'] = $producto_db->getDescripcion();
                    $producto['precio'] = $producto_db->getPrecio();
                    $producto['imagen'] = $producto_db->getImagenPrincipal();
                    $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
                    $total += $producto['subtotal'];
                } else {
                    $producto['nombre'] = 'Producto desconocido';
                }
            }
            unset($producto);
        }

        require_once 'views/Cesta.php';
    }

    public function updateCantidad() {
        session_start();
        $id_plato = filter_input(INPUT_GET, 'id_plato', FILTER_VALIDATE_INT);
        $accion = filter_input(INPUT_GET, 'accion',);

        if ($id_plato && in_array($accion, ['+', '-'])) {
            if (isset($_SESSION['carrito'][$id_plato])) {
                $cantidad = $_SESSION['carrito'][$id_plato]['cantidad'];
                $cantidad = ($accion === '+') ? $cantidad + 1 : max(0, $cantidad - 1);
                if ($cantidad <= 0) {
                    unset($_SESSION['carrito'][$id_plato]);
                    $_SESSION['success'] = "Producto eliminado de la cesta.";
                } else {
                    $_SESSION['carrito'][$id_plato]['cantidad'] = $cantidad;
                    $_SESSION['carrito'][$id_plato]['subtotal'] = $cantidad * $_SESSION['carrito'][$id_plato]['precio'];
                }
            }
        } else {
            $_SESSION['error'] = "ID de producto inválido.";
        }
        header("Location: index.php?controller=pedido&action=cesta");
        exit;
    }

    public function eliminarProducto() {
        session_start();
        $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
        if ($id_plato && isset($_SESSION['carrito'][$id_plato])) {
            unset($_SESSION['carrito'][$id_plato]);
            $_SESSION['success'] = "Producto eliminado de la cesta.";
        } else {
            $_SESSION['error'] = "Producto no encontrado.";
        }
        header("Location: index.php?controller=pedido&action=cesta");
        exit;
    }

}
