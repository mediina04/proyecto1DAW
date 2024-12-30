<?php
class Usuario {
    private $id;
    private $usuario;
    private $nombre;
    private $apellido;
    private $contrasena;
    private $email;
    private $telefono;
    private $direccion;

    // Constructor
    public function __construct($usuario, $nombre, $apellido, $contrasena, $email, $telefono = null, $direccion = null, $id = null) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->contrasena = $contrasena;
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

    public function getContrasena() {
        return $this->contrasena;
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

    // Setters con validaciones
    public function setId($id) {
        $this->id = $id;
    }

    public function setUsuario($usuario) {
        if (empty($usuario) || !preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
            throw new Exception("El nombre de usuario solo debe contener letras, números y guiones bajos.");
        }
        $this->usuario = $usuario;
    }

    public function setNombre($nombre) {
        if (empty($nombre)) {
            throw new Exception("El nombre no puede estar vacío.");
        }
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        if (empty($apellido)) {
            throw new Exception("El apellido no puede estar vacío.");
        }
        $this->apellido = $apellido;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El correo electrónico no es válido.");
        }
        $this->email = $email;
    }

    public function setTelefono($telefono) {
        if (!empty($telefono) && !preg_match('/^\+?[0-9]{10,15}$/', $telefono)) {
            throw new Exception("El teléfono no tiene un formato válido.");
        }
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion) {
        if (empty($direccion)) {
            throw new Exception("La dirección no puede estar vacía.");
        }
        $this->direccion = $direccion;
    }

    public function __toString() {
        return "Usuario: {$this->usuario}, Nombre: {$this->nombre} {$this->apellido}, Correo: {$this->email}";
    }
}

?>
