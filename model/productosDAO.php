<?php
require_once 'producto.php';

class ProductosDAO {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $query = "SELECT * FROM platos";
        $stmt = $this->conexion->query($query); // Usar query directamente para consultas simples
        $productos = [];

        while ($row = $stmt->fetch_assoc()) { // Cambiado a fetch_assoc para mysqli
            $productos[] = new Producto(
                $row['id_plato'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['imagen_principal'],
                $row['imagen_secundaria']
            );
        }
        return $productos;
    }

    // Obtener un producto por su ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM platos WHERE id_plato = ?";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        // Vincular parámetros usando bind_param (mysqli)
        $stmt->bind_param("i", $id); // "i" indica que el parámetro es un entero
        $stmt->execute();
        $result = $stmt->get_result(); // Obtener el resultado de la consulta

        if ($row = $result->fetch_assoc()) {
            return new Producto(
                $row['id_plato'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['imagen_principal'],
                $row['imagen_secundaria']
            );
        }
        return null; // Retornar null si no se encuentra el producto
    }

    // Método para agregar un nuevo producto
    public function agregar($producto) {
        $query = "INSERT INTO platos (nombre, descripcion, precio, imagen_principal, imagen_secundaria) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        // Vincular parámetros con los valores del objeto Producto
        $stmt->bind_param(
            "ssdss", // Tipos: s (string), d (double), i (integer)
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getImagenPrincipal(),
            $producto->getImagenSecundaria()
        );

        // Ejecutar la consulta
        return $stmt->execute(); // Devuelve true si la inserción fue exitosa
    }
}
