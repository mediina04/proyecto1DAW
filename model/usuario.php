<?php
class Usuario {
    // Atributos privados
    private $id_usuario;
    private $usuario;
    private $nombre;
    private $apellido;
    private $contrasena;
    private $email;
    private $telefono;
    private $direccion;
    private $rol;

    /**
     * Constructor para usuarios.
     */
    public function __construct(
        string $usuario,
        string $nombre,
        string $apellido,
        string $contrasena,
        string $email,
        ?string $telefono = null,
        ?string $direccion = null,
        ?int $id_usuario = null,
        ?string $rol = null
    ) {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->contrasena = $contrasena;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->id_usuario = $id_usuario;
        $this->rol = $rol;
    }

    // Getters
    public function getIdUsuario(): ?int {
        return $this->id_usuario;
    }
    public function getUsuario(): string {
        return $this->usuario;
    }
    public function getNombre(): string {
        return $this->nombre;
    }
    public function getApellido(): string {
        return $this->apellido;
    }
    public function getContrasena(): string {
        return $this->contrasena;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getTelefono(): ?string {
        return $this->telefono;
    }
    public function getDireccion(): ?string {
        return $this->direccion;
    }
    public function getRol(): ?string {
        return $this->rol;
    }

    // Setters
    public function setIdUsuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }
    public function setUsuario(string $usuario): void {
        $this->usuario = $usuario;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }
    public function setApellido(string $apellido): void {
        $this->apellido = $apellido;
    }
    public function setContrasena(string $contrasena): void {
        $this->contrasena = $contrasena;
    }
    public function setEmail(string $email): void {
        $this->email = $email;
    }
    public function setTelefono(?string $telefono): void {
        $this->telefono = $telefono;
    }
    public function setDireccion(?string $direccion): void {
        $this->direccion = $direccion;
    }
    public function setRol(?string $rol): void {
        $this->rol = $rol;
    }
}
?>