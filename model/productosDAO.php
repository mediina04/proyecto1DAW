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
        $stmt = $this->conexion->query($query);
        if (!$stmt) {
            throw new Exception("Error en consulta obtenerTodos: " . $this->conexion->error);
        }

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
            throw new Exception("Error al preparar la consulta obtenerPorId: " . $this->conexion->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $producto = new Producto(
                $row['id_plato'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['imagen_principal'],
                $row['imagen_secundaria']
            );
            $stmt->close();
            return $producto;
        }

        $stmt->close();
        return null;
    }

    // Obtener productos destacados (aleatorios) limitados
    public function obtenerDestacados($limite = 8) {
        $sql = "SELECT * FROM platos ORDER BY RAND() LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta obtenerDestacados: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = new Producto(
                $row['id_plato'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['imagen_principal'],
                $row['imagen_secundaria']
            );
        }
        $stmt->close();
        return $productos;
    }

    // Insertar nuevo producto
    public function insertar($nombre, $precio, $descripcion, $imagen_principal = null, $imagen_secundaria = null) {
        $sql = "INSERT INTO platos (nombre, precio, descripcion, imagen_principal, imagen_secundaria) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta insertar: " . $this->conexion->error);
        }
        $stmt->bind_param("sdsss", $nombre, $precio, $descripcion, $imagen_principal, $imagen_secundaria);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Actualizar producto (opcional, si lo necesitas)
    public function actualizar($id, $nombre, $precio, $descripcion, $imagen_principal = null, $imagen_secundaria = null) {
        $sql = "UPDATE platos SET nombre = ?, precio = ?, descripcion = ?, imagen_principal = ?, imagen_secundaria = ? WHERE id_plato = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta actualizar: " . $this->conexion->error);
        }
        $stmt->bind_param("sdsssi", $nombre, $precio, $descripcion, $imagen_principal, $imagen_secundaria, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Eliminar producto por ID (opcional)
    public function eliminar($id) {
        $sql = "DELETE FROM platos WHERE id_plato = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta eliminar: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
