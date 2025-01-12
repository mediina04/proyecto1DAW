<?php

// Incluye los controladores necesarios
include_once("controller/ProductoController.php");
include_once("controller/UsuarioController.php");
include_once("controller/PedidoController.php");
include_once("controller/ReservaController.php");
include_once("controller/AdminController.php");
include_once("config/parameters.php");

if (!isset($_GET['controller']) || !isset($_GET['action'])) {
    header('Location: ' . url_base . '?controller=producto&action=index');
} else {
    if (isset($_GET['controller'])) {
        header('Location: ' . url_base . '?controller=producto&action=index');
    } else {
        if (isset($_GET['controller'])) {
            header('Location: ' . url_base . '?controller=producto&action=index');
        } else {
            $controller_nombre = $_GET['controller']."Controller";
            if (class_exists($controller_nombre)) {
                $controllerObj = new $controller_nombre();

                if (isset($_GET['action']) && (method_exists($controllerObj, $GET["action"]))) {
                    $action = $_GET["action"];
                } else {
                    $action = default_action;
               }
                    
                $controllerObj->$action();

            } else {
                echo "El controlador " . $controller_nombre . " no existe.";
            }
        }
    }
}
