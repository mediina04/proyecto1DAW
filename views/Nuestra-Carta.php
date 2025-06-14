<?php
require_once '../config/data_base.php';  
require_once '../model/ProductosDAO.php';

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

} catch (PDOException $e) {
    // Manejo de errores de conexión
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    exit;
}

// Consulta SQL para obtener todos los platos
$sql = "SELECT id_plato, nombre, descripcion, precio, imagen_principal, imagen_secundaria FROM platos";
$stmt = $pdo->query($sql);

// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plato'])) {
    // Obtener el ID del plato desde el formulario y validarlo
    $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

    // Validar que el ID es válido y mayor que 0
    if ($id_plato !== false && $id_plato > 0) {
        // Verificar si el carrito ya está inicializado en la sesión
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Conectar a la base de datos y obtener el producto
        $conexion = DataBase::connect();
        $productosDAO = new ProductosDAO($conexion);
        $producto = $productosDAO->obtenerPorId($id_plato);

        // Verificar si el producto existe
        if ($producto) {
            // Si el producto ya está en el carrito, aumentar la cantidad
            if (isset($_SESSION['carrito'][$id_plato])) {
                $_SESSION['carrito'][$id_plato]['cantidad'] += 1;
                $_SESSION['carrito'][$id_plato]['subtotal'] = $_SESSION['carrito'][$id_plato]['producto']->getPrecio() * $_SESSION['carrito'][$id_plato]['cantidad'];
            } else {
                // Si el producto no está en el carrito, agregarlo
                $_SESSION['carrito'][$id_plato] = [
                    'imagen' => $producto->getImagenPrincipal() ?? 'default.jpg', // Imagen principal
                    'nombre' => $producto->getNombre(), // Nombre del producto
                    'descripcion' => $producto->getDescripcion(), // Descripción del producto
                    'precio' => $producto->getPrecio(), // Precio del producto
                    'producto' => $producto, // Objeto de producto (opcional)
                    'cantidad' => 1, // Iniciar con una cantidad de 1
                    'subtotal' => $producto->getPrecio() // Establecer el subtotal inicial
                ];
            }

            // Establecer mensaje de éxito en la sesión
            $_SESSION['success'] = "";
        } else {
            // Establecer mensaje de error si no se encuentra el producto
            $_SESSION['error'] = "Producto no encontrado en la base de datos.";
        }
    } else {
        // Establecer mensaje de error si el ID no es válido
        $_SESSION['error'] = "ID de producto no válido.";
    }
    
    // Redirigir o continuar según lo necesites
    header("Location: Nuestra-Carta.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polbeiro</title>
    <link rel="icon" href="Assets\IMG\ICONOS\HEADER\logo-polbeiro-head.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
    <!-- Encabezado -->
    <header class="header">
        <div class="nav">
            <ul class="menu">
                <li><a href="Inicio.php">INICIO</a></li>
                <li><a href="Nuestra-Carta.php">NUESTRA CARTA</a></li>
                <li><a href="Restaurante.php">RESTAURANTE</a></li>
                <li><a href="Contacto.php">CONTACTO</a></li>
            </ul>
            <div class="logo">
                <a href="Inicio.php">
                    <img src="assets/img/ICONOS/HEADER/logo-polbeiro.svg" alt="Logo Polbeiro">
                </a>
            </div>
            
            <div class="icons">

                <input type="checkbox" id="search-toggle" hidden>
                
                <!-- Etiqueta para el icono de la lupa -->
                <label for="search-toggle">
                    <img src="assets/img/ICONOS/HEADER/icon-lupa.svg" alt="Lupa" class="icon">
                </label>

                <a href="Cesta.php">
                    <img src="assets/img/ICONOS/HEADER/<?php echo (count($_SESSION['carrito']) > 0) ? 'icon-cesta-punto.svg' : 'icon-cesta.svg'; ?>" alt="Cesta" class="icon">
                </a>

                <a href="Info-Usuario.php">
                    <img src="assets/img/ICONOS/HEADER/icon-usuario.svg" alt="Usuario" class="icon">
                </a>
            </div>
        </div>
    </header>

    <!-- Banner de mensaje -->
    <div class="banner">
        <div class="carousel-text">
            <div class="carousel-item">
                <span>VEN A DISFRUTAR DEL MEJOR PULPO DEL BAIX LLOBREGAT</span>
            </div>
            <div class="carousel-item">
                <span>DESCUBRE NUEVAS RECETAS CON EL SABOR GALLEGO</span>
            </div>
            <div class="carousel-item">
                <span>EVENTOS ESPECIALES Y PROMOCIONES EXCLUSIVAS</span>
            </div>
        </div>
    </div>

    <!-- Sección de productos desde la base de datos -->
    <section class="menu-section">
        <h2>NUESTRA CARTA</h2>
        <div class="menu-gallery">
            <?php
            // Comprobar si hay resultados de la base de datos
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="menu-item">';
                    echo '<img src="' . $row['imagen_principal'] . '" alt="' . $row['nombre'] . '">';
                    echo '<img src="' . $row['imagen_secundaria'] . '" alt="' . $row['nombre'] . ' Hover" class="product-image-hover">';
                    echo '<div class="product-details">';
                    echo '<p class="name">' . $row['nombre'] . '</p>';
                    echo '<p class="price">€ ' . number_format($row['precio'], 2, ',', '.') . '</p>';

                    // Formulario para añadir al carrito
                    echo '<form method="POST">';
                    echo '<input type="hidden" name="id_plato" value="' . $row['id_plato'] . '">';
                    echo '<button type="submit" class="add-to-cart">AÑADIR AL CARRITO</button>';
                    echo '</form>';

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No se encontraron platos en la base de datos.";
            }
            ?>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-section">
                <h3>NO TE PIERDAS NUESTRAS OFERTAS</h3>
                <p>Suscríbete a nuestra newsletter y recibe tu primer pedido gratis</p>
                <form class="subscribe-form">
                    <div class="subscribe-input">
                        <input type="email" id="email" required placeholder=" ">
                        <label for="email">Correo electrónico</label>
                    </div>
                    <button class="button-footer">SUSCRIBIRSE</button>
    
                    <!-- Logo y redes sociales -->
                    <div class="logo">
                        <img src="assets/img/ICONOS/HEADER/logo-polbeiro.svg" alt="Polbeiro Logo">
                    </div>
                    
                    <div class="social-icons">
                        <a href="https://www.instagram.com"><img src="assets/img/ICONOS/REDES/icon-instagram.png" alt="Instagram"></a>
                        <a href="https://www.pinterest.com"><img src="assets/img/ICONOS/REDES/icon-pinterest.png" alt="Pinterest"></a>
                        <a href="https://www.youtube.com"><img src="assets/img/ICONOS/REDES/icon-youtube.png" alt="YouTube"></a>
                        <a href="https://www.tiktok.com"><img src="assets/img/ICONOS/REDES/icon-tiktok.png" alt="TikTok"></a>
                        <a href="https://www.whatsapp.com"><img src="assets/img/ICONOS/REDES/icon-whatsapp.png" alt="WhatsApp"></a>
                    </div>
                </form>
            </div>
    
            <!-- Las otras secciones del footer se quedan igual -->
            <div class="footer-section">
                <h3>OFERTAS ACTUALES</h3>
                <p>CÓDIGO: POLBEIRO (10%)</p>
            </div>
            
            <div class="footer-section">
                <h3>SUPPORT</h3>
                <p><a href="#">CONTACT</a></p>
                <p><a href="#">TRABAJA CON NOSOTROS</a></p>
            </div>
            
            <div class="footer-section">
                <h3>POLÍTICAS</h3>
                <p><a href="#">POLÍTICA DE PRIVACIDAD</a></p>
                <p><a href="#">POLÍTICA DE ENVÍO</a></p>
                <p><a href="#">COOKIES</a></p>
                <p><a href="#">TÉRMINOS Y CONDICIONES</a></p>
            </div>
        </div>
    </footer>
    
    <script src="Assets/js/script.js"></script>
</body>
</html>