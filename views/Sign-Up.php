<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" href="Assets\IMG\ICONOS\HEADER\logo-polbeiro-head.svg" type="image/svg+xml">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <div class="title">Registro</div>
        <form action="process_signup.php" method="POST" class="signup-form">
            <div class="input-Log-Sign">
                <input type="text" name="name" id="name" required placeholder=" ">
                <label for="name">Usuario</label>
            </div>
            <div class="input-Log-Sign">
                <input type="text" name="name" id="name" required placeholder=" ">
                <label for="name">Nombre</label>
            </div>
            <div class="input-Log-Sign">
                <input type="text" name="lastname" id="lastname" required placeholder=" ">
                <label for="lastname">Apellido</label>
            </div>
            <div class="input-Log-Sign">
                <input type="email" name="email" id="email" required placeholder=" ">
                <label for="email">Correo Electrónico</label>
            </div>
            <div class="input-Log-Sign">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Contraseña</label>
            </div>
            <div class="input-Log-Sign">
                <input type="number" name="phone" id="phone" required placeholder=" ">
                <label for="telf">Teléfono</label>
            </div>
            <div class="input-Log-Sign">
                <input type="text" name="address" id="address" required placeholder=" ">
                <label for="address">Dirección</label>
            </div>
            <div class="button-container">
                <button type="submit" class="reservation-button">Registrarse</button>
            </div>
        </form>
        <div class="redirect-login">
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </div>
    </div>
</body>
</html>
