<?php
require_once 'pedido.php';

class PedidosDAO {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Crear un nuevo pedido
    public function crearPedido($usuarioId, $productos) {
        $this->conexion->begin_transaction(); // Iniciar transacción

        try {
            // Calcular el total del pedido
            $total = 0; // Inicializar el total a 0
            foreach ($productos as $producto) {
                // Sumar el subtotal de cada producto al total del pedido
                $total += $producto['cantidad'] * $producto['precio']; // Sumar cantidad * precio
            }

            // Insertar el pedido en la base de datos
            $query = "INSERT INTO pedidos (id_usuario, total, estado) VALUES (?, ?, 'pendiente')";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("id", $usuarioId, $total); // Ahora $total está calculado
            $stmt->execute();
            $pedidoId = $stmt->insert_id; // Obtener el ID del pedido insertado

            // Insertar detalles del pedido en la base de datos
            foreach ($productos as $producto) {
                $subtotal = $producto['cantidad'] * $producto['precio']; // Calcular subtotal del producto
                $queryDetalle = "INSERT INTO detalles_pedido (id_pedido, id_plato, cantidad, subtotal) 
                                 VALUES (?, ?, ?, ?)";
                $stmtDetalle = $this->conexion->prepare($queryDetalle);
                $stmtDetalle->bind_param("iiid", $pedidoId, $producto['producto_id'], $producto['cantidad'], $subtotal);
                $stmtDetalle->execute(); // Insertar detalle de cada producto
            }

            // Confirmar transacción si todo sale bien
            $this->conexion->commit();
            return $pedidoId; // Retornar el ID del pedido creado
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->conexion->rollback();
            return null; // Devolver null si algo salió mal
        }
    }

    // Obtener pedidos por usuario
    public function obtenerPedidosPorUsuario($usuarioId) {
        $query = "SELECT * FROM pedidos WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = new Pedido(
                $row['id_pedido'],
                $row['id_usuario'],
                $row['fecha'],
                $row['total'],
                $row['estado']
            );
        }
        return $pedidos;
    }

    // Obtener un pedido por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM pedidos WHERE id_pedido = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Pedido(
                $row['id_pedido'],
                $row['id_usuario'],
                $row['fecha'],
                $row['total'],
                $row['estado']
            );
        }
        return null;
    }

    // Actualizar el estado del pedido
    public function actualizarEstado($pedidoId, $nuevoEstado) {
        $query = "UPDATE pedidos SET estado = ? WHERE id_pedido = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $nuevoEstado, $pedidoId);
        return $stmt->execute();
    }
}
