<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="Assets\IMG\ICONOS\HEADER\logo-polbeiro-head.svg" type="image/svg+xml">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <div class="title">Login</div>
        <form action="process_login.php" method="POST" class="login-form">
            <div class="input-Log-Sign">
                <input type="text" name="username" id="username" required placeholder=" ">
                <label for="username">Usuario</label>
            </div>
            <div class="input-Log-Sign">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Contraseña</label>
            </div>
            <div class="button-container">
                <button type="submit" class="reservation-button">Iniciar sesión</button>
            </div>
        </form>
        <div class="redirect-signup">
            <p>¿No tienes una cuenta? <a href="Sign-Up.php">Regístrate aquí</a>.</p>
        </div>
    </div>
</body>
</html>
