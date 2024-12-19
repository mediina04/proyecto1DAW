<?php

abstract class Usuario {
    protected $id;
    protected $usuario;
    protected $nombre;
    protected $apellido;
    protected $contraseña;
    protected $email;
    protected $telefono;
    protected $direccion;

    // Constructor
    public function __construct($id = null, $usuario = null, $nombre = null, $apellido = null, $contraseña = null, $email = null, $telefono = null, $direccion = null) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->contraseña = $contraseña;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    // Setters
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setContraseña($contraseña) {
        $this->contraseña = $contraseña;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
}
