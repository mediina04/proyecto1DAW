<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
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
                <a href="Usuario.php">
                    <img src="assets/img/ICONOS/HEADER/icon-usuario.svg" alt="Usuario" class="icon">
                </a>
            </div>
        </div>
    </header>

    <!-- Panel de Usuario -->
    <div class="user-section">
        <h2>Bienvenido, <?php echo $_SESSION['usuario']->getNombre(); ?></h2> <!-- Nombre dinámico del usuario -->
        
        <!-- Último pedido -->
        <?php if ($pedido): ?>
        <div class="last-order">
            <div class="order-details">
                <h3>Tu último pedido</h3>
                <p><strong>Pedido #<?php echo $pedido['id_pedido']; ?></strong></p>
                <p>Fecha: <?php echo $pedido['fecha']; ?></p>
                <p>Total: $<?php echo $pedido['total']; ?></p>
            </div>
            <form action="Cesta.php" method="GET">
                <button type="submit" class="reservation-button">Volver a pedir</button>
            </form>
        </div>
        <?php else: ?>
        <p>No tienes pedidos anteriores.</p>
        <?php endif; ?>
        
        <!-- Formulario para editar datos -->
        <div class="edit-profile">
            <h3>Editar tus datos</h3>
            <form action="index.php?controller=usuario&action=actualizar_datos" method="POST" id="editProfileForm">
                <div class="input-Log-Sign">
                    <input type="text" name="name" id="name" required placeholder=" " value="<?php echo $_SESSION['usuario']->getNombre(); ?>">
                    <label for="name">Nombre</label>
                </div>
                <div class="input-Log-Sign">
                    <input type="text" name="lastname" id="lastname" required placeholder=" " value="<?php echo $_SESSION['usuario']->getApellido(); ?>">
                    <label for="lastname">Apellido</label>
                </div>
                <div class="input-Log-Sign">
                    <input type="email" name="email" id="email" required placeholder=" " value="<?php echo $_SESSION['usuario']->getEmail(); ?>">
                    <label for="email">Correo Electrónico</label>
                </div>
                <div class="input-Log-Sign">
                    <input type="number" name="phone" id="phone" required placeholder=" " value="<?php echo $_SESSION['usuario']->getTelefono(); ?>">
                    <label for="phone">Teléfono</label>
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
