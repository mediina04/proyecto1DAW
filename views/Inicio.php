<?php
// Incluir la clase DataBase para la conexión a la base de datos
require_once '../config/data_base.php';
require_once '../model/ProductosDAO.php';
require_once __DIR__ . '/../model/reservasDAO.php';

session_start();  // Iniciar la sesión si no está iniciada

// Configuración de conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'polbeiro';
$username = 'root';
$password = 'Asdqwe!23'; // Cambiar si es necesario

try {
    // Crear la conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    exit;
}

// Consulta SQL para obtener 8 productos al azar de la tabla `platos`
$sql = "SELECT id_plato, nombre, descripcion, precio, imagen_principal, imagen_secundaria FROM platos ORDER BY RAND() LIMIT 8";
$stmt = $pdo->query($sql);

// Verificar si el formulario de reserva ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $reservaDAO = new ReservasDAO(DataBase::connect());
    $usuarioId = 1; // Simulando un usuario fijo

    if ($_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $personas = $_POST['personas'];
        $fecha_reserva = $_POST['hora']; // Fecha en formato string

        // Crear la reserva
        $reserva = new Reserva(null, $usuarioId, $fecha_reserva, $personas, $nombre, $telefono);

        // Insertar la reserva en la base de datos
        $reservaDAO->crearReserva($reserva);

        // Mensaje de éxito
        $_SESSION['success'] = "Mesa reservada con éxito.";
        header('Location: Inicio.php');
        exit();
    } elseif ($_POST['accion'] == 'anular') {
        // Anular la reserva
        $id_reserva = $_POST['id_reserva'];
        $reservaDAO->eliminarReserva($id_reserva);

        // Mensaje de anulación
        $_SESSION['success'] = "Reserva anulada.";
        header('Location: Inicio.php');
        exit();
    } elseif ($_POST['accion'] == 'modificar') {
        // Modificar la reserva
        $id_reserva = $_POST['id_reserva'];
        $personas = $_POST['personas'];
        $fecha_reserva = $_POST['hora'];

        // Actualizar en la base de datos
        $reservaDAO->actualizarReserva($id_reserva, $fecha_reserva, $personas);

        // Mensaje de éxito
        $_SESSION['success'] = "Reserva modificada con éxito.";
        header('Location: Inicio.php');
        exit();
    }
}

// Obtener la reserva activa para el usuario (si existe)
$reservaDAO = new ReservasDAO(DataBase::connect());
$reservasUsuario = $reservaDAO->obtenerReservasPorUsuario(1); // Usuario fijo


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
                    <img src="assets/img/ICONOS/HEADER/icon-lupa.png" alt="Buscar" class="icon">
                </label>

                <a href="Cesta.php">
                    <img src="assets/img/ICONOS/HEADER/icon-cesta.png" alt="Cesta" class="icon">
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
            <div class="carousel-item">VEN A DISFRUTAR DEL MEJOR PULPO DEL BAIX LLOBREGAT</div>
            <div class="carousel-item">DESCUBRE NUEVAS RECETAS CON EL SABOR GALLEGO</div>
            <div class="carousel-item">EVENTOS ESPECIALES Y PROMOCIONES EXCLUSIVAS</div>
        </div>
    </div>

    <!-- Sección de imágenes -->
    <div class="contenedor-slider">
        <!-- Diapositiva 1: Vídeo -->
        <div class="diapositiva activa">
            <video src="Assets/IMG/SLIDER/Slide-Polbeiro-1.mp4" autoplay muted loop></video>
        </div>
        
        <!-- Diapositiva 2: Imagen -->
        <div class="diapositiva">
            <img src="Assets/IMG/SLIDER/Slide-2-Polbeiro.png" alt="Slide-2-Polbeiro" />
        </div>

        <!-- Diapositiva 3: Vídeo -->
        <div class="diapositiva">
            <video src="Assets/IMG/SLIDER/Slide-Polbeiro-3.mp4" autoplay muted loop></video>
        </div>

        <!-- Botones de navegación con círculos de progreso -->
        <div class="puntos-slider">
            <svg class="punto" onclick="irADiapositiva(0)" viewBox="0 0 100 100">
                <circle cx="25" cy="25" r="20" stroke="#e0e0e0" stroke-width="8" fill="transparent" />
                <circle cx="25" cy="25" r="20" stroke="#fff" stroke-width="8" fill="transparent" class="progreso" />
            </svg>
            <svg class="punto" onclick="irADiapositiva(1)" viewBox="0 0 100 100">
                <circle cx="25" cy="25" r="20" stroke="#e0e0e0" stroke-width="8" fill="transparent" />
                <circle cx="25" cy="25" r="20" stroke="#fff" stroke-width="8" fill="transparent" class="progreso" />
            </svg>
            <svg class="punto" onclick="irADiapositiva(2)" viewBox="0 0 100 100">
                <circle cx="25" cy="25" r="20" stroke="#e0e0e0" stroke-width="8" fill="transparent" />
                <circle cx="25" cy="25" r="20" stroke="#fff" stroke-width="8" fill="transparent" class="progreso" />
            </svg>
        </div>
    </div>

    <!-- Sección de productos -->
    <section class="menu-section">
        <h2>THE MOST REQUESTED</h2>
        <div class="menu-gallery">
            <?php

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="menu-item">';
                    echo '<img src="' . $row['imagen_principal'] . '" alt="' . $row['nombre'] . '">';
                    echo '<img src="' . $row['imagen_secundaria'] . '" alt="' . $row['nombre'] . ' Hover" class="product-image-hover">';
                    echo '<div class="product-details">';
                    echo '<p class="name">' . $row['nombre'] . '</p>';
                    echo '<p class="price">€ ' . number_format($row['precio'], 2, ',', '.') . '</p>';

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


    <section class="restaurant">
        <div class="blackWhiteImg">
            <img src="Assets/IMG/img-restauranteBW.webp" alt="restauranteimg">
            <div class="overlay">
                <h2>DESCUBRE NUESTRO RESTAURANTE</h2>
                <a href="Restaurante.php" class="visit-button">VISITAR</a>
            </div>
        </div>
    </section>
    
    <!-- Sección de Reservas -->
    <section class="reservation">
    <h2 class="reservation-title">RESERVAR MESA</h2>
    <div class="reservation-form">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($reservasUsuario)): ?>
            <!-- Mostrar detalles de la reserva -->
            <h3>Tienes una reserva activa</h3>
            <p><strong>Fecha y hora:</strong> <?php echo htmlspecialchars($reservasUsuario[0]->getFechaReserva()); ?></p>
            <p><strong>Cantidad de personas:</strong> <?php echo htmlspecialchars($reservasUsuario[0]->getCantidadPersonas()); ?></p>
            <p><strong>Comentarios:</strong> <?php echo htmlspecialchars($reservasUsuario[0]->getComentarios()); ?></p>
            
            <!-- Botón para modificar la reserva -->
            <form method="POST" action="Inicio.php" style="display: inline-block;">
                <input type="hidden" name="accion" value="modificar">
                <input type="hidden" name="id_reserva" value="<?php echo $reservasUsuario[0]->getIdReserva(); ?>">
                <button type="submit" class="btn-modificar">Modificar Reserva</button>
            </form>

            <!-- Botón para anular la reserva -->
            <form method="POST" action="Inicio.php" style="display: inline-block;">
                <input type="hidden" name="accion" value="anular">
                <input type="hidden" name="id_reserva" value="<?php echo $reservasUsuario[0]->getIdReserva(); ?>">
                <button type="submit" class="btn-anular">Anular Reserva</button>
            </form>
        <?php else: ?>
            <!-- Formulario para crear una reserva -->
            <form method="POST" action="Inicio.php" class="reservation-container">
                <input type="hidden" name="accion" value="crear">
                
                <div class="reserva-form">
                    <input type="text" name="nombre" required placeholder="">
                    <label for="name">Nombre</label>
                </div>
                <div class="reserva-form">
                    <input type="tel" name="telefono" required placeholder="">
                    <label for="phone">Teléfono</label>
                </div>
                <div class="reserva-form">
                    <input type="number" name="personas" min="1" required placeholder="">
                    <label for="people">Personas</label>
                </div>
                <div class="reserva-form">
                    <input type="time" min="12:00" max="17:00" name="hora" required placeholder="">
                    <label for="hora">Hora</label>
                </div>
                <button type="submit" class="reservation-button">RESERVAR</button>
            </form>
        <?php endif; ?>
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
                    <button type="submit">SUSCRIBIRSE</button>
    
                    <!-- Logo y redes sociales -->
                    <div class="logo">
                        <img src="assets/img/ICONOS/HEADER/logo-polbeiro.svg" alt="Polbeiro Logo">
                    </div>
                    
                    <div class="social-icons">
                        <a href="#"><img src="assets/img/ICONOS/REDES/icon-instagram.png" alt="Instagram"></a>
                        <a href="#"><img src="assets/img/ICONOS/REDES/icon-pinterest.png" alt="Pinterest"></a>
                        <a href="#"><img src="assets/img/ICONOS/REDES/icon-youtube.png" alt="YouTube"></a>
                        <a href="#"><img src="assets/img/ICONOS/REDES/icon-tiktok.png" alt="TikTok"></a>
                        <a href="#"><img src="assets/img/ICONOS/REDES/icon-whatsapp.png" alt="WhatsApp"></a>
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
