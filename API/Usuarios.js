// Datos iniciales de usuarios
let usuarios = [
    { id: 1, nombre: "Iago Medina", email: "iago@correo.com", rol: "Administrador" },
    { id: 2, nombre: "Irian Raso", email: "irian@correo.com", rol: "Usuario" }
];

// Función para renderizar la tabla de usuarios
function renderizarUsuarios(usuarios) {
    const tablaCuerpo = document.getElementById('cuerpo-tabla-usuarios');
    tablaCuerpo.innerHTML = ''; // Limpiar contenido previo

    usuarios.forEach(usuario => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${usuario.nombre}</td>
            <td>${usuario.email}</td>
            <td>${usuario.rol}</td>
            <td>
                <button onclick="editarUsuario(${usuario.id})">Editar</button>
                <button onclick="eliminarUsuario(${usuario.id})">Eliminar</button>
            </td>
        `;
        tablaCuerpo.appendChild(fila);
    });
}

// Función para cargar los usuarios desde el backend
function cargarUsuarios() {
    fetch('/controller/AdminController.php?action=verUsuarios')  // Ruta ajustada al controlador
        .then(response => response.json())
        .then(usuarios => {
            renderizarUsuarios(usuarios);
        })
        .catch(error => {
            console.error("Error al cargar los usuarios:", error);
        });
}

// Función para eliminar un usuario
function eliminarUsuario(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
        fetch('/controller/AdminController.php?action=eliminarUsuario', {  // Ruta ajustada al controlador
            method: 'POST',
            body: new URLSearchParams({ id })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            cargarUsuarios();
        })
        .catch(error => {
            console.error("Error al eliminar el usuario:", error);
        });
    }
}

// Función para editar un usuario
function editarUsuario(id) {
    fetch(`/controller/AdminController.php?action=verUsuario&id=${id}`)  // Ruta ajustada al controlador
        .then(response => response.json())
        .then(usuario => {
            if (usuario) {
                document.getElementById('cliente-usuario-editar').value = usuario.cliente;
                document.getElementById('fecha-usuario-editar').value = usuario.fecha;
                document.getElementById('personas-usuario-editar').value = usuario.personas;
                document.getElementById('usuario-id-editar').value = usuario.id;

                document.getElementById('formulario-editar-usuario').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error("Error al cargar el usuario para editar:", error);
        });
}

// Función para guardar la edición de un usuario
function guardarEdicionUsuario() {
    const id = parseInt(document.getElementById('usuario-id-editar').value);
    const cliente = document.getElementById('cliente-usuario-editar').value;
    const fecha = document.getElementById('fecha-usuario-editar').value;
    const personas = document.getElementById('personas-usuario-editar').value;

    fetch('/controller/AdminController.php?action=editarUsuario', {  // Ruta ajustada al controlador
        method: 'POST',
        body: new URLSearchParams({
            id,
            cliente,
            fecha,
            personas
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        cargarUsuarios();
        cerrarFormulario('formulario-editar-usuario');
    })
    .catch(error => {
        console.error("Error al editar el usuario:", error);
    });
}

// Función para cerrar formularios
function cerrarFormulario(formularioId) {
    document.getElementById(formularioId).classList.add('hidden');
}

// Inicializar la tabla de usuarios al cargar
document.addEventListener('DOMContentLoaded', () => {
    cargarUsuarios();
});
