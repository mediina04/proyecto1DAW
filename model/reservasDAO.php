<?php

class ReservasDAO {
    private $db;

    public function __construct($db) {
        // $db es una instancia de mysqli
        $this->db = $db;
    }

    // Crear nueva reserva
    public function crearReserva($id_usuario, $fecha_reserva, $cantidad_personas, $comentarios = null) {
        $sql = "INSERT INTO reservas (id_usuario, fecha_reserva, cantidad_personas, comentarios) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error en preparar consulta crearReserva: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("isis", $id_usuario, $fecha_reserva, $cantidad_personas, $comentarios);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            error_log("Error en ejecutar crearReserva: " . $stmt->error);
            return false;
        }
    }

    // Obtener todas las reservas
    public function getAll() {
        $sql = "SELECT * FROM reservas ORDER BY fecha_reserva DESC";
        $result = $this->db->query($sql);
        if (!$result) {
            error_log("Error en consulta getAll: " . $this->db->error);
            return [];
        }
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        return $reservas;
    }

    // Obtener reservas por usuario
    public function obtenerReservasPorUsuario($id_usuario) {
        $sql = "SELECT * FROM reservas WHERE id_usuario = ? ORDER BY fecha_reserva DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error en preparar consulta obtenerReservasPorUsuario: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            error_log("Error en ejecutar obtenerReservasPorUsuario: " . $stmt->error);
            return [];
        }
        $result = $stmt->get_result();
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        return $reservas;
    }

    // Obtener reserva por id
    public function obtenerReservaPorId($id_reserva) {
        $sql = "SELECT * FROM reservas WHERE id_reserva = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error en preparar consulta obtenerReservaPorId: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("i", $id_reserva);
        if (!$stmt->execute()) {
            error_log("Error en ejecutar obtenerReservaPorId: " . $stmt->error);
            return null;
        }
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Actualizar reserva
    public function actualizarReserva($id_reserva, $fecha_reserva, $cantidad_personas, $comentarios = null) {
        $sql = "UPDATE reservas SET fecha_reserva = ?, cantidad_personas = ?, comentarios = ? WHERE id_reserva = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error en preparar consulta actualizarReserva: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("sisi", $fecha_reserva, $cantidad_personas, $comentarios, $id_reserva);
        if (!$stmt->execute()) {
            error_log("Error en ejecutar actualizarReserva: " . $stmt->error);
            return false;
        }
        return true;
    }

    // Eliminar reserva
    public function eliminarReserva($id_reserva) {
        $sql = "DELETE FROM reservas WHERE id_reserva = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error en preparar consulta eliminarReserva: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("i", $id_reserva);
        if (!$stmt->execute()) {
            error_log("Error en ejecutar eliminarReserva: " . $stmt->error);
            return false;
        }
        return true;
    }
    // Obtener la reserva activa (más próxima o actual) de un usuario
    public function getReservaActiva($id_usuario) {
        $hoy = date('d-m-Y'); 

        $sql = "SELECT * FROM reservas WHERE id_usuario = ? AND fecha_reserva >= ? ORDER BY fecha_reserva ASC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error en preparar consulta getReservaActiva: " . $this->db->error);
            return null;
        }

        $stmt->bind_param("is", $id_usuario, $hoy);
        if (!$stmt->execute()) {
            error_log("Error en ejecutar getReservaActiva: " . $stmt->error);
            return null;
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}


