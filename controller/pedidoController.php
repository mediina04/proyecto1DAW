<?php
require_once 'model/Pedido.php';
require_once 'model/PedidosDAO.php';

class PedidoController {

    public function crear() {
        session_start();

        // Comprobar que el usuario esté autenticado
        if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar un pedido.";
            header("Location: views/Login.php");
            exit();
        }

        if (!empty($_SESSION['carrito'])) {
            $usuarioId = $_SESSION['usuario']->getId();
            $productos = $_SESSION['carrito'];

            // Calcular el total del pedido
            $total = array_reduce($productos, function ($sum, $producto) {
                return $sum + $producto['subtotal'];
            }, 0);

            $pedidoDAO = new PedidosDAO(DataBase::connect());

            try {
                // Intentar crear el pedido
                $pedidoId = $pedidoDAO->crearPedido($usuarioId, $productos, $total);

                if ($pedidoId) {
                    $_SESSION['carrito'] = []; // Vaciar el carrito
                    $_SESSION['success'] = "Pedido realizado con éxito. ID del pedido: $pedidoId";
                    header("Location: index.php?controller=pedido&action=historial");
                    exit();
                } else {
                    throw new Exception("Error al crear el pedido.");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Ocurrió un error al realizar el pedido: " . $e->getMessage();
                header("Location: views/Cesta.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "El carrito está vacío.";
            header("Location: views/Cesta.php");
            exit();
        }
    }

    public function historial() {
        session_start();

        // Verificar autenticación del usuario
        if (!isset($_SESSION['usuario'])) {
            $_SESSION['error'] = "Debes iniciar sesión para ver tu historial.";
            header("Location: views/Login.php");
            exit();
        }

        $usuarioId = $_SESSION['usuario']->getId();
        $pedidoDAO = new PedidosDAO(DataBase::connect());
        $pedidos = $pedidoDAO->obtenerPedidosPorUsuario($usuarioId);

        require_once 'views/pedidos/historial.php'; // Mostrar historial en la vista
    }
}
