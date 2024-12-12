<?php

// Acción por defecto si no se pasa un parámetro 'action'
define("DEFAULT_ACTION", "index");

// URL base para el proyecto (automáticamente detecta http o https y el dominio)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
define("URL_BASE", "$protocol://$host$path/");

