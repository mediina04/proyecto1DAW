<?php

class PedidosDAO {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function eliminarPlatoDeCesta(int $id_plato): void {
        // Verificar que el método sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

            // Validar ID del plato
            if (!$id_plato) {
                setSessionMessage('error', 'ID de plato inválido.');
                redirect('Cesta.php');
                return;
            }

            // Verificar si el carrito está definido en la sesión
            if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
                setSessionMessage('error', 'No hay carrito activo.');
                redirect('Cesta.php');
                return;
            }

            // Verificar si el plato existe en el carrito
            if (isset($_SESSION['carrito'][$id_plato])) {
                unset($_SESSION['carrito'][$id_plato]); // Eliminar plato del carrito
                setSessionMessage('success', 'El plato ha sido eliminado del carrito.');
            } else {
                setSessionMessage('error', 'El plato no se encontró en el carrito.');
            }

            redirect('Cesta.php'); // Redirigir a la página del carrito
        }
    }

    public function crearPedido($usuarioId, $productos, $total) {
        try {
            // Iniciar una transacción
            $this->db->beginTransaction();

            // Insertar el pedido en la tabla `pedidos`
            $stmt = $this->db->prepare("INSERT INTO pedidos (id_usuario, total, estado) VALUES (?, ?, 'pendiente')");
            $stmt->execute([$usuarioId, $total]);

            // Obtener el ID del pedido recién creado
            $pedidoId = $this->db->lastInsertId();

            // Insertar los detalles del pedido
            $stmtDetalle = $this->db->prepare("INSERT INTO detalles_pedido (id_pedido, id_plato, cantidad, subtotal) VALUES (?, ?, ?, ?)");
            foreach ($productos as $id_plato => $producto) {
                $stmtDetalle->execute([$pedidoId, $id_plato, $producto['cantidad'], $producto['subtotal']]);
            }

            // Confirmar la transacción
            $this->db->commit();

            return $pedidoId;
        } catch (Exception $e) {
            // Revertir la transacción si ocurre un error
            $this->db->rollBack();
            error_log("Error al crear el pedido: " . $e->getMessage());
            return false;
        }
    }

    // Obtener el último pedido de un usuario
    public function getLatestPedidoByUsuarioId($id_usuario) {
        $query = "SELECT * FROM pedidos WHERE id_usuario = :id_usuario ORDER BY fecha DESC LIMIT 1";
        $stmt = $this->db->prepare($query); // Cambié $this->con por $this->db
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el último pedido
    }

    // Obtener todos los productos de un pedido
    public function getProductosByPedidoId($id_pedido) {
        $query = "SELECT p.id_plato, p.nombre, dp.cantidad, dp.subtotal 
                  FROM detalles_pedido dp
                  JOIN platos p ON dp.id_plato = p.id_plato
                  WHERE dp.id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve los productos asociados al pedido
    }

    // Obtener los pedidos de un usuario
    public function obtenerPedidosPorUsuario($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


