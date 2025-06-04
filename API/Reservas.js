// Renderizar reservas desde la base de datos
function renderizarReservas(reservas) {
    const tablaCuerpo = document.getElementById('cuerpo-tabla-reservas');
    tablaCuerpo.innerHTML = '';
    reservas.forEach(reserva => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${reserva.nombre_cliente || reserva.cliente || ''}</td>
            <td>${reserva.fecha}</td>
            <td>${reserva.num_personas || reserva.personas}</td>
            <td>
                <button onclick="editarReserva(${reserva.id_reserva})">Editar</button>
                <button onclick="eliminarReserva(${reserva.id_reserva})">Eliminar</button>
            </td>
        `;
        tablaCuerpo.appendChild(fila);
    });
}

// Cargar reservas desde la API
function obtenerReservas() {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerReservas')
        .then(res => res.json())
        .then(reservas => renderizarReservas(reservas))
        .catch(err => console.error("Error al cargar reservas:", err));
}

// Agregar reserva
function agregarReserva() {
    const id_usuario = document.getElementById('id-usuario-reserva').value;
    const fecha = document.getElementById('fecha-reserva').value;
    const hora = document.getElementById('hora-reserva').value;
    const num_personas = parseInt(document.getElementById('personas-reserva').value);

    if (id_usuario && fecha && hora && num_personas) {
        fetch('/proyecto1DAW/controller/ApiController.php?action=agregarReserva', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_usuario, fecha, hora, num_personas })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || data.error);
            cargarReservas();
            cerrarFormulario('formulario-agregar-reserva');
        });
    } else {
        alert("Por favor, completa todos los campos.");
    }
}

// Eliminar reserva
function eliminarReserva(id) {
    if (confirm("¿Estás seguro de que deseas eliminar esta reserva?")) {
        fetch('/controller/ApiController.php?action=eliminarReserva', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || data.error);
            cargarReservas();
        });
    }
}

// Editar reserva (mostrar datos en formulario)
function editarReserva(id) {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerReservas')
        .then(res => res.json())
        .then(reservas => {
            const reserva = reservas.find(r => r.id_reserva == id);
            if (reserva) {
                document.getElementById('id-usuario-reserva-editar').value = reserva.id_usuario;
                document.getElementById('fecha-reserva-editar').value = reserva.fecha;
                document.getElementById('hora-reserva-editar').value = reserva.hora;
                document.getElementById('personas-reserva-editar').value = reserva.num_personas;
                document.getElementById('reserva-id-editar').value = reserva.id_reserva;
                document.getElementById('formulario-editar-reserva').classList.remove('hidden');
            }
        });
}

// Guardar edición de reserva
function guardarEdicionReserva() {
    const id = document.getElementById('reserva-id-editar').value;
    const id_usuario = document.getElementById('id-usuario-reserva-editar').value;
    const fecha = document.getElementById('fecha-reserva-editar').value;
    const hora = document.getElementById('hora-reserva-editar').value;
    const num_personas = parseInt(document.getElementById('personas-reserva-editar').value);

    fetch('/proyecto1DAW/controller/ApiController.php?action=actualizarReserva', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, id_usuario, fecha, hora, num_personas })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || data.error);
        cargarReservas();
        cerrarFormulario('formulario-editar-reserva');
    });
}

// Mostrar formulario de agregar reserva
function mostrarFormularioAgregarReserva() {
    document.getElementById('formulario-agregar-reserva').classList.remove('hidden');
}

// Cerrar formularios
function cerrarFormulario(formularioId) {
    document.getElementById(formularioId).classList.add('hidden');
}

// Función para mostrar el formulario de agregar reserva
function mostrarFormularioAgregarReserva() {
    document.getElementById('formulario-agregar-reserva').classList.remove('hidden');
}

// Inicializar la tabla de reservas al cargar
document.addEventListener('DOMContentLoaded', cargarReservas);
