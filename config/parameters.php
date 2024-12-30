<?php

define("default_action", "index"); // Acción por defecto del controlador para redirigir si no se especifica ninguna al index

// URL base para el proyecto (automáticamente detecta http o https y el dominio)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
define("url_base", "$protocol://$host$path/");
