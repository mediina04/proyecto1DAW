<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="icon" href="/proyecto1DAW/Assets/IMG/ICONOS/HEADER/logo-polbeiro-head.svg" type="image/svg+xml">
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

            <!-- Formulario para agregar usuario -->
            <div id="formulario-agregar-usuario" class="hidden">
                <h3>Agregar Nuevo Usuario</h3>
                <input type="text" id="nombre-usuario" placeholder="Nombre" required>
                <input type="email" id="email-usuario" placeholder="Email" required>
                <select id="rol-usuario" required>
                    <option value="Administrador">Administrador</option>
                    <option value="Usuario">Usuario</option>
                </select>
                <button onclick="agregarUsuario()">Agregar</button>
                <button onclick="cerrarFormulario('formulario-agregar-usuario')">Cancelar</button>
            </div>

            <!-- Formulario para editar usuario -->
            <div id="formulario-editar-usuario" class="hidden">
                <h3>Editar Usuario</h3>
                <input type="hidden" id="usuario-id-editar">
                <input type="text" id="nombre-usuario-editar" placeholder="Nombre" required>
                <input type="email" id="email-usuario-editar" placeholder="Email" required>
                <select id="rol-usuario-editar" required>
                    <option value="Administrador">Administrador</option>
                    <option value="Usuario">Usuario</option>
                </select>
                <button onclick="guardarEdicionUsuario()">Guardar</button>
                <button onclick="cerrarFormulario('formulario-editar-usuario')">Cancelar</button>
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

            <!-- Formulario para agregar pedido -->
            <div id="formulario-agregar-pedido" class="hidden">
                <h3>Agregar Nuevo Pedido</h3>
                <input type="text" id="id-cliente-pedido" placeholder="ID Cliente" required>
                <input type="date" id="fecha-pedido" required>
                <input type="number" id="total-pedido" placeholder="Total (€)" required>
                <button onclick="agregarPedido()">Agregar</button>
                <button onclick="cerrarFormulario('formulario-agregar-pedido')">Cancelar</button>
            </div>

            <!-- Formulario para editar pedido -->
            <div id="formulario-editar-pedido" class="hidden">
                <h3>Editar Pedido</h3>
                <input type="hidden" id="pedido-id-editar">
                <input type="text" id="id-cliente-pedido-editar" placeholder="ID Cliente" required>
                <input type="date" id="fecha-pedido-editar" required>
                <input type="number" id="total-pedido-editar" placeholder="Total (€)" required>
                <button onclick="guardarEdicionPedido()">Guardar</button>
                <button onclick="cerrarFormulario('formulario-editar-pedido')">Cancelar</button>
            </div>

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

            <!-- Formulario para agregar plato -->
            <div id="formulario-agregar-plato" class="hidden">
                <h3>Agregar Nuevo Plato</h3>
                <input type="text" id="nombre-plato" placeholder="Nombre" required>
                <input type="text" id="descripcion-plato" placeholder="Descripción" required>
                <input type="number" id="precio-plato" placeholder="Precio (€)" step="0.01" required>
                <button onclick="agregarPlato(event)">Agregar</button>
                <button onclick="cerrarFormulario('formulario-agregar-plato')">Cancelar</button>
            </div>

            <!-- Formulario para editar plato -->
            <div id="formulario-editar-plato" class="hidden" data-plato-id="">
                <h3>Editar Plato</h3>
                <input type="text" id="nombre-plato-editar" placeholder="Nombre" required>
                <input type="text" id="descripcion-plato-editar" placeholder="Descripción" required>
                <input type="number" id="precio-plato-editar" placeholder="Precio (€)" step="0.01" required>
                <button onclick="guardarEdicionPlato(event)">Guardar</button>
                <button onclick="cerrarFormulario('formulario-editar-plato')">Cancelar</button>
            </div>

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

            <!-- Formulario para agregar reserva -->
            <div id="formulario-agregar-reserva" class="hidden">
                <h3>Agregar Nueva Reserva</h3>
                <input type="text" id="id-usuario-reserva" placeholder="ID Usuario" required>
                <input type="date" id="fecha-reserva" required>
                <input type="time" id="hora-reserva" required>
                <input type="number" id="personas-reserva" placeholder="Nº Personas" required>
                <button onclick="agregarReserva()">Agregar</button>
                <button onclick="cerrarFormulario('formulario-agregar-reserva')">Cancelar</button>
            </div>

            <!-- Formulario para editar reserva -->
            <div id="formulario-editar-reserva" class="hidden">
                <h3>Editar Reserva</h3>
                <input type="hidden" id="reserva-id-editar">
                <input type="text" id="id-usuario-reserva-editar" placeholder="ID Usuario" required>
                <input type="date" id="fecha-reserva-editar" required>
                <input type="time" id="hora-reserva-editar" required>
                <input type="number" id="personas-reserva-editar" placeholder="Nº Personas" required>
                <button onclick="guardarEdicionReserva()">Guardar</button>
                <button onclick="cerrarFormulario('formulario-editar-reserva')">Cancelar</button>
            </div>

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
