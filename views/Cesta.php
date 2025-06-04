<?php
session_start();

require_once __DIR__ . '/../config/data_base.php';
require_once __DIR__ . '/../model/ProductosDAO.php';
require_once __DIR__ . '/../model/PedidosDAO.php';
include_once __DIR__ . '/../model/usuario.php';

// Mensajes predefinidos
const ERROR_INVALID_ID = "ID de producto inválido.";
const ERROR_NOT_FOUND = "Producto no encontrado.";
const SUCCESS_REMOVE = "Producto eliminado de la cesta      .";


// Lógica para actualizar la cantidad de un producto
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'], $_GET['id_plato'], $_GET['accion']) && $_GET['action'] === 'update') {
    $id_plato = filter_input(INPUT_GET, 'id_plato', FILTER_VALIDATE_INT);
    $accion = filter_input(INPUT_GET, 'accion');

    if ($id_plato && in_array($accion, ['+', '-'])) {
        actualizarCantidad($id_plato, $accion);
    } else {
        setSessionMessage('error', ERROR_INVALID_ID);
    }

    redirect('Cesta.php');
}

// Lógica para eliminar un producto del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plato'])) {
    $id_plato = filter_input(INPUT_POST, 'id_plato', FILTER_VALIDATE_INT);

    if ($id_plato && isset($_SESSION['carrito'][$id_plato])) {
        unset($_SESSION['carrito'][$id_plato]);
        setSessionMessage('success', SUCCESS_REMOVE);
    } else {
        setSessionMessage('error', ERROR_NOT_FOUND);
    }

    redirect('Cesta.php');
}

function actualizarCantidad(int $id_plato, string $accion): void {
    if (!isset($_SESSION['carrito'][$id_plato])) {
        setSessionMessage('error', ERROR_INVALID_ID);
        return;
    }

    $cantidad = $_SESSION['carrito'][$id_plato]['cantidad'];
    $cantidad = ($accion === '+') ? $cantidad + 1 : max(0, $cantidad - 1);

    if ($cantidad <= 0) {
        unset($_SESSION['carrito'][$id_plato]);
        setSessionMessage('success', SUCCESS_REMOVE);
    } else {
        $_SESSION['carrito'][$id_plato]['cantidad'] = $cantidad;
        $_SESSION['carrito'][$id_plato]['subtotal'] = $cantidad * $_SESSION['carrito'][$id_plato]['precio'];
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
if (!empty($_SESSION['carrito'])) {
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

// Regenerar la sesión cada 5 minutos
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

<main class="container">
    <?php if (!empty($_SESSION['carrito'])): ?>
        <h1 class="title">Cesta</h1>
        <?php if (!empty($_SESSION['success'])): ?>
            <p class="success-message"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
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
                                <img src="<?= htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" class="product-image">
                                <div class="product-details">
                                    <span class="product-name"><?= htmlspecialchars($producto['nombre']); ?></span>
                                    <span class="product-description"><?= htmlspecialchars($producto['descripcion'] ?? ''); ?></span>
                                    <span class="product-price">&euro;<?= number_format($producto['precio'], 2); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="quantity-cell">
                            <div class="quantity-controls">
                                <a href="Cesta.php?action=update&id_plato=<?= htmlspecialchars($id_plato); ?>&accion=-" class="quantity-button">-</a>
                                <input type="number" value="<?= htmlspecialchars($producto['cantidad']); ?>" min="1" class="quantity-input" readonly>
                                <a href="Cesta.php?action=update&id_plato=<?= htmlspecialchars($id_plato); ?>&accion=%2B" class="quantity-button">+</a>
                            </div>
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
            <p>Total: <span>&euro;<?= number_format($total, 2); ?></span></p>
            <p class="note">Impuestos incluidos.</p>
            <?php if (isset($_SESSION['usuario'])): ?>
                <form method="post" action="index.php?controller=pedido&action=crear">
                    <button type="submit" class="button-web">FINALIZAR COMPRA</button>
                </form>
            <?php else: ?>
                <a href="Info-Usuario.php" class="button-web">INICIAR SESIÓN PARA COMPRAR</a>
            <?php endif; ?>

        </div>
    <?php else: ?>
        <div class="empty-cart">
            <h1>CESTA</h1>
            <p>La cesta está vacía.</p>
            <a class="button-web" href="Nuestra-Carta.php">SEGUIR COMPRANDO</a>
        </div>
    <?php endif; ?>
</main>
<script src="Assets/js/script.js"></script>
</body>
</html>
