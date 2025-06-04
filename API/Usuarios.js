// Renderizar usuarios desde la base de datos
function renderizarUsuarios(usuarios) {
    const tablaCuerpo = document.getElementById('cuerpo-tabla-usuarios');
    tablaCuerpo.innerHTML = '';
    usuarios.forEach(usuario => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${usuario.nombre}</td>
            <td>${usuario.email}</td>
            <td>${usuario.rol}</td>
            <td>
                <button onclick="editarUsuario(${usuario.id_usuario})">Editar</button>
                <button onclick="eliminarUsuario(${usuario.id_usuario})">Eliminar</button>
            </td>
        `;
        tablaCuerpo.appendChild(fila);
    });
}

// Cargar usuarios desde la API
function cargarUsuarios() {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerUsuarios')
        .then(response => response.json())
        .then(usuarios => {
            renderizarUsuarios(usuarios);
        })
        .catch(error => {
            console.error("Error al cargar los usuarios:", error);
        });
}

// Eliminar usuario
function eliminarUsuario(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
        fetch('/proyecto1DAW/controller/ApiController.php?action=eliminarUsuario', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || data.error);
            cargarUsuarios();
        })
        .catch(error => {
            console.error("Error al eliminar el usuario:", error);
        });
    }
}

// Editar usuario (mostrar datos en formulario)
function editarUsuario(id) {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerUsuarios')
        .then(response => response.json())
        .then(usuarios => {
            const usuario = usuarios.find(u => u.id_usuario == id);
            if (usuario) {
                document.getElementById('nombre-usuario').value = usuario.nombre;
                document.getElementById('email-usuario').value = usuario.email;
                document.getElementById('rol-usuario').value = usuario.rol;
                document.getElementById('usuario-id-editar').value = usuario.id_usuario;
                document.getElementById('formulario-editar-usuario').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error("Error al cargar el usuario para editar:", error);
        });
}

// Guardar edición de usuario
function guardarEdicionUsuario() {
    const id = document.getElementById('usuario-id-editar').value;
    const nombre = document.getElementById('nombre-usuario').value;
    const email = document.getElementById('email-usuario').value;
    const rol = document.getElementById('rol-usuario').value;

    fetch('/proyecto1DAW/controller/ApiController.php?action=actualizarUsuario', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, nombre, email, rol })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || data.error);
        cargarUsuarios();
        cerrarFormulario('formulario-editar-usuario');
    })
    .catch(error => {
        console.error("Error al editar el usuario:", error);
    });
}

// Agregar usuario
function agregarUsuario() {
    const nombre = document.getElementById('nombre-usuario').value;
    const email = document.getElementById('email-usuario').value;
    const rol = document.getElementById('rol-usuario').value;

    fetch('/proyecto1DAW/controller/ApiController.php?action=agregarUsuario', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, email, rol })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || data.error);
        cargarUsuarios();
        cerrarFormulario('formulario-agregar-usuario');
    })
    .catch(error => {
        console.error("Error al agregar el usuario:", error);
    });
}

// Cerrar formularios
function cerrarFormulario(formularioId) {
    document.getElementById(formularioId).classList.add('hidden');
}

// Mostrar formulario de agregar usuario
function mostrarFormularioAgregarUsuario() {
    document.getElementById('formulario-agregar-usuario').classList.remove('hidden');
}

// Inicializar la tabla de usuarios al cargar
document.addEventListener('DOMContentLoaded', () => {
    cargarUsuarios();
});
