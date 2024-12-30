<?php
// Aquí puedes agregar configuraciones o lógica PHP si es necesario, como variables dinámicas o datos de la base de datos.
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
    <section class="contact">
        <h2 class="contact-title">CONTACTA</h2>
        <p>Acogemos con satisfacción consultas, comentarios y sugerencias. ¡Queremos saber de ti!</p>
        
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-item">
                    <img src="Assets/IMG/ICONOS/CONTACTO/icon-mail.png" alt="Email" class="contact-icon">
                    <a href="mailto:polbeiroContacto@gmail.com">polbeiroContacto@gmail.com</a>
                </div>
                <div class="contact-item">
                    <img src="Assets/IMG/ICONOS/CONTACTO/icon-direccion.png" alt="Ubicación" class="contact-icon">
                    <p>Carrer Nostra Senyora de Lourdes, 34, Molins de Rei</p>
                </div>
                <div class="contact-item">
                    <img src="Assets/IMG/ICONOS/CONTACTO/icon-mobile.png" alt="Teléfono" class="contact-icon">
                    <p>+34 689 75 31 24</p>
                </div>
            </div>
    
            <form class="contact-form">
                <div class="contact-input">
                    <input type="text" id="name" placeholder=" " required>
                    <label for="name">Nombre</label>
                </div>
                <div class="contact-input">
                    <input type="tel" id="phone" placeholder=" " required>
                    <label for="phone">Teléfono</label>
                </div>
                <div class="contact-input">
                    <input type="email" id="email" placeholder=" " required>
                    <label for="email">Correo electrónico</label>
                </div>
                <div class="contact-input" id="message-group">
                    <textarea id="message" rows="3" placeholder=" " required></textarea>
                    <label for="message">Mensaje</label>
                </div>                
                <div class="button-container">
                    <button type="submit" class="contact-button"><span>CONTACTA</span></button>
                </div>                               
            </form>
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
                    
</body>
</html>
