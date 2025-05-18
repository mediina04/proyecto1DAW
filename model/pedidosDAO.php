<?php

class PedidosDAO {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function eliminarPlatoDeCesta(int $id_plato): void {
        // Verificar que el método sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

            // Validar ID del plato
            if (!$id_plato) {
                setSessionMessage('error', 'ID de plato inválido.');
                redirect('Cesta.php');
                return;
            }

            // Verificar si el carrito está definido en la sesión
            if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
                setSessionMessage('error', 'No hay carrito activo.');
                redirect('Cesta.php');
                return;
            }

            // Verificar si el plato existe en el carrito
            if (isset($_SESSION['carrito'][$id_plato])) {
                unset($_SESSION['carrito'][$id_plato]); // Eliminar plato del carrito
                setSessionMessage('success', 'El plato ha sido eliminado del carrito.');
            } else {
                setSessionMessage('error', 'El plato no se encontró en el carrito.');
            }

            redirect('Cesta.php'); // Redirigir a la página del carrito
        }
    }

    public static function insertarPedido($pedidoId, $usuarioId, $fecha, $total, $estado) {
        // Conectar a la base de datos
        $conexion = DataBase::connect();

        // Si la fecha ya viene en formato correcto, no la conviertas
        if (strtotime($fecha) !== false) {
            $fecha = date('Y-m-d H:i:s', strtotime($fecha));
        }

        // Preparar la consulta SQL
        $stmt = $conexion->prepare("INSERT INTO pedidos (id_pedido, id_usuario, fecha, total, estado) VALUES (?, ?, ?, ?, ?)");

        // Verificar si la preparación fue exitosa
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        // Asignar los parámetros con el formato correcto
        // Orden: id_pedido (i), id_usuario (i), fecha (s), total (d), estado (s)
        $stmt->bind_param("iids", $pedidoId, $usuarioId, $fecha, $total, $estado);

        // Ejecutar la consulta y verificar errores
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Obtener el ID del pedido recién insertado
        $id_pedido = $stmt->insert_id;

        // Cerrar la declaración y la conexión
        $stmt->close();
        $conexion->close();

        // Retornar el ID del pedido insertado
        return $id_pedido;
    }
    

    public static function obtenerUltimoPedidoPorUsuario($id_usuario) {
        // Conexión a la base de datos
        $conexion = DataBase::connect();
    
        // Consulta para obtener el último pedido
        $query = "SELECT p.*, 
                         GROUP_CONCAT(dp.id_plato, ':', dp.cantidad, ':', dp.subtotal SEPARATOR '|') AS productos 
                  FROM pedidos p
                  LEFT JOIN detalles_pedido dp ON p.id_pedido = dp.id_pedido
                  WHERE p.id_usuario = ? 
                  GROUP BY p.id_pedido 
                  ORDER BY p.fecha DESC 
                  LIMIT 1";
    
        $stmt = $conexion->prepare($query);
    
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }
    
        // Vincular los parámetros
        $stmt->bind_param("i", $id_usuario); // "i" indica un entero
    
        // Ejecutar la consulta
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
    
        // Obtener los resultados
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
    
        if ($pedido) {
            // Procesar productos en un array
            $productos = [];
            if (!empty($pedido['productos'])) {
                $productosDetalles = explode('|', $pedido['productos']);
                foreach ($productosDetalles as $detalle) {
                    [$id_plato, $cantidad, $subtotal] = explode(':', $detalle);
                    $productos[] = [
                        'id_plato' => $id_plato,
                        'cantidad' => $cantidad,
                        'subtotal' => $subtotal,
                    ];
                }
            }
            $pedido['productos'] = $productos;
        }
    
        // Cerrar la declaración y la conexión
        $stmt->close();
        $conexion->close();      
        
        return $pedido; // Retornar el pedido con productos
    }
    


    // Obtener todos los productos de un pedido
    public function getProductosByPedidoId($id_pedido) {
        $query = "SELECT p.id_plato, p.nombre, dp.cantidad, dp.subtotal 
                  FROM detalles_pedido dp
                  JOIN platos p ON dp.id_plato = p.id_plato
                  WHERE dp.id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve los productos asociados al pedido
    }

    // Obtener los pedidos de un usuario
    public function obtenerPedidosPorUsuario($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


