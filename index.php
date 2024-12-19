<?php
// Incluir los controladores necesarios
include_once("controller/productoController.php");
include_once("controller/usuarioController.php");
include_once("controller/pedidoController.php");
include_once("controller/reservaController.php"); // Asegúrate de incluir el controlador de reservas
include_once("config/parameters.php");

// Redirigir al inicio si no hay controller ni action
if (!isset($_GET['controller']) && !isset($_GET['action'])) {
    header('Location: views/Inicio.php');
    exit();
}

// Validar el controller
if (!isset($_GET['controller']) || empty($_GET['controller'])) {
    // Si no existe el controller, redirigir al inicio
    header('Location: views/Inicio.php');
    exit();
} else {
    $nombre_controller = ucfirst($_GET['controller']) . "Controller"; // Capitaliza el nombre del controller
    
    // Verificar si el controlador existe
    if (class_exists($nombre_controller)) {
        // Crear instancia del controlador
        $controller = new $nombre_controller();
        
        // Verificar si la acción existe
        $action = isset($_GET['action']) && method_exists($controller, $_GET['action']) 
            ? $_GET['action'] 
            : DEFAULT_ACTION;  // Utiliza la acción por defecto si no se especifica una
        
        // Ejecutar la acción correspondiente
        try {
            $controller->$action();
        } catch (Exception $e) {
            // Si ocurre un error en la ejecución de la acción, mostrar un mensaje
            echo "Error al ejecutar la acción: " . htmlspecialchars($e->getMessage());
        }
    } else {
        // Si no existe el controlador, mostrar un mensaje de error
        echo "No existe el controlador: " . htmlspecialchars($nombre_controller);
    }
}
?>
