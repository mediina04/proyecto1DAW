<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" href="Assets/IMG/ICONOS/HEADER/logo-polbeiro-head.svg" type="image/svg+xml">
    <link rel="stylesheet" href="Assets/css/styles.css">
</head>
<body>
    <div class="contenedor">
        <div class="title">Registro</div>
        <form action="index.php?controller=usuario&action=registrar" method="POST" class="signup-form" onsubmit="return validatePassword()">
            <div class="input-Log-Sign">
                <input type="text" name="usuario" id="user" required placeholder=" " minlength="3">
                <label for="user">Usuario</label>
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
                <input type="password" name="password" id="password" required placeholder=" " minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.">
                <label for="password">Contraseña</label>
            </div>
            <div class="input-Log-Sign">
                <input type="password" name="confirm_password" id="confirm_password" required placeholder=" " minlength="8">
                <label for="confirm_password">Confirmar Contraseña</label>
            </div>
            <div class="input-Log-Sign">
                <input type="number" name="phone" id="phone" required placeholder=" " pattern="\d{9}" title="Introduce un número de teléfono válido (9 dígitos)">
                <label for="phone">Teléfono</label>
            </div>
            <div class="input-Log-Sign">
                <input type="text" name="address" id="address" required placeholder=" ">
                <label for="address">Dirección</label>
            </div>

            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">

            <div class="button-container">
                <button type="submit" class="button-web" href="Inicio.php">Registrarse</button>
            </div>
        </form>

        <div class="redirect-login">
            <p>¿Ya tienes una cuenta? <a href="Login.php">Inicia sesión aquí</a>.</p>
        </div>
    </div>
</body>
</html>
