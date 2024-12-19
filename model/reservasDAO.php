<?php
require_once 'reserva.php';

class ReservasDAO {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Crear una reserva en la base de datos
    public function crearReserva($reserva) {
        try {
            // Preparar la consulta SQL para insertar una nueva reserva
            $sql = "INSERT INTO reservas (id_usuario, fecha_reserva, cantidad_personas, comentarios) 
                    VALUES (?, ?, ?, ?)";
            
            // Preparar la sentencia
            $stmt = $this->db->prepare($sql);
            
            // Verificar qué datos estamos intentando insertar
            error_log("Datos de la reserva a insertar: " . 
                      $reserva->getIdUsuario() . " - " .
                      $reserva->getFechaReserva() . " - " .
                      $reserva->getCantidadPersonas() . " - " .
                      $reserva->getComentarios());
            
            // Ejecutar la sentencia con los datos de la reserva
            $stmt->execute([
                $reserva->getIdUsuario(),
                $reserva->getFechaReserva(),
                $reserva->getCantidadPersonas(),
                $reserva->getComentarios()
            ]);
    
            // Obtener el ID de la reserva recién insertada
            $lastInsertId = $this->db->lastInsertId();
    
            // Verificar si el ID fue generado correctamente
            error_log("ID de la reserva insertada: " . $lastInsertId);
    
            return $lastInsertId; // Esto debería devolver el ID generado automáticamente
        } catch (PDOException $e) {
            // Si hay un error en la ejecución, lo mostramos
            error_log("Error al crear la reserva: " . $e->getMessage());
            return false; // Indicamos que la inserción falló
        }
    }
    
    
    

    // Obtener todas las reservas de un usuario
    public function obtenerReservasPorUsuario($id_usuario) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_usuario = ? ORDER BY fecha_reserva DESC");
            $stmt->execute([$id_usuario]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convertir cada fila del resultado en un objeto Reserva
            $reservas = [];
            foreach ($result as $reservaData) {
                $reservas[] = new Reserva(
                    $reservaData['id_reserva'],
                    $reservaData['id_usuario'],
                    $reservaData['fecha_reserva'],
                    $reservaData['cantidad_personas'],
                    $reservaData['comentarios']
                );
            }

            return $reservas;
        } catch (PDOException $e) {
            echo "Error al obtener las reservas del usuario: " . $e->getMessage();
            return [];
        }
    }

    // Eliminar una reserva
    public function eliminarReserva($id_reserva) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reservas WHERE id_reserva = ?");
            $stmt->execute([$id_reserva]);
            return true; // Indicamos que la eliminación fue exitosa
        } catch (PDOException $e) {
            echo "Error al eliminar la reserva: " . $e->getMessage();
            return false;
        }
    }

    // Obtener una reserva por su ID
    public function obtenerReservaPorId($id_reserva) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reservas WHERE id_reserva = ?");
            $stmt->execute([$id_reserva]);
            $reservaData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reservaData) {
                // Convertir la fila en un objeto Reserva
                return new Reserva(
                    $reservaData['id_reserva'],
                    $reservaData['id_usuario'],
                    $reservaData['fecha_reserva'],
                    $reservaData['cantidad_personas'],
                    $reservaData['comentarios']
                );
            } else {
                return null; // No se encuentra la reserva
            }
        } catch (PDOException $e) {
            echo "Error al obtener la reserva por ID: " . $e->getMessage();
            return null;
        }
    }

    // Actualizar una reserva
    public function actualizarReserva($id_reserva, $fecha_reserva, $personas) {
        try {
            $stmt = $this->db->prepare("UPDATE reservas SET fecha_reserva = ?, cantidad_personas = ? WHERE id_reserva = ?");
            $stmt->execute([$fecha_reserva, $personas, $id_reserva]);
            return true; // Indicamos que la actualización fue exitosa
        } catch (PDOException $e) {
            echo "Error al actualizar la reserva: " . $e->getMessage();
            return false;
        }
    }
}
?>
