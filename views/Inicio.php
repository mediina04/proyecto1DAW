<?php
require_once __DIR__ . '/../config/data_base.php';
require_once __DIR__ . '/../model/ProductosDAO.php';
require_once __DIR__ . '/../model/ReservasDAO.php';

session_start(); // Iniciar la sesión si no está iniciada

// Crear la conexión con la base de datos
$con = DataBase::connect();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Verificar si el formulario de añadir a la cesta ha sido enviado
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
                    'imagen' => $producto->getImagenPrincipal() ?? 'default.jpg', // Agregar imagen principal
                    'nombre' => $producto->getNombre(), // Nombre del producto
                    'descripcion' => $producto->getDescripcion(), // Descripción del producto
                    'precio' => $producto->getPrecio(), // Precio del producto
                    'producto' => $producto, // Objeto de producto (opcional si es necesario)
                    'cantidad' => 1,
                    'subtotal' => $producto->getPrecio()
                ];
            }

            // Establecer mensaje de éxito en la sesión
            $_SESSION['success'] = "";
        } else {
            // Establecer mensaje de error si no se encuentra el producto
            $_SESSION['error'] = "";
        }
    } else {
        // Establecer mensaje de error si el ID no es válido
        $_SESSION['error'] = "";
    }

    header('Location: Inicio.php');
    exit();
}

// Consulta SQL para obtener 8 productos al azar de la tabla `platos`
$query = "SELECT id_plato, nombre, descripcion, precio, imagen_principal, imagen_secundaria FROM platos ORDER BY RAND() LIMIT 8";
$result = $con->query($query);

$productos = [];
if ($result && $result->num_rows > 0) {
    $productos = $result->fetch_all(MYSQLI_ASSOC);
}

// Verificar si el formulario de reserva ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $usuarioId = 1; // Simulando un usuario fijo

    if ($_POST['accion'] == 'crear') {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $personas = $_POST['personas'];
        $fecha_reserva = $_POST['hora'];

        $query = "INSERT INTO reservas (id_usuario, fecha_reserva, cantidad_personas, comentarios, telefono) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('isiss', $usuarioId, $fecha_reserva, $personas, $nombre, $telefono);
        $stmt->execute();


        $_SESSION['success'] = "Mesa reservada con éxito.";
        header('Location: Inicio.php');
        exit();
    } elseif ($_POST['accion'] == 'anular') {
        $id_reserva = $_POST['id_reserva'];

        $query = "DELETE FROM reservas WHERE id_reserva = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_reserva);
        $stmt->execute();


        $_SESSION['success'] = "Reserva anulada.";
        header('Location: Inicio.php');
        exit();
    } elseif ($_POST['accion'] == 'modificar') {
        $id_reserva = $_POST['id_reserva'];
        $personas = $_POST['personas'];
        $fecha_reserva = $_POST['hora'];

        $query = "UPDATE reservas SET fecha_reserva = ?, cantidad_personas = ? WHERE id_reserva = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('sii', $fecha_reserva, $personas, $id_reserva);
        $stmt->execute();


        $_SESSION['success'] = "Reserva modificada con éxito.";
        header('Location: Inicio.php');
        exit();
    }
}

// Obtener la reserva activa para el usuario
$query = "SELECT * FROM reservas WHERE id_usuario = ? ORDER BY fecha_reserva DESC LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $usuarioId);
$stmt->execute();
$reservasUsuario = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polbeiro</title>
    <link rel="icon" href="assets\IMG\ICONOS\HEADER\logo-polbeiro-head.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
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
            <video src="assets/IMG/SLIDER/Slide-Polbeiro-1.mp4" autoplay muted loop></video>
        </div>
        
        <!-- Diapositiva 2: Imagen -->
        <div class="diapositiva">
            <img src="assets/IMG/SLIDER/Slide-2-Polbeiro.png" alt="Slide-2-Polbeiro" />
        </div>

        <!-- Diapositiva 3: Vídeo -->
        <div class="diapositiva">
            <video src="assets/IMG/SLIDER/Slide-Polbeiro-3.mp4" autoplay muted loop></video>
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
        // Verificar si hay resultados en la consulta
        if (!empty($productos)) { // Usamos la variable $productos previamente obtenida
            foreach ($productos as $row) {
                echo '<div class="menu-item">';
                echo '<img src="' . $row['imagen_principal'] . '" alt="' . $row['nombre'] . '">';
                echo '<img src="' . $row['imagen_secundaria'] . '" alt="' . $row['nombre'] . ' Hover" class="product-image-hover">';
                echo '<div class="product-details">';
                echo '<p class="name">' . $row['nombre'] . '</p>';
                echo '<p class="price">€ ' . number_format($row['precio'], 2, ',', '.') . '</p>';

                // Formulario para añadir a la cesta
                echo '<form method="POST">';
                echo '<input type="hidden" name="id_plato" value="' . $row['id_plato'] . '">';
                echo '<input type="hidden" name="accion" value="add_to_cart">';
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
            <img src="assets/IMG/img-restauranteBW.webp" alt="restauranteimg">
            <div class="overlay">
                <h2>DESCUBRE NUESTRO RESTAURANTE</h2>
                <a href="Restaurante.php" class="button-web">VISITAR</a>
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
            <p><strong>Fecha y hora:</strong> <?php echo htmlspecialchars($reservasUsuario[0]['fecha_reserva']); ?></p>
            <p><strong>Cantidad de personas:</strong> <?php echo htmlspecialchars($reservasUsuario[0]['cantidad_personas']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($reservasUsuario[0]['comentarios']); ?></p>

            <!-- Botón para modificar la reserva -->
            <form method="POST" action="Inicio.php" style="display: inline-block;">
                <input type="hidden" name="accion" value="modificar">
                <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reservasUsuario[0]['id_reserva']); ?>">

                <div class="reserva-form">
                    <label for="personas">Personas</label>
                    <input type="number" name="personas" min="1" value="<?php echo htmlspecialchars($reservasUsuario[0]['cantidad_personas']); ?>" required>
                </div>
                <div class="reserva-form">
                    <label for="hora">Fecha y Hora</label>
                    <input type="time" name="hora" value="<?php echo htmlspecialchars($reservasUsuario[0]['fecha_reserva']); ?>" required>
                </div>
                <button type="submit" class="button-web">Modificar Reserva</button>
            </form>

            <!-- Botón para anular la reserva -->
            <form method="POST" action="Inicio.php" style="display: inline-block;">
                <input type="hidden" name="accion" value="anular">
                <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reservasUsuario[0]['id_reserva']); ?>">
                <button type="submit" class="button-web">Anular Reserva</button>
            </form>
        <?php else: ?>
            <!-- Formulario para crear una reserva -->
            <form method="POST" action="Inicio.php" class="reservation-container">
                <input type="hidden" name="accion" value="crear">

                <div class="reserva-form">
                    <input type="text" name="nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required placeholder="">
                    <label for="name">Nombre</label>
                </div>
                <div class="reserva-form">
                    <input type="tel" name="telefono" value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>" required placeholder="">
                    <label for="phone">Teléfono</label>
                </div>
                <div class="reserva-form">
                    <input type="number" name="personas" min="1" value="<?php echo isset($_POST['personas']) ? htmlspecialchars($_POST['personas']) : ''; ?>" required placeholder="">
                    <label for="people">Personas</label>
                </div>
                <div class="reserva-form">
                    <input type="time" name="hora" value="<?php echo isset($_POST['hora']) ? htmlspecialchars($_POST['hora']) : ''; ?>" required placeholder="">
                    <label for="hora">Hora</label>
                </div>
                <button type="submit" class="button-web">RESERVAR</button>
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
    <script src="assets/js/script.js"></script>
</body>
</html>
