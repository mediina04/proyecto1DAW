<?php
require_once 'model/reserva.php';
require_once 'model/reservasDAO.php';

class ReservaController {

    // Realizar una nueva reserva
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'];
            $fechaReserva = $_POST['fecha_reserva'];
            $cantidadPersonas = $_POST['cantidad_personas'];
            $comentarios = $_POST['comentarios'] ?? '';

            $reserva = new Reserva(null, $usuarioId, $fechaReserva, $cantidadPersonas, $comentarios);
            $reservaDAO = new ReservasDAO(DataBase::connect());

            if ($reservaDAO->guardar($reserva)) {
                echo "Reserva realizada con éxito.";
            } else {
                echo "Error al realizar la reserva.";
            }
        }
    }

    // Ver las reservas de un usuario
    public function historial() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $usuarioId = $_SESSION['usuario']->getId();
            $reservaDAO = new ReservasDAO(DataBase::connect());
            $reservas = $reservaDAO->obtenerReservasPorUsuario($usuarioId);
            require_once 'views/reservas/historial.php'; // Mostrar reservas
        }
    }
}
