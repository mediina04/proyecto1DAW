<?php
// Iniciar la sesión para gestionar el carrito
session_start();

// Incluir las clases necesarias
require_once __DIR__ . '/../config/data_base.php';
require_once __DIR__ . '/../model/productosDAO.php';

// Verificar si se solicita eliminar un plato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plato'])) {
    $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
    if ($id_plato !== false && $id_plato > 0) {
        eliminarPlatoDeCesta($id_plato);
    } else {
        $_SESSION['error'] = "ID de plato inválido.";
    }
    header('Location: Cesta.php');
    exit;
}

// Función para eliminar un plato del carrito
function eliminarPlatoDeCesta($id_plato) {
    if (isset($_SESSION['carrito'][$id_plato])) {
        unset($_SESSION['carrito'][$id_plato]);
        $_SESSION['success'] = "";
    } else {
        $_SESSION['error'] = "El plato que intentas eliminar no existe en el carrito.";
    }
}

// Procesar las solicitudes de actualización de cantidad
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id_plato']) && isset($_GET['accion'])) {
    $id_plato = filter_input(INPUT_GET, 'id_plato', FILTER_VALIDATE_INT);
    $accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING); // '+' o '-'

    if ($id_plato && ($accion === '+' || $accion === '-')) {
        // Llamar al método en el controlador para actualizar la cantidad
        actualizarCantidad($id_plato, $accion);
    }

    // Redirigir a la misma página para ver los cambios
    header('Location: Cesta.php');
    exit;
}

// Función para actualizar la cantidad de un producto
function actualizarCantidad($id_plato, $accion) {
    if (isset($_SESSION['carrito'][$id_plato])) {
        // Obtener la cantidad actual
        $cantidad = $_SESSION['carrito'][$id_plato]['cantidad'];

        // Modificar la cantidad según la acción
        if ($accion === '+') {
            $cantidad++; // Incrementar la cantidad
        } elseif ($accion === '-') {
            $cantidad--; // Decrementar la cantidad
        }

        // Si la cantidad es 0 o menor, eliminar el producto del carrito
        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$id_plato]);
            $_SESSION['success'] = "Producto eliminado del carrito.";
        } else {
            // Actualizar la cantidad y subtotal si no se eliminó
            $_SESSION['carrito'][$id_plato]['cantidad'] = $cantidad;
            $_SESSION['carrito'][$id_plato]['subtotal'] = $cantidad * $_SESSION['carrito'][$id_plato]['precio'];
        }
    }
}



// Inicializar la variable $total y calcular el total del carrito
$total = 0;

if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    $productosDAO = new ProductosDAO(DataBase::connect());

    foreach ($_SESSION['carrito'] as $id_plato => &$producto) {
        $producto_db = $productosDAO->obtenerPorId($id_plato);

        if ($producto_db) {
            // Obtener los campos de la base de datos
            $producto['nombre'] = $producto_db->getNombre();
            $producto['descripcion'] = $producto_db->getDescripcion();
            $producto['precio'] = $producto_db->getPrecio();
            $producto['imagen'] = $producto_db->getImagenPrincipal(); // Imagen principal

            // Calcular el subtotal
            $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
            $total += $producto['subtotal'];
        } else {
            $producto['nombre'] = 'Producto desconocido';
            $producto['imagen'] = 'default.jpg'; // Imagen por defecto
        }
    }
    unset($producto); // Rompe la referencia para evitar problemas
}

// Regenerar el ID de sesión periódicamente para mayor seguridad
if (!isset($_SESSION['last_regeneration']) || time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <link rel="icon" href="Assets\IMG\ICONOS\HEADER\logo-polbeiro-head.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
    <!-- Encabezado -->
    <header class="header">
        <nav class="nav">
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

                <a href="Usuario.php">
                    <img src="assets/img/ICONOS/HEADER/icon-usuario.svg" alt="Usuario" class="icon">
                </a>
            </div>
        </nav>
    </header>

    <!-- Sección de la Cesta -->
    <main class="container">
        <?php if (!empty($_SESSION['carrito'])): ?>
            <h1 class="title">Cesta</h1>

            <!-- Mensajes de éxito o error -->
            <?php if (!empty($_SESSION['success'])): ?>
                <p class="success-message"><?= htmlspecialchars($_SESSION['success']) ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
                <p class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['carrito'] as $id_plato => $producto): ?>
                        <tr>
                            <!-- Imagen y detalles del producto -->
                            <td class="product-cell">
                                <div class="product">
                                    <!-- Imagen principal del producto -->
                                    <img src="Assets/IMG/PRODUCTOS/<?= htmlspecialchars($producto['imagen'] ?? 'default.jpg') ?>" 
                                        alt="<?= htmlspecialchars($producto['nombre'] ?? 'Producto desconocido') ?>" 
                                        class="product-image">
                                    <div class="product-details">
                                        <span class="product-name"><?= htmlspecialchars($producto['nombre'] ?? 'Producto desconocido') ?></span>
                                        <span class="product-description"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></span>
                                        <span class="product-price">€<?= number_format($producto['precio'], 2) ?></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Cantidad con botones -->
                            <td class="quantity-cell">
                                <div class="quantity-controls">
                                    <a href="Cesta.php?action=update&id_plato=<?= htmlspecialchars($id_plato) ?>&accion=-" class="quantity-button">-</a>
                                    <input type="number" value="<?= $producto['cantidad'] ?>" min="1" class="quantity-input" readonly />
                                    <a href="Cesta.php?action=update&id_plato=<?= htmlspecialchars($id_plato) ?>&accion=+" class="quantity-button">+</a>
                                </div>
                                <form method="POST" action="Cesta.php" class="delete-btn-container">
                                    <input type="hidden" name="id_plato" value="<?= htmlspecialchars($id_plato) ?>">
                                    <button type="submit" class="delete-btn">Quitar</button>
                                </form>
                            </td>

                            <!-- Precio total -->
                            <td class="total-cell">
                                <span>€<?= number_format($producto['subtotal'], 2) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            
            <!-- Resumen del carrito -->
            <div class="cart-summary">
                <p>Total: <span>€ <?= number_format($total, 2) ?></span></p>
                <p class="note">Impuesto incluidos.</p>
                <a class="checkout-btn" href="index.php?controller=pedido&action=crear"><span>FINALIZAR COMPRA</span></a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <h1>CESTA</h1>
                <p>La cesta está vacía</p>
                <button onclick="window.location.href='Nuestra-Carta.php'">SEGUIR COMPRANDO</button>
            </div>
        <?php endif; ?>
    </main>
    <script src="Assets/js/script.js"></script>
</body>
</html>
