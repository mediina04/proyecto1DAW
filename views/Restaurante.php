<?php
// Aquí puedes agregar configuraciones o lógica PHP si es necesario, como variables dinámicas o datos de la base de datos.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polbeiro</title>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Encabezado -->
    <header class="header">
        <div class="nav">
            <ul class="menu">
                <li><a href="Inicio.html">INICIO</a></li>
                <li><a href="Nuestra-Carta.html">NUESTRA CARTA</a></li>
                <li><a href="Restaurante.html">RESTAURANTE</a></li>
                <li><a href="Contacto.html">CONTACTO</a></li>
            </ul>
            <div class="logo">
                <a href="Inicio.html">
                    <img src="IMG/ICONOS/HEADER/logo-polbeiro.png" alt="Logo Polbeiro">
                </a>
            </div>
            
            <div class="icons">
                <!-- Checkbox oculto para alternar la visibilidad del campo de búsqueda -->
                <input type="checkbox" id="search-toggle" hidden>
                
                <!-- Etiqueta para el icono de la lupa, actuará como botón -->
                <label for="search-toggle">
                    <img src="IMG/ICONOS/HEADER/icon-lupa.png" alt="Buscar" class="icon">
                </label>

                <a href="Cesta.html">
                    <img src="IMG/ICONOS/HEADER/icon-cesta.png" alt="Cesta" class="icon">
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
    <!-- Sección Sobre Nosotros -->
    <section class="sobre-nosotros">
        <div class="texto-sobre-nosotros">
            <h2>SOBRE NOSOTROS</h2>
            <p>En Polbeiro rendimos homenaje a la tradición gallega de un modo singular: el pulpo de las más variadas maneras. A base de un contexto rústico, con mesas de madera y paredes de piedra, créeme cuando te digo que podrás disfrutar de auténticos platos en un ambiente cálido y hogareño. Ven a descubrir nuestras recetas, preparadas con los ingredientes más frescos, para hacerte sentir en Galicia desde el primer bocado.</p>
        </div>
        <div class="imagen-sobre-nosotros">
            <img src="IMG/RESTAURANTE/sobre-nosotros.jpg" alt="Interior del restaurante Polbeiro">
        </div>
    </section>
    <section class="restaurante">
        <h2 class="restaurante-title">NUESTRO RESTAURANTE</h2>
        <div class="restaurante-gallery">
            <img src="IMG/RESTAURANTE/izq-arriba.jpg" alt="Vista del restaurante 1">
            <img src="IMG/RESTAURANTE/der-arriba.jpg" alt="Vista del restaurante 2">
            <img src="IMG/RESTAURANTE/izq-abajo.jpg" alt="Vista del restaurante 3">
            <img src="IMG/RESTAURANTE/der-abajo.jpg" alt="Vista del restaurante 4">
        </div>
    </section>
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-section">
                <h3>NO TE PIERDAS NUESTRAS OFERTAS</h3>
                <p>Suscríbete a nuestra newsletter y recibe tu primer pedido gratis</p>
                <form class="subscribe-form">
                    <div class="input-group">
                        <input type="email" id="email" required placeholder="">
                        <label for="email">Correo electrónico</label>
                    </div>
                    <button type="submit">SUSCRIBIRSE</button>
    
                    <!-- Logo y redes sociales -->
                    <div class="logo">
                        <img src="IMG/ICONOS/HEADER/logo-polbeiro.png" alt="Polbeiro Logo">
                    </div>
                    
                    <div class="social-icons">
                        <a href="#"><img src="IMG/ICONOS/REDES/icon-instagram.png" alt="Instagram"></a>
                        <a href="#"><img src="IMG/ICONOS/REDES/icon-pinterest.png" alt="Pinterest"></a>
                        <a href="#"><img src="IMG/ICONOS/REDES/icon-youtube.png" alt="YouTube"></a>
                        <a href="#"><img src="IMG/ICONOS/REDES/icon-tiktok.png" alt="TikTok"></a>
                        <a href="#"><img src="IMG/ICONOS/REDES/icon-whatsapp.png" alt="WhatsApp"></a>
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