<?php
include_once __DIR__ . '/../model/usuario.php';
include_once __DIR__ . '/../model/PedidosDAO.php';
include_once __DIR__ . '/../config/data_base.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario']) && $_SESSION['usuario'] instanceof Usuario) {
    $usuario = $_SESSION['usuario'];
} elseif (isset($_SESSION['usuario']) && is_object($_SESSION['usuario'])) {
    $u = $_SESSION['usuario'];
    $usuario = new Usuario(
        $u->usuario ?? '',
        $u->nombre ?? '',
        $u->apellido ?? '',
        '', // No guardar la contraseña
        $u->email ?? '',
        $u->telefono ?? '',
        $u->direccion ?? '',
        $u->id_usuario ?? null,
        $u->rol ?? null
    );
    $_SESSION['usuario'] = $usuario; // Actualiza la sesión
} else {
    $usuario = null;
}

// --- OBTENER EL ÚLTIMO PEDIDO DEL USUARIO ---
$ultimoPedido = null;
if ($usuario && $usuario->getIdUsuario()) {
    $db = DataBase::connect();
    $pedidosDAO = new PedidosDAO($db);
    $ultimoPedido = $pedidosDAO->obtenerUltimoPedidoPorUsuario($usuario->getIdUsuario());
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
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
                    <img src="assets/img/ICONOS/HEADER/icon-lupa.svg" alt="Lupa" class="icon">
                </label>
                <a href="Cesta.php">
                    <img src="assets/img/ICONOS/HEADER/<?php echo (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) ? 'icon-cesta-punto.svg' : 'icon-cesta.svg'; ?>" alt="Cesta" class="icon">
                </a>
                <a href="Info-Usuario.php">
                    <img src="assets/img/ICONOS/HEADER/icon-usuario.svg" alt="Usuario" class="icon">
                </a>
            </div>
        </div>
    </header>

    <!-- Panel de Usuario -->
    <div class="user-section">
        <?php if ($usuario): ?>
            <h2>Bienvenido, <?php echo htmlspecialchars($usuario->getUsuario()); ?></h2>

            <!-- Mostrar último pedido -->
            <?php if (isset($ultimoPedido) && $ultimoPedido): ?>
                <div class="last-order">
                    <h3>Tu último pedido</h3>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($ultimoPedido['fecha']); ?></p>
                    <p><strong>Total:</strong> €<?php echo htmlspecialchars($ultimoPedido['total']); ?></p>
                    <form action="Cesta.php" method="POST">
                        <input type="hidden" name="productos" value="<?php echo htmlspecialchars(json_encode($ultimoPedido['productos'])); ?>">
                        <button type="submit" class="button-web">Repetir Pedido</button>
                    </form>
                </div>
            <?php else: ?>
                <p>No tienes ningún pedido reciente.</p>
            <?php endif; ?>

            <!-- Botón para el Panel de Administración si es administrador -->
            <?php if (method_exists($usuario, 'getRol') && $usuario->getRol() === 'admin'): ?>
                <div class="admin-panel">
                    <a href="../API/Panel_Admin.php" class="button-web">Ir al Panel de Administración</a>
                </div>
            <?php endif; ?>

            <!-- Formulario para editar datos -->
            <div class="edit-profile">
                <h3>Editar tus datos</h3>
                <form action="index.php?controller=usuario&action=actualizar_datos" method="POST">
                    <div class="input-Log-Sign">
                        <input type="text" name="name" id="name" required placeholder=" " value="<?php echo htmlspecialchars($usuario->getNombre()); ?>">
                        <label for="name">Nombre</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="text" name="lastname" id="lastname" required placeholder=" " value="<?php echo htmlspecialchars($usuario->getApellido()); ?>">
                        <label for="lastname">Apellido</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="email" name="email" id="email" required placeholder=" " value="<?php echo htmlspecialchars($usuario->getEmail()); ?>">
                        <label for="email">Correo Electrónico</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="text" name="phone" id="phone" required placeholder=" " value="<?php echo htmlspecialchars($usuario->getTelefono()); ?>">
                        <label for="phone">Teléfono</label>
                    </div>
                    <div class="input-Log-Sign">
                        <input type="text" name="address" id="address" required placeholder=" " value="<?php echo htmlspecialchars($usuario->getDireccion()); ?>">
                        <label for="address">Dirección</label>
                    </div>
                    <button type="submit" class="button-web">Guardar Cambios</button>
                </form>
            </div>

            <!-- Botón para cerrar sesión -->
            <div class="logout">
                <form action="index.php?controller=usuario&action=cerrar_sesion" method="POST">
                    <button type="submit" class="button-web">Cerrar Sesión</button>
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
</body>
</html>
