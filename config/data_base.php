<?php

class DataBase {
    public static function connect($host = 'localhost', $port = 3306, $user = 'root', $passwd = 'Asdqwe!23', $dbname = 'polbeiro') {
        try {
            $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $passwd);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        } catch (PDOException $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage();
            exit;
        }
    }
}
