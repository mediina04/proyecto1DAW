<?php

// Incluir los archivos necesarios para las clases que vamos a usar
include_once 'model/UsuariosDAO.php';
include_once 'model/PedidosDAO.php';
include_once 'model/PlatosDAO.php';
include_once 'model/ReservasDAO.php';
include_once 'config/data_base.php';

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
                    echo json_encode(["error" => "Datos incompletos para el usuario"]);
                    return;
                }

                $usuario = new Usuario(null, $data->usuario, $data->nombre, $data->apellido, $data->email, $data->contrasena, $data->telefono);

                if (UsuariosDAO::insert($usuario)) {
                    echo json_encode(["success" => true, "message" => "Usuario agregado con éxito."]);
                } else {
                    echo json_encode(["error" => "Error al agregar el usuario."]);
                }
            } catch (Exception $e) {
                echo json_encode(["error" => "Error al agregar usuario: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["error" => "Datos no válidos o faltantes."]);
        }
    }

    public function eliminarUsuario() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data && isset($data->id)) {
            try {
                if (UsuariosDAO::eliminar($data->id)) {
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

        if ($data && isset($data->id)) {
            try {
                if (!isset($data->nombre, $data->apellido, $data->email, $data->telefono)) {
                    echo json_encode(["error" => "Datos incompletos para actualizar el usuario."]);
                    return;
                }

                if (UsuariosDAO::actualizar($data->id, $data->nombre, $data->apellido, $data->email, $data->telefono)) {
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
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        try {
            $pedidos = PedidosDAO::getAll();
            echo json_encode($pedidos ?: ["mensaje" => "No hay pedidos disponibles"], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al obtener pedidos: " . $e->getMessage()]);
        }
    }

    public function agregarPedido() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data) {
            try {
                if (!isset($data->id_cliente, $data->fecha_pedido, $data->productos)) {
                    echo json_encode(["error" => "Datos incompletos para el pedido."]);
                    return;
                }

                $idPedido = PedidosDAO::insert($data->id_cliente, $data->fecha_pedido, $data->productos);

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
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        try {
            $platos = PlatosDAO::getAll();
            echo json_encode($platos ?: ["mensaje" => "No hay platos disponibles"], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al obtener platos: " . $e->getMessage()]);
        }
    }

    public function agregarPlato() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data) {
            try {
                if (!isset($data->nombre, $data->precio, $data->descripcion)) {
                    echo json_encode(["error" => "Datos incompletos para el plato."]);
                    return;
                }

                if (PlatosDAO::insert($data->nombre, $data->precio, $data->descripcion)) {
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
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        try {
            $reservas = ReservasDAO::getAll();
            echo json_encode($reservas ?: ["mensaje" => "No hay reservas disponibles"], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al obtener reservas: " . $e->getMessage()]);
        }
    }

    public function agregarReserva() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $data = json_decode(file_get_contents("php://input"));

        if ($data) {
            try {
                if (!isset($data->id_usuario, $data->fecha, $data->hora, $data->num_personas)) {
                    echo json_encode(["error" => "Datos incompletos para la reserva."]);
                    return;
                }

                if (ReservasDAO::insert($data->id_usuario, $data->fecha, $data->hora, $data->num_personas)) {
                    echo json_encode(["success" => true, "message" => "Reserva agregada con éxito."]);
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
