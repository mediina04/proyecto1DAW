<?php

// Incluye los controladores necesarios
include_once("controller/ProductoController.php");
include_once("controller/UsuarioController.php");
include_once("controller/PedidoController.php");
include_once("controller/ReservaController.php");
include_once("controller/ApiController.php");
include_once("config/parameters.php");

// Si no se especifica controlador y acción, redirige a la página principal
if (!isset($_GET['controller']) && !isset($_GET['action'])) {
    header('Location: ' . url_base . '?controller=producto&action=index');
    exit;
}

if (!isset($_GET['controller'])) {
    header('Location: ' . url_base . '?controller=producto&action=index');
    exit;
}

$controller_nombre = $_GET['controller'] . "Controller";

if (class_exists($controller_nombre)) {
    $controllerObj = new $controller_nombre();

    if (isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])) {
        $action = $_GET["action"];
    } else {
        $action = defined('default_action') ? default_action : 'index';
    }

    $controllerObj->$action();
} else {
    echo "El controlador " . htmlspecialchars($controller_nombre) . " no existe.";
}
