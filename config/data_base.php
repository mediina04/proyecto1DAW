<?php

class DataBase {
    public static function connect($host = 'localhost', $user = 'root', $passwd = 'Asdqwe!23', $dbname = 'polbeiro') {
        $con = new mysqli($host, $user, $passwd, $dbname);
        if ($con ===false) {
            die("Error de conexión a la base de datos: " . mysqli_connect_error());
        };
        $con->set_charset('utf8mb4');
        return $con;
    }
}
