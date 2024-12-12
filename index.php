<?php
include_once("controller/productoController.php");
include_once("controller/usuarioController.php");
include_once("config/parameters.php");

// Redirigir al inicio si no hay controller ni action
if (!isset($_GET['controller']) && !isset($_GET['action'])) {
    header('Location: views/Inicio.php');
    exit();
}

// Validar el controller
if (!isset($_GET['controller'])) {
    header('Location: views/Inicio.php');
    exit();
} else {
    $nombre_controller = ucfirst($_GET['controller']) . "Controller"; // Capitaliza el nombre
    
    // Verificar si el controlador existe
    if (class_exists($nombre_controller)) {
        $controller = new $nombre_controller();
        
        // Verificar si la acción existe
        $action = isset($_GET['action']) && method_exists($controller, $_GET['action']) 
            ? $_GET['action'] 
            : DEFAULT_ACTION;

        // Ejecutar la acción
        $controller->$action();
    } else {
        echo "No existe el controller: " . htmlspecialchars($nombre_controller);
    }
}
