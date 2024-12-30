<!-- Archivo para probar la conexion a la base de datos -->
<?php
require_once 'config/data_base.php';

$con = DataBase::connect();

$query = "SELECT nombre, descripcion FROM platos";

$result = $con->query($query);

$con->close();
?>

