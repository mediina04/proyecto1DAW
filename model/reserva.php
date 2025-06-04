<?php 

class Reserva {
    private $id_reserva;
    private $id_usuario;
    private $fecha_reserva;
    private $cantidad_personas;
    private $nombre;

    // Constructor con valores predeterminados para comentarios (pueden ser nulos)
    public function __construct($id_usuario, $fecha_reserva, $cantidad_personas, $nombre = null, $id_reserva = null) {
        if (!$id_usuario || !$fecha_reserva || !$cantidad_personas) {
            throw new InvalidArgumentException("Todos los campos son obligatorios.");
        }
    
        $this->id_reserva = $id_reserva;
        $this->id_usuario = $id_usuario;
        $this->fecha_reserva = $fecha_reserva;
        $this->cantidad_personas = $cantidad_personas;
        $this->nombre = $nombre;
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

    public function getNombre() {
        return $this->nombre;
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

    public function setNombre($nombre) {
        // Verificar que $nombre sea una cadena de texto y no nula
        if (is_string($nombre)) {
            $this->nombre = $nombre;
        } else {
            throw new InvalidArgumentException("El nombre no puede ser nulo y debe ser una cadena de texto.");
        }
    }
    

    // Convertir objeto a array para facilitar la inserción en la base de datos
    public function toArray() {
        return [
            'id_reserva' => $this->id_reserva,
            'id_usuario' => $this->id_usuario,
            'fecha_reserva' => $this->fecha_reserva,
            'cantidad_personas' => $this->cantidad_personas,
            'nombre' => $this->nombre
        ];
    }
}



