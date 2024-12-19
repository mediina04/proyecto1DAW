<?php
require_once 'model/reserva.php';
require_once 'model/reservasDAO.php';

class ReservaController {

    // Función para crear una reserva
    public function crear() {
        // Verificar si la sesión está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Recoger los datos del formulario
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        $personas = filter_input(INPUT_POST, 'personas', FILTER_VALIDATE_INT);
        $fecha_reserva = filter_input(INPUT_POST, 'fecha_reserva', FILTER_SANITIZE_STRING);

        // Validar la fecha con expresión regular (formato YYYY-MM-DD)
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_reserva)) {
            $_SESSION['error'] = "La fecha de la reserva no es válida.";
            header("Location: index.php");
            exit();
        }

        if ($nombre && $telefono && $personas && $fecha_reserva) {
            $usuarioId = 1; // Suponemos un usuario fijo por ahora
            $reservaDAO = new ReservasDAO(DataBase::connect());

            // Crear la reserva
            $reserva = new Reserva(null, $usuarioId, $fecha_reserva, $personas, null);
            $reservaId = $reservaDAO->crearReserva($reserva);

            if ($reservaId) {
                $_SESSION['success'] = "Reserva realizada con éxito.";
                header("Location: index.php?controller=reserva&action=misReservas");
                exit();
            } else {
                $_SESSION['error'] = "Error al realizar la reserva.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            header("Location: index.php");
            exit();
        }
    }

    // Función para mostrar las reservas del usuario
    public function misReservas() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $usuarioId = 1; // Suponemos un usuario fijo por ahora
        $reservaDAO = new ReservasDAO(DataBase::connect());
        $reservas = $reservaDAO->obtenerReservasPorUsuario($usuarioId);

        require_once 'views/reservas/misReservas.php'; // Mostrar las reservas del usuario
    }

    // Función para anular una reserva
    public function anularReserva() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $id_reserva = filter_input(INPUT_GET, 'id_reserva', FILTER_VALIDATE_INT);
        if ($id_reserva) {
            $reservaDAO = new ReservasDAO(DataBase::connect());
            $reservaDAO->eliminarReserva($id_reserva);
            $_SESSION['success'] = "Reserva anulada con éxito.";
        } else {
            $_SESSION['error'] = "Reserva no válida.";
        }
        header("Location: index.php");
        exit();
    }

    // Función para modificar una reserva
    public function modificarReserva() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $id_reserva = filter_input(INPUT_GET, 'id_reserva', FILTER_VALIDATE_INT);
        if ($id_reserva) {
            // Obtener la reserva actual para modificar
            $reservaDAO = new ReservasDAO(DataBase::connect());
            $reserva = $reservaDAO->obtenerReservaPorId($id_reserva);

            // Mostrar el formulario de modificación con los datos actuales
            require_once 'views/reservas/modificarReserva.php'; // Vista para modificar la reserva
        } else {
            $_SESSION['error'] = "Reserva no válida.";
            header("Location: index.php");
            exit();
        }
    }

    // Función para actualizar una reserva modificada
    public function actualizarReserva() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $id_reserva = filter_input(INPUT_POST, 'id_reserva', FILTER_VALIDATE_INT);
        $fecha_reserva = filter_input(INPUT_POST, 'fecha_reserva', FILTER_SANITIZE_STRING);
        $personas = filter_input(INPUT_POST, 'personas', FILTER_VALIDATE_INT);

        // Validar la fecha con expresión regular (formato YYYY-MM-DD)
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_reserva)) {
            $_SESSION['error'] = "La fecha de la reserva no es válida.";
            header("Location: index.php?controller=reserva&action=misReservas");
            exit();
        }

        if ($id_reserva && $fecha_reserva && $personas) {
            $reservaDAO = new ReservasDAO(DataBase::connect());
            $reservaDAO->actualizarReserva($id_reserva, $fecha_reserva, $personas);
            $_SESSION['success'] = "Reserva modificada con éxito.";
            header("Location: index.php?controller=reserva&action=misReservas");
            exit();
        } else {
            $_SESSION['error'] = "Error al modificar la reserva.";
            header("Location: index.php?controller=reserva&action=misReservas");
            exit();
        }
    }
}
?>
