<?php
include_once __DIR__ . '/../config/data_base.php';
include_once __DIR__ . '/../model/Producto.php';

class ProductosDAO {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $query = "SELECT * FROM platos";
        $stmt = $this->conexion->query($query); // Consulta directa para obtener todos los productos

        $productos = [];
        while ($row = $stmt->fetch_assoc()) {
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
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("ID de producto inválido.");
        }
    
        $query = "SELECT * FROM platos WHERE id_plato = ?";
        $stmt = $this->conexion->prepare($query);
    
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }
    
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $stmt->close(); // Cerrar el statement
            return new Producto(
                $row['id_plato'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['imagen_principal'],
                $row['imagen_secundaria']
            );
        } else {
            error_log("Producto con ID $id no encontrado."); // Registro en el log
        }
    
        $stmt->close(); // Cerrar el statement
        return null;
    }
    

}
