    <?php
    include_once __DIR__ . '/../model/usuario.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="assets/IMG/ICONOS/HEADER/logo-polbeiro-head.svg" type="image">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <div class="title">Login</div>
            <?php if (isset($_GET['error'])): ?>
                <div class="error-msg" style="color: red; margin-bottom: 1em;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=usuario&action=login" method="POST" class="login-form">
                <div class="input-Log-Sign">
                    <input type="text" name="usuario" id="usuario" required placeholder=" " value="<?php echo htmlspecialchars($_GET['usuario'] ?? ''); ?>">
                    <label for="usuario">Usuario</label>
                </div>
                <div class="input-Log-Sign">
                    <input type="password" name="password" id="password" required placeholder=" ">
                    <label for="password">Contraseña</label>
                </div>
                <div class="button-container">
                    <button type="submit" class="button-web">Iniciar Sesión</button>
                </div>
            </form>

        
        <div class="redirect-signup">
            <p>¿No tienes una cuenta? <a href="Sign-Up.php">Regístrate aquí</a>.</p>
        </div>
    </div>
</body>
</html>
