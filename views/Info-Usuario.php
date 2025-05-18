<?php
session_start();

// Simulación de un usuario logueado
$_SESSION['login'] = true; // Usuario logueado
$_SESSION['usuario'] = (object) [ // Usuario simulado como un objeto
    'id_usuario' => 1,
    'rol' => 'admin',
    'usuario' => 'Mediina', // Nombre de usuario
    'nombre' => 'Iago', // Nombre
    'apellido' => 'Medina', // Apellido
    'email' => 'iago@correo.com', // Email
    'telefono' => '123456789', // Teléfono
    'direccion' => 'Av. Collserola 2' // Dirección
];

// Función para obtener el último pedido
function obtenerUltimoPedido($id_usuario) {
    include_once __DIR__ . '/../config/data_base.php'; // Ruta relativa
    include_once __DIR__ . '/../model/PedidosDAO.php'; // Ruta relativa
 // Modelo de pedidos

    // Obtener el último pedido del usuario desde la base de datos
    return PedidosDAO::obtenerUltimoPedidoPorUsuario($id_usuario);
}

// Si el usuario está logueado, buscar su último pedido
$ultimoPedido = null;
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $ultimoPedido = obtenerUltimoPedido($_SESSION['usuario']->id_usuario);
}
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

    <!-- Panel de Usuario -->
    <div class="user-section">
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']->usuario); ?></h2>

            <!-- Mostrar último pedido -->
            <?php if ($ultimoPedido): ?>
    <div class="last-order">
        <h3>Tu último pedido</h3>
        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($ultimoPedido['fecha']); ?></p>
        <!-- <p><strong>Productos:</strong></p>
        <ul>
            <?php foreach ($ultimoPedido['productos'] as $producto): ?>
                <li>
                    <?php echo htmlspecialchars($producto['cantidad']) . 'x ' . 
                                htmlspecialchars($producto['id_plato']) . ' - €' . 
                                htmlspecialchars($producto['subtotal']); ?>
                </li>
            <?php endforeach; ?>
        </ul>-->
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
            <?php if ($_SESSION['usuario']->rol === 'admin'): ?>
                <div class="admin-panel">
                    <a href="../API/Panel_Admin.php" class="button-web">Ir al Panel de Administración</a>
                </div>
            <?php endif; ?>

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
                    <!--<button type="submit" class="button-web">Guardar Cambios</button> -->
                </form>
            </div>

            <!-- Botón para cerrar sesión -->
            <!-- <div class="logout">
                <form action="index.php?controller=usuario&action=cerrar_sesion" method="POST">
                    <button type="submit" class="button-web">Cerrar Sesión</button>
                </form>
            </div> -->
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
