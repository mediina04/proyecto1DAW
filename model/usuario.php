<?php

class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $contraseña;
    private $tipo_usuario;

    // Constructor
    public function __construct($id, $nombre, $email, $contraseña, $tipo_usuario) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->contraseña = $contraseña;
        $this->tipo_usuario = $tipo_usuario;
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function getTipoUsuario() {
        return $this->tipo_usuario;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
}
