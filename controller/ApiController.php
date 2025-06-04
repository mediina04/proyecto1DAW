<?php

// Incluir los archivos necesarios para las clases que vamos a usar
include_once(__DIR__ . '/../model/UsuariosDAO.php');
include_once(__DIR__ . '/../model/PedidosDAO.php');
include_once(__DIR__ . '/../model/ProductosDAO.php');
include_once(__DIR__ . '/../model/ReservasDAO.php');
include_once(__DIR__ . '/../config/data_base.php');


class ApiController {
    // Acción para mostrar el panel de administración
    public function panel() {
        include_once 'API/Panel_Admin.php';
    }

    // USUARIOS
    public function obtenerUsuarios() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        try {
            $usuarios = UsuariosDAO::getAll();
            echo json_encode($usuarios ?: ["mensaje" => "No hay usuarios disponibles"], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al obtener usuarios: " . $e->getMessage()]);
        }
    }

    public function agregarUsuario() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data) {
            try {
                if (!isset($data->usuario, $data->nombre, $data->apellido, $data->email, $data->contrasena, $data->telefono)) {
                    http_response_code(400);
                    echo json_encode(["error" => "Datos incompletos para el usuario"]);
                    return;
                }

                // Validación de email
                if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                    http_response_code(400);
                    echo json_encode(["error" => "Email no válido"]);
                    return;
                }

                // Validación de contraseña
                if (strlen($data->contrasena) < 8) {
                    http_response_code(400);
                    echo json_encode(["error" => "La contraseña debe tener al menos 8 caracteres"]);
                    return;
                }

                // Hashear la contraseña
                $data->contrasena = password_hash($data->contrasena, PASSWORD_DEFAULT);

                $usuario = new Usuario(
                    $data->usuario,
                    $data->nombre,
                    $data->apellido,
                    $data->contrasena,
                    $data->email,
                    $data->telefono,
                    $data->direccion ?? null
                );

                if (UsuariosDAO::insert($usuario)) {
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Usuario agregado con éxito."]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Error al agregar el usuario."]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["error" => "Error al agregar usuario: " . $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Datos no válidos o faltantes."]);
        }
    }

    public function eliminarUsuario() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data && isset($data->id_usuario)) {
            try {
                if (UsuariosDAO::eliminar($data->id_usuario)) {
                    echo json_encode(["success" => true, "message" => "Usuario eliminado con éxito."]);
                } else {
                    echo json_encode(["error" => "Error al eliminar el usuario."]);
                }
            } catch (Exception $e) {
                echo json_encode(["error" => "Error al eliminar usuario: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["error" => "ID de usuario no proporcionado."]);
        }
    }

    public function actualizarUsuario() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data && isset($data->id_usuario)) {
            try {
                if (!isset($data->nombre, $data->apellido, $data->email, $data->telefono)) {
                    echo json_encode(["error" => "Datos incompletos para actualizar el usuario."]);
                    return;
                }

                if (UsuariosDAO::actualizar($data->id_usuario, $data->nombre, $data->apellido, $data->email, $data->telefono, $data->direccion ?? null)) {
                    echo json_encode(["success" => true, "message" => "Usuario actualizado con éxito."]);
                } else {
                    echo json_encode(["error" => "Error al actualizar el usuario."]);
                }
            } catch (Exception $e) {
                echo json_encode(["error" => "Error al actualizar usuario: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["error" => "Datos no válidos o faltantes."]);
        }
    }

    // PEDIDOS
public function obtenerPedidos() {

    try {
        $db = DataBase::connect();
        $pedidosDAO = new PedidosDAO($db);

        $pedidos = $pedidosDAO->getAll();
        echo json_encode($pedidos ?: ["mensaje" => "No hay pedidos disponibles"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error al obtener pedidos: " . $e->getMessage()]);
    }
}

public function agregarPedido() {

    $data = json_decode(file_get_contents("php://input"));

    if ($data) {
        try {
            if (!isset($data->id_usuario, $data->fecha_pedido, $data->total)) {
                echo json_encode(["error" => "Datos incompletos para el pedido."]);
                return;
            }

            $estado = isset($data->pendiente) && $data->pendiente ? 'pendiente' : 'completado';

            $db = DataBase::connect();
            $pedidosDAO = new PedidosDAO($db);

            $idPedido = $pedidosDAO->insert($data->id_usuario, $data->fecha_pedido, $data->total, $estado);

            if ($idPedido) {
                echo json_encode(["success" => true, "id_pedido" => $idPedido]);
            } else {
                echo json_encode(["error" => "Error al agregar el pedido."]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al agregar pedido: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Datos no válidos o faltantes."]);
    }
}


    // PLATOS
public function obtenerPlatos() {

    try {
        $db = DataBase::connect();
        $productosDAO = new ProductosDAO($db);

        $platos = $productosDAO->obtenerTodos();
        echo json_encode($platos ?: ["mensaje" => "No hay platos disponibles"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error al obtener platos: " . $e->getMessage()]);
    }
}

public function agregarPlato() {

    $data = json_decode(file_get_contents("php://input"));

    if ($data) {
        try {
            if (!isset($data->nombre, $data->precio, $data->descripcion)) {
                echo json_encode(["error" => "Datos incompletos para el plato."]);
                return;
            }

            $db = DataBase::connect();
            $productosDAO = new ProductosDAO($db);

            $resultado = $productosDAO->insertar(
                $data->nombre,
                $data->precio,
                $data->descripcion,
                $data->imagen_principal ?? null,
                $data->imagen_secundaria ?? null
            );

            if ($resultado) {
                echo json_encode(["success" => true, "message" => "Plato agregado con éxito."]);
            } else {
                echo json_encode(["error" => "Error al agregar el plato."]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al agregar plato: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Datos no válidos o faltantes."]);
    }
}


    // RESERVAS
    public function obtenerReservas() {

        try {
            $db = DataBase::connect();               // Obtienes la conexión
            $reservasDAO = new ReservasDAO($db);    // Creas el DAO con la conexión
            $reservas = $reservasDAO->getAll();     // Obtienes las reservas
            echo json_encode($reservas ?: ["mensaje" => "No hay reservas disponibles"], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al obtener reservas: " . $e->getMessage()]);
        }
    }


    public function agregarReserva() {
    $data = json_decode(file_get_contents("php://input"));

    if ($data) {
        try {
            if (!isset($data->id_usuario, $data->fecha, $data->hora, $data->num_personas)) {
                echo json_encode(["error" => "Datos incompletos para la reserva."]);
                return;
            }

            $db = DataBase::connect();                         // Obtiene conexión
            $reservasDAO = new ReservasDAO($db);               // Crea instancia DAO

            // Opcionalmente puedes combinar fecha y hora si tu base de datos lo requiere
            $fechaHora = $data->fecha . ' ' . $data->hora;

            $reservaId = $reservasDAO->crearReserva(
                $data->id_usuario,
                $fechaHora,
                $data->num_personas,
                $data->comentarios ?? null
            );

            if ($reservaId) {
                echo json_encode(["success" => true, "message" => "Reserva agregada con éxito.", "id" => $reservaId]);
            } else {
                echo json_encode(["error" => "Error al agregar la reserva."]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al agregar reserva: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Datos no válidos o faltantes."]);
    }
}

    
}
