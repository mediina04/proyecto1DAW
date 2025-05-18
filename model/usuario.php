<?php
class Usuario {
    // Atributos privados
    private $id;
    private $usuario;
    private $nombre;
    private $apellido;
    private $contrasena;
    private $email;
    private $telefono;
    private $direccion;

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
        ?int $id = null
    ) {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->contrasena = $contrasena;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->id = $id;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
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

    // Setters (puedes añadir validaciones aquí si lo necesitas)
    public function setId(int $id): void {
        $this->id = $id;
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
}
?>