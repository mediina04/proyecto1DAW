<?php

class DataBase {
    public static function connect($host = 'localhost', $user = 'root', $passwd = 'Asdqwe!23', $dbname = 'polbeiro') {
        $con = new mysqli($host, $user, $passwd, $dbname);

        // Verificar si hay errores en la conexión
        if ($con->connect_error) {
            die("Error de conexión a la base de datos: " . $con->connect_error);
        }

        return $con; // Devuelve la instancia de mysqli
    }
}
?>
