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
            header("Location: views/Login.php");
            exit();
        }

        if (!empty($_SESSION['carrito'])) {
            $usuarioId = $_SESSION['usuario']->getId();
            $productos = $_SESSION['carrito'];

            // Calcular el total del pedido
            $total = array_reduce($productos, function ($sum, $producto) {
                return $sum + (isset($producto['subtotal']) ? $producto['subtotal'] : 0);
            }, 0);

            $pedidoDAO = new PedidosDAO(DataBase::connect());

            try {
                // Intentar crear el pedido
                $pedidoId = $pedidoDAO->insertarPedido(null, $usuarioId, date('Y-m-d H:i:s'), $total, 'pendiente');

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

}
