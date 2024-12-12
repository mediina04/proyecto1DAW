<?php
require_once 'model/pedido.php';
require_once 'model/pedidosDAO.php';

class PedidoController {

    // Crear un nuevo pedido
    public function crear() {
        session_start();
        if (isset($_SESSION['usuario']) && !empty($_SESSION['carrito'])) {
            $usuarioId = $_SESSION['usuario']->getId();
            $productos = $_SESSION['carrito'];

            $pedidoDAO = new PedidosDAO(DataBase::connect());
            $pedidoId = $pedidoDAO->crearPedido($usuarioId, $productos);

            if ($pedidoId) {
                $_SESSION['carrito'] = [];
                echo "Pedido realizado con éxito. ID del pedido: $pedidoId";
            } else {
                echo "Error al realizar el pedido.";
            }
        } else {
            echo "No hay productos en el carrito o el usuario no está autenticado.";
        }
    }

    // Consultar los pedidos del usuario
    public function historial() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $usuarioId = $_SESSION['usuario']->getId();
            $pedidoDAO = new PedidosDAO(DataBase::connect());
            $pedidos = $pedidoDAO->obtenerPedidosPorUsuario($usuarioId);
            require_once 'views/pedidos/historial.php'; // Mostrar historial en la vista
        }
    }
}
