<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="icon" href="Assets/IMG/ICONOS/HEADER/logo-polbeiro-head.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Admin.css">
</head>
<body>

    <!-- Barra lateral -->
    <div class="sidebar">
        <div class="logo">Admin Panel</div>
        <ul>
            <li class="active" data-section="usuarios" role="button" tabindex="0">Usuarios</li>
            <li data-section="pedidos" role="button" tabindex="0">Pedidos</li>
            <li data-section="platos" role="button" tabindex="0">Platos</li>
            <li data-section="reservas" role="button" tabindex="0">Reservas</li>
        </ul>
        <a href="../views/Inicio.php" class="cerrarPanel">Cerrar Panel</a>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">

        <!-- Sección Usuarios -->
        <div id="usuarios" class="section active">
            <h2>Gestión de Usuarios</h2>

            <div id="formulario-agregar-usuario" class="hidden">
                <h3>Agregar Nuevo Usuario</h3>
                <input type="text" id="nombre-usuario" placeholder="Nombre" required>
                <input type="email" id="email-usuario" placeholder="Email" required>
                <select id="rol-usuario" required>
                    <option value="Administrador">Administrador</option>
                    <option value="Usuario">Usuario</option>
                </select>
                <button onclick="if (typeof agregarUsuario === 'function') agregarUsuario();">Agregar</button>
                <button onclick="cerrarFormulario('formulario-agregar-usuario')">Cancelar</button>
            </div>

            <table id="tabla-usuarios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla-usuarios">
                    <!-- Contenido generado por JavaScript -->
                </tbody>
            </table>

            <div class="acciones">
                <button onclick="mostrarFormularioAgregarUsuario()">Agregar Nuevo Usuario</button>
            </div>
        </div>

        <!-- Sección Pedidos -->
        <div id="pedidos" class="section">
            <h2>Gestión de Pedidos</h2>

            <table id="tabla-pedidos">
                <thead>
                    <tr>
                        <th>Nombre Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla-pedidos">
                    <!-- Contenido generado por JavaScript -->
                </tbody>
            </table>

            <div class="acciones">
                <button onclick="mostrarFormularioAgregarPedido()">Agregar Nuevo Pedido</button>
            </div>
        </div>

        <!-- Sección Platos -->
        <div id="platos" class="section">
            <h2>Gestión de Platos</h2>

            <table id="tabla-platos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla-platos">
                    <!-- Contenido generado por JavaScript -->
                </tbody>
            </table>

            <div class="acciones">
                <button onclick="mostrarFormularioAgregarPlato()">Agregar Nuevo Plato</button>
            </div>
        </div>

        <!-- Sección Reservas -->
        <div id="reservas" class="section">
            <h2>Gestión de Reservas</h2>

            <table id="tabla-reservas">
                <thead>
                    <tr>
                        <th>Nombre Cliente</th>
                        <th>Fecha</th>
                        <th>Número de Personas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla-reservas">
                    <!-- Contenido generado por JavaScript -->
                </tbody>
            </table>

            <div class="acciones">
                <button onclick="mostrarFormularioAgregarReserva()">Agregar Nueva Reserva</button>
            </div>
        </div>

    </div>

    <script src="Usuarios.js"></script>
    <script src="Pedidos.js"></script>
    <script src="Platos.js"></script>
    <script src="Reservas.js"></script>
    <script src="admin.js"></script>

    <script>
        // Funcionalidad para cambiar entre secciones
        const menuItems = document.querySelectorAll('.sidebar ul li');
        const sections = document.querySelectorAll('.section');

        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                // Quitar la clase activa de todos los elementos
                menuItems.forEach(menu => menu.classList.remove('active'));
                sections.forEach(section => section.classList.remove('active'));

                // Agregar la clase activa al elemento seleccionado
                item.classList.add('active');
                document.getElementById(item.getAttribute('data-section')).classList.add('active');
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar ul');

            if (sidebar) {
                sidebar.addEventListener('click', (event) => {
                    if (event.target.tagName === 'LI') {
                        document.querySelectorAll('.sidebar ul li').forEach(item => item.classList.remove('active'));
                        document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));

                        event.target.classList.add('active');
                        document.getElementById(event.target.getAttribute('data-section')).classList.add('active');
                    }
                });
            }
        });

    </script>

</body>
</html>
