<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Simulación del último pedido (puedes reemplazarlo con datos reales desde la base de datos)
$pedido = isset($_SESSION['pedido']) ? $_SESSION['pedido'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Usuario</title>
    <link rel="icon" href="Assets/IMG/ICONOS/HEADER/logo-polbeiro-head.svg" type="image/svg+xml">
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

    <!-- Panel de Usuario -->
    <div class="user-section">
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']->nombre); ?></h2>

            <!-- Formulario para editar datos -->
            <div class="edit-profile">
                <h3>Editar tus datos</h3>
                <form action="index.php?controller=usuario&action=actualizar_datos" method="POST">
                    <div class="input-Log-Sign">
                        <input type="text" name="name" id="name" required placeholder=" " value="<?php echo htmlspecialchars($_SESSION['usuario']->nombre); ?>">
                        <label for="name">Nombre</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="text" name="lastname" id="lastname" required placeholder=" " value="<?php echo htmlspecialchars($_SESSION['usuario']->apellido); ?>">
                        <label for="lastname">Apellido</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="email" name="email" id="email" required placeholder=" " value="<?php echo htmlspecialchars($_SESSION['usuario']->email); ?>">
                        <label for="email">Correo Electrónico</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="text" name="phone" id="phone" required placeholder=" " value="<?php echo htmlspecialchars($_SESSION['usuario']->telefono); ?>">
                        <label for="phone">Teléfono</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="text" name="address" id="address" required placeholder=" " value="<?php echo htmlspecialchars($_SESSION['usuario']->direccion); ?>">
                        <label for="address">Dirección</label>
                    </div>
                    <button type="submit" class="reservation-button">Guardar Cambios</button>
                </form>
            </div>

            <!-- Botón para cerrar sesión -->
            <div class="logout">
                <form action="index.php?controller=usuario&action=cerrar_sesion" method="POST">
                    <button type="submit" class="reservation-button">Cerrar Sesión</button>
                </form>
            </div>
        <?php else: ?>
            <h2>No has iniciado sesión</h2>
            <p>Para acceder a esta página, por favor, inicia sesión o regístrate.</p>
            <div class="auth-buttons">
                <a href="Login.php" class="button-web">Iniciar Sesión</a>
                <a href="Sign-Up.php" class="button-web">Registrarse</a>
            </div>
        <?php endif; ?>
    </div>

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
</body>
</html>
