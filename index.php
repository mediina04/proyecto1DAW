<?php

// Incluye los controladores necesarios
include_once("controllers/productoController.php");
include_once("controllers/UsuarioController.php");
include_once("controllers/pedidoController.php");
include_once("controllers/reservaController.php");
include_once("config/parameters.php");

// Debugging: Muestra los parámetros GET para verificar que se reciben correctamente
echo "<pre>";
var_dump($_GET);  // Muestra los parámetros GET
echo "</pre>";

// Redirigir a la URL por defecto si no se especifica controlador o acción
if (empty($_GET['controller']) || empty($_GET['action'])) {
    header('Location:' . url_base . '?controller=producto&action=index');
    exit; // Asegúrate de salir después de la redirección
}

// Sanitize and validate controller and action
$controller = $_GET['controller'] ?? 'producto';
$action = $_GET['action'] ?? 'index';

// Define el nombre del controlador a cargar
$controllerClass = $controller . "Controller";

// Comprobar si la clase existe
if (class_exists($controllerClass)) {
    // Crear una instancia del controlador
    $controllerObj = new $controllerClass();

    // Verificar si la acción solicitada existe en el controlador
    if (method_exists($controllerObj, $action)) {
        try {
            // Ejecutar la acción solicitada
            $controllerObj->$action();
        } catch (Exception $e) {
            // Manejo de errores: se captura y muestra el error
            echo "Error al ejecutar la acción: " . htmlspecialchars($e->getMessage());
        }
    } else {
        // Si la acción no existe, redirigir o mostrar error
        echo "La acción '" . htmlspecialchars($action) . "' no existe en el controlador '" . htmlspecialchars($controllerClass) . "'.";
    }
} else {
    // Si el controlador no existe, mostrar un mensaje de error
    echo "El controlador '" . htmlspecialchars($controllerClass) . "' no existe.";
}
?>
