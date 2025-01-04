<?php

class Producto {

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $imagenPrincipal;
    private $imagenSecundaria;

    public function __construct($id, $nombre, $descripcion, $precio, $imagenPrincipal, $imagenSecundaria) {
        if (!is_numeric($precio) || $precio < 0) {
            throw new InvalidArgumentException("El precio debe ser un número positivo.");
        }

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
    public function setPrecio($precio) {
        if (!is_numeric($precio) || $precio < 0) {
            throw new InvalidArgumentException("El precio debe ser un número positivo.");
        }
        $this->precio = $precio;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setImagenPrincipal($imagenPrincipal) {
        $this->imagenPrincipal = $imagenPrincipal;
    }

    public function setImagenSecundaria($imagenSecundaria) {
        $this->imagenSecundaria = $imagenSecundaria;
    }

    // Método para convertir a array
    public function toArray() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'imagen_principal' => $this->imagenPrincipal,
            'imagen_secundaria' => $this->imagenSecundaria,
        ];
    }
}
