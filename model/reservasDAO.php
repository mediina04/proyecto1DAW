<?php
require_once 'reserva.php';

class ReservasDAO {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Crear una nueva reserva
    public function guardar(Reserva $reserva) {
        $query = "INSERT INTO reservas (id_usuario, fecha_reserva, cantidad_personas, comentarios) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "isis",
            $reserva->getIdUsuario(),
            $reserva->getFechaReserva(),
            $reserva->getCantidadPersonas(),
            $reserva->getComentarios()
        );
        return $stmt->execute();
    }

    // Obtener todas las reservas de un usuario
    public function obtenerReservasPorUsuario($usuarioId) {
        $query = "SELECT * FROM reservas WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = new Reserva(
                $row['id_reserva'],
                $row['id_usuario'],
                $row['fecha_reserva'],
                $row['cantidad_personas'],
                $row['comentarios']
            );
        }
        return $reservas;
    }

    // Obtener una reserva por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM reservas WHERE id_reserva = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Reserva(
                $row['id_reserva'],
                $row['id_usuario'],
                $row['fecha_reserva'],
                $row['cantidad_personas'],
                $row['comentarios']
            );
        }
        return null;
    }

    // Eliminar una reserva por ID
    public function eliminar($id) {
        $query = "DELETE FROM reservas WHERE id_reserva = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
