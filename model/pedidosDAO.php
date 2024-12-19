<?php

class PedidosDAO {
    private $db;

    public function __construct($db) {
        $this->db = $db;
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

    public function obtenerPedidosPorUsuario($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

