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
        $stmt = $this->conexion->query($query);
        $productos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Producto(
                $row['id_plato'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['imagen_principal'],
                $row['imagen_secundaria']
            );
        }
        return null;
    }

    // Método para agregar un nuevo producto
    public function agregar($producto) {
        $query = "INSERT INTO platos (nombre, descripcion, precio, imagen_principal, imagen_secundaria) 
                  VALUES (:nombre, :descripcion, :precio, :imagen_principal, :imagen_secundaria)";
        
        // Preparar la consulta
        $stmt = $this->conexion->prepare($query);
        
        // Vincular los parámetros con los valores del objeto Producto
        $stmt->bindParam(':nombre', $producto->getNombre());
        $stmt->bindParam(':descripcion', $producto->getDescripcion());
        $stmt->bindParam(':precio', $producto->getPrecio());
        $stmt->bindParam(':imagen_principal', $producto->getImagenPrincipal());
        $stmt->bindParam(':imagen_secundaria', $producto->getImagenSecundaria());

        // Ejecutar la consulta
        return $stmt->execute(); // Devuelve true si la inserción fue exitosa
    }
}
