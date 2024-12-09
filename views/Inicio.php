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
            <div class="carousel-item">VEN A DISFRUTAR DEL MEJOR PULPO DEL BAIX LLOBREGAT</div>
            <div class="carousel-item">DESCUBRE NUEVAS RECETAS CON EL SABOR GALLEGO</div>
            <div class="carousel-item">EVENTOS ESPECIALES Y PROMOCIONES EXCLUSIVAS</div>
        </div>
    </div>


  <!-- Sección de imágenes -->
    <div class="contenedor-slider">
        <!-- Diapositiva 1: Vídeo -->
        <div class="diapositiva activa">
            <video src="IMG/SLIDER/Slide-Polbeiro-1.mp4" autoplay muted loop></video>
        </div>
        
        <!-- Diapositiva 2: Imagen -->
        <div class="diapositiva">
            <img src="IMG/SLIDER/Slide-2-Polbeiro.png" alt="Imagen Total">
        </div>

        <!-- Diapositiva 3: Vídeo -->
        <div class="diapositiva">
            <video src="IMG/SLIDER/Slide-Polbeiro-3.mp4" autoplay muted loop></video>
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

    <!-- Sección de menú popular -->
    <section class="menu-section">
        <h2>THE MOST REQUESTED</h2>
        <div class="menu-gallery">
            <!-- Item 1 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/1. Pulpo Tapa.webp" alt="Tapa de Pulpo">
                <img src="IMG/PRODUCTOS/1.1 Pulpo Tapa.webp" alt="Tapa de Pulpo Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">TAPA DE PULPO</p>
                    <p class="price">€ 2,60</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 2 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/2. Pulpo Pincho.webp" alt="Brocheta de Pulpo">
                <img src="IMG/PRODUCTOS/2.1 Pulpo Pincho.webp" alt="Brocheta de Pulpo Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">BROCHETA DE PULPO</p>
                    <p class="price">€ 4,90</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 3 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/3. Pulpo Gallega.webp" alt="Pulpo a la Gallega">
                <img src="IMG/PRODUCTOS/3.1 Pulpo Gallega.webp" alt="Pulpo a la Gallega Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">PULPO A LA GALLEGA</p>
                    <p class="price">€ 5,60</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 4 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/4. Ensalada de Pulpo.webp" alt="Ensalada de Pulpo">
                <img src="IMG/PRODUCTOS/4.1 Ensalada de Pulpo.webp" alt="Ensalada de Pulpo Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">ENSALADA DE PULPO</p>
                    <p class="price">€ 4,75</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 5 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/5. Pulpo Poke.webp" alt="Pulpo Poke">
                <img src="IMG/PRODUCTOS/5.1 Pulpo Poke.webp" alt="Pulpo Poke Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">PULPO POKE</p>
                    <p class="price">€ 6,00</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 6 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/6. Pulpo Burger.webp" alt="Pulpo Burger">
                <img src="IMG/PRODUCTOS/6.1 Pulpo Burger.webp" alt="Pulpo Burger Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">PULPO BURGER</p>
                    <p class="price">€ 6,80</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 7 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/7. Pizza Pulpo.webp" alt="Pizza de Pulpo">
                <img src="IMG/PRODUCTOS/7.1 Pizza Pulpo.webp" alt="Pizza de Pulpo Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">PIZZA DE PULPO</p>
                    <p class="price">€ 8,50</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
            <!-- Item 8 -->
            <div class="menu-item">
                <span class="tag">MOST REQUESTED</span>
                <img src="IMG/PRODUCTOS/8. Nigiri Pulpo.webp" alt="Nigiri de Pulpo">
                <img src="IMG/PRODUCTOS/8.1 Nigiri Pulpo.webp" alt="Nigiri de Pulpo Hover" class="product-image-hover">
                <div class="product-details">
                    <p class="name">NIGIRI DE PULPO</p>
                    <p class="price">€ 6,20</p>
                    <button class="add-to-cart">AÑADIR AL CARRITO</button>
                </div>
            </div>
        </div>
    </section>
    
    <section class="restaurant">
        <div class="blackWhiteImg">
            <img src="IMG/img-restauranteBW.webp" alt="restauranteimg">
            <div class="overlay">
                <h2>DESCUBRE NUESTRO RESTAURANTE</h2>
                <a href="Restaurante.html" class="visit-button">VISITAR</a>
            </div>
        </div>
    </section>
    <section class="reservation">
        <h2 class="reservation-title">RESERVAR MESA</h2>
        <div class="reservation-form">
            <div class="input-group">
                <input type="text" id="name" required placeholder=" ">
                <label for="name">Nombre</label>
            </div>
            <div class="input-group">
                <input type="tel" id="phone" required placeholder=" ">
                <label for="phone">Teléfono</label>
            </div>
            <div class="input-group">
                <input type="number" id="people" min="1" required placeholder=" ">
                <label for="people">Personas</label>
            </div>
            <div class="input-group">
                <input type="time" id="hora" min="12:00" max="23:00" required placeholder=" ">
                <label for="hora">Hora</label>
            </div>
            <button type="submit" class="reservation-button">RESERVA</button>
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
    <script src="script.js"></script>                 
</body>
</html>
