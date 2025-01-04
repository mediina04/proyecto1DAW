<?php
session_start();

require_once __DIR__ . '/../config/data_base.php';
require_once __DIR__ . '/../model/ProductosDAO.php';
require_once __DIR__ . '/../model/PedidosDAO.php';

const ERROR_INVALID_ID = "";
const ERROR_NOT_FOUND = "";
const SUCCESS_REMOVE = "";



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plato'])) {
    $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);
    
    if ($id_plato) {
        eliminarPlatoDeCesta($id_plato);
    } else {
        setSessionMessage('error', ERROR_INVALID_ID);
    }

    redirect('Cesta.php');
}

// Lógica para actualizar la cantidad de un producto
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'], $_GET['id_plato'], $_GET['accion']) && $_GET['action'] === 'update') {
    $id_plato = filter_input(INPUT_GET, 'id_plato', FILTER_VALIDATE_INT);
    $accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING);

    if ($id_plato && ($accion === '+' || $accion === '-')) {
        actualizarCantidad($id_plato, $accion);
    }

    // Redirige a la misma página para reflejar los cambios en el carrito
    redirect('Cesta.php');
}


function eliminarPlatoDeCesta(int $id_plato): void {
    if (isset($_SESSION['carrito'][$id_plato])) {
        unset($_SESSION['carrito'][$id_plato]);
        setSessionMessage('success', SUCCESS_REMOVE);
    } else {
        setSessionMessage('error', ERROR_NOT_FOUND);
    }
}

function actualizarCantidad(int $id_plato, string $accion): void {
    if (isset($_SESSION['carrito'][$id_plato])) {
        $cantidad = $_SESSION['carrito'][$id_plato]['cantidad'];

        // Actualizar la cantidad según la acción
        if ($accion === '+') {
            $cantidad += 1;
        } elseif ($accion === '-') {
            $cantidad -= 1;
        }

        // Si la cantidad es menor o igual a 0, eliminar el producto del carrito
        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$id_plato]);
            setSessionMessage('success', SUCCESS_REMOVE);
        } else {
            $_SESSION['carrito'][$id_plato]['cantidad'] = $cantidad;
            $_SESSION['carrito'][$id_plato]['subtotal'] = $cantidad * $_SESSION['carrito'][$id_plato]['precio'];
        }
    }
}


function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function setSessionMessage(string $key, string $message): void {
    $_SESSION[$key] = $message;
}

// Calcular el total del carrito
$total = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    $productosDAO = new ProductosDAO(DataBase::connect());

    foreach ($_SESSION['carrito'] as $id_plato => &$producto) {
        $producto_db = $productosDAO->obtenerPorId($id_plato);
    
        if ($producto_db) {
            $producto['nombre'] = $producto_db->getNombre();
            $producto['descripcion'] = $producto_db->getDescripcion();
            $producto['precio'] = $producto_db->getPrecio();
            $producto['imagen'] = $producto_db->getImagenPrincipal();
            $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
            $total += $producto['subtotal'];
        } else {
            $producto['nombre'] = 'Producto desconocido';
        }
    }
    
    unset($producto);
}

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
    <link rel="icon" href="Assets/IMG/ICONOS/HEADER/logo-polbeiro-head.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
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
    </nav>
</header>

<main class="container">
    <?php if (!empty($_SESSION['carrito'])): ?>
        <h1 class="title">Cesta</h1>
        <?php if (!empty($_SESSION['success'])): ?>
            <p class="success-message"> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?> </p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="error-message"> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?> </p>
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
                        <td class="product-cell">
                            <div class="product">
                                <img src="proyecto1DAW/views/Assets/IMG/PRODUCTOS/<?= htmlspecialchars($producto['imagen']) ?> "class="product-image">
                                <div class="product-details">
                                    <span class="product-name"> <?= htmlspecialchars($producto['nombre']); ?> </span>
                                    <span class="product-description"> <?= htmlspecialchars($producto['descripcion'] ?? ''); ?> </span>
                                    <span class="product-price">&euro;<?= number_format($producto['precio'], 2); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="quantity-cell">
    <div class="quantity-controls">
        <!-- Botón de decremento -->
        <a href="Cesta.php?action=update&id_plato=<?= htmlspecialchars($id_plato) ?>&accion=-" class="quantity-button">-</a>
        
        <!-- Input de cantidad -->
        <input type="number" value="<?= $producto['cantidad'] ?>" min="1" class="quantity-input" readonly />
        
        <!-- Botón de incremento -->
        <a href="Cesta.php?action=update&id_plato=<?= htmlspecialchars($id_plato) ?>&accion=+" class="quantity-button">+</a>
    </div>
    <!-- Formulario para eliminar producto -->
    <form method="POST" action="Cesta.php" class="delete-btn-container">
        <input type="hidden" name="id_plato" value="<?= htmlspecialchars($id_plato); ?>">
        <button type="submit" class="delete-btn">Quitar</button>
    </form>
</td>

                        <td class="total-cell">&euro;<?= number_format($producto['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <p>Total: <span>&euro; <?= number_format($total, 2); ?></span></p>
            <p class="note">Impuesto incluidos.</p>
            <a class="checkout-btn" href="index.php?controller=pedido&action=crear">FINALIZAR COMPRA</a>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <h1>CESTA</h1>
            <p>La cesta está vacía.</p>
            <button onclick="window.location.href='Nuestra-Carta.php'">SEGUIR COMPRANDO</button>
        </div>
    <?php endif; ?>
</main>
<script src="Assets/js/script.js"></script>
</body>
</html>
