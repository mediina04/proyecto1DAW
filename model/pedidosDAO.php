<?php

class PedidosDAO {
    private $db;

    public function __construct($db) {
        $this->db = $db; // $db debe ser una instancia de mysqli
    }

    // Eliminar un plato del carrito (lógica de sesión, no de BD)
    public function eliminarPlatoDeCesta(int $id_plato): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

            if (!$id_plato) {
                setSessionMessage('error', 'ID de plato inválido.');
                redirect('Cesta.php');
                return;
            }

            if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
                setSessionMessage('error', 'No hay carrito activo.');
                redirect('Cesta.php');
                return;
            }

            if (isset($_SESSION['carrito'][$id_plato])) {
                unset($_SESSION['carrito'][$id_plato]);
                setSessionMessage('success', 'El plato ha sido eliminado del carrito.');
            } else {
                setSessionMessage('error', 'El plato no se encontró en el carrito.');
            }

            redirect('Cesta.php');
        }
    }

    // Insertar un nuevo pedido
    public function insert($id_usuario, $fecha, $total, $estado) {
        $stmt = $this->db->prepare("INSERT INTO pedidos (id_usuario, fecha, total, estado) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("isds", $id_usuario, $fecha, $total, $estado);
        $result = $stmt->execute();
        $insert_id = $stmt->insert_id;
        $stmt->close();
        return $result ? $insert_id : false;
    }

    // Obtener el último pedido de un usuario
    public function obtenerUltimoPedidoPorUsuario($id_usuario) {
        $query = "SELECT p.*, 
                         GROUP_CONCAT(dp.id_plato, ':', dp.cantidad, ':', dp.subtotal SEPARATOR '|') AS productos 
                  FROM pedidos p
                  LEFT JOIN detalles_pedido dp ON p.id_pedido = dp.id_pedido
                  WHERE p.id_usuario = ? 
                  GROUP BY p.id_pedido 
                  ORDER BY p.fecha DESC 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();

        if ($pedido) {
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

        $stmt->close();
        return $pedido;
    }

    // Obtener todos los productos de un pedido
    public function getProductosByPedidoId($id_pedido) {
        $query = "SELECT p.id_plato, p.nombre, dp.cantidad, dp.subtotal 
                  FROM detalles_pedido dp
                  JOIN platos p ON dp.id_plato = p.id_plato
                  WHERE dp.id_pedido = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        $stmt->close();
        return $productos;
    }

    // Obtener los pedidos de un usuario
    public function obtenerPedidosPorUsuario($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
        if (!$stmt) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        $stmt->close();
        return $pedidos;
    }

    // Obtener todos los pedidos
    public function getAll() {
        $result = $this->db->query("SELECT * FROM pedidos ORDER BY fecha DESC");
        $pedidos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
            $result->free();
        }
        return $pedidos;
    }
}

function obtenerUltimoPedido($id_usuario) {
    include_once __DIR__ . '/../config/data_base.php';
    include_once __DIR__ . '/../model/pedidosDAO.php';

    $conexion = DataBase::connect();
    $pedidosDAO = new PedidosDAO($conexion);

    // Llama al método de instancia
    $ultimoPedido = $pedidosDAO->obtenerUltimoPedidoPorUsuario($id_usuario);

    $conexion->close();
    return $ultimoPedido;
}


