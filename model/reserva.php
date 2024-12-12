<?php

class Reserva {
    private $id;
    private $id_usuario;
    private $fecha_reserva;
    private $cantidad_personas;
    private $comentarios;

    // Constructor
    public function __construct($id, $id_usuario, $fecha_reserva, $cantidad_personas, $comentarios = '') {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->fecha_reserva = $fecha_reserva;
        $this->cantidad_personas = $cantidad_personas;
        $this->comentarios = $comentarios;
    }

    // Getters
    public function getId() {
        return $this->id;
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

    // Setters
    public function setComentarios($comentarios) {
        $this->comentarios = $comentarios;
    }
}
