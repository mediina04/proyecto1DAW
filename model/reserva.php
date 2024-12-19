<?php 

class Reserva {
    private $id_reserva;
    private $id_usuario;
    private $fecha_reserva;
    private $cantidad_personas;
    private $comentarios;

    // Constructor con valores predeterminados para comentarios (pueden ser nulos)
    public function __construct($id_reserva = null, $id_usuario, $fecha_reserva, $cantidad_personas, $comentarios = null) {
        if (!$id_usuario || !$fecha_reserva || !$cantidad_personas) {
            throw new InvalidArgumentException("Todos los campos son obligatorios.");
        }
    
        $this->id_reserva = $id_reserva;
        $this->id_usuario = $id_usuario;
        $this->fecha_reserva = $fecha_reserva;
        $this->cantidad_personas = $cantidad_personas;
        $this->comentarios = $comentarios;
    }
    
    // Getters
    public function getIdReserva() {
        return $this->id_reserva;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function getFechaReserva() {
        return $this->fecha_reserva;
    }

    public function getCantidadPersonas() {
        return $this->cantidad_personas;
    }

    public function getComentarios() {
        return $this->comentarios;
    }

    // Setters con validación
    public function setIdReserva($id_reserva) {
        if (is_int($id_reserva) && $id_reserva > 0) {
            $this->id_reserva = $id_reserva;
        }
    }

    public function setIdUsuario($id_usuario) {
        if (is_int($id_usuario) && $id_usuario > 0) {
            $this->id_usuario = $id_usuario;
        }
    }

    public function setFechaReserva($fecha_reserva) {
        $this->fecha_reserva = $fecha_reserva;
    }

    public function setCantidadPersonas($cantidad_personas) {
        if (is_int($cantidad_personas) && $cantidad_personas > 0) {
            $this->cantidad_personas = $cantidad_personas;
        }
    }

    public function setComentarios($comentarios) {
        // Permitir comentarios nulos o cadenas de texto
        if ($comentarios === null || is_string($comentarios)) {
            $this->comentarios = $comentarios;
        }
    }

    // Convertir objeto a array para facilitar la inserción en la base de datos
    public function toArray() {
        return [
            'id_reserva' => $this->id_reserva,
            'id_usuario' => $this->id_usuario,
            'fecha_reserva' => $this->fecha_reserva,
            'cantidad_personas' => $this->cantidad_personas,
            'comentarios' => $this->comentarios
        ];
    }

    // Método para mostrar la información de la reserva de forma legible
    public function mostrarReserva() {
        return "Reserva ID: " . $this->id_reserva . " para " . $this->cantidad_personas . " personas, fecha: " . $this->fecha_reserva . ($this->comentarios ? ", Comentarios: " . $this->comentarios : "");
    }
}
?>
