<?php

class Producto {

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $imagenPrincipal;
    private $imagenSecundaria;

    public function __construct($id, $nombre, $descripcion, $precio, $imagenPrincipal, $imagenSecundaria) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->imagenPrincipal = $imagenPrincipal;
        $this->imagenSecundaria = $imagenSecundaria;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getImagenPrincipal() {
        return $this->imagenPrincipal;
    }

    public function getImagenSecundaria() {
        return $this->imagenSecundaria;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setImagenPrincipal($imagenPrincipal) {
        $this->imagenPrincipal = $imagenPrincipal;
    }

    public function setImagenSecundaria($imagenSecundaria) {
        $this->imagenSecundaria = $imagenSecundaria;
    }
}

?>
