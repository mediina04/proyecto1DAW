<?php
// Configuración de conexión a la base de datos
$host = '127.0.0.1'; // Dirección IP del servidor de MySQL
$dbname = 'polbeiro'; // Nombre de la base de datos
$username = 'root'; // Usuario
$password = 'Asdqwe!23'; // Contraseña

try {
    // Crear la conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configuración de los atributos de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mensaje de éxito
    echo "¡Conexión exitosa a la base de datos!<br>";
} catch (PDOException $e) {
    // Manejo de errores de conexión
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    exit;
}

?>
