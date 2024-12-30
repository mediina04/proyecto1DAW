<?php

class Pedido {
    private $id;
    private $id_usuario;
    private $fecha;
    private $total;
    private $estado;

    // Constructor
    public function __construct($id, $id_usuario, $fecha, $total, $estado) {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->fecha = $fecha;
        $this->total = $total;
        $this->estado = $estado;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getEstado() {
        return $this->estado;
    }

    // Setters
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setTotal($total) {
        $this->total = $total;
    }
}
