<?php

// Incluye los controladores necesarios
include_once("controllers/productoController.php");
include_once("controllers/UsuarioController.php");
include_once("controllers/pedidoController.php");
include_once("controllers/reservaController.php");
include_once("config/parameters.php");

// Redirigir a la URL por defecto si no se especifica controlador o acción
if (empty($_GET['controller']) || empty($_GET['action'])) {
    header('Location: ' . url_base . '?controller=producto&action=index');
    exit;
}

// Sanitize and validate controller and action
$controller = isset($_GET['controller']) ? filter_var($_GET['controller'], FILTER_SANITIZE_STRING) : 'producto';
$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : 'index';

// Validar el controlador y la acción
$allowedControllers = ['producto', 'usuario', 'pedido', 'reserva']; // Puedes agregar más controladores aquí
$allowedActions = ['index', 'login', 'registrar', 'menu_usuario', 'cerrar_sesion', 'pedidos_info', 'panel_admin']; // Acciones permitidas

// Verificar si el controlador y la acción son válidos
if (!in_array($controller, $allowedControllers)) {
    die("Controlador no válido.");
}

if (!in_array($action, $allowedActions)) {
    die("Acción no válida.");
}

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
