// Datos iniciales de reservas (puedes reemplazar esto con una API o base de datos real)
let reservas = [
    { id: 1, cliente: "Iago Medina", fecha: "2025-01-22", personas: 4 },
    { id: 2, cliente: "Irian Raso", fecha: "2025-01-23", personas: 2 }
];

// Función para renderizar la tabla de reservas
function renderizarReservas() {
    const tablaCuerpo = document.getElementById('cuerpo-tabla-reservas');
    tablaCuerpo.innerHTML = ''; // Limpiar contenido previo

    reservas.forEach(reserva => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${reserva.cliente}</td>
            <td>${reserva.fecha}</td>
            <td>${reserva.personas}</td>
            <td>
                <button onclick="editarReserva(${reserva.id})">Editar</button>
                <button onclick="eliminarReserva(${reserva.id})">Eliminar</button>
            </td>
        `;
        tablaCuerpo.appendChild(fila);
    });
}

// Función para agregar una nueva reserva
function agregarReserva() {
    const cliente = document.getElementById('nombre-cliente-reserva').value;
    const fecha = document.getElementById('fecha-reserva').value;
    const personas = parseInt(document.getElementById('personas-reserva').value);

    if (cliente && fecha && personas) {
        const nuevaReserva = { id: Date.now(), cliente, fecha, personas };
        reservas.push(nuevaReserva);
        renderizarReservas();
        cerrarFormulario('formulario-agregar-reserva');
    } else {
        alert("Por favor, completa todos los campos.");
    }
}

// Función para eliminar una reserva
function eliminarReserva(id) {
    if (confirm("¿Estás seguro de que deseas eliminar esta reserva?")) {
        reservas = reservas.filter(reserva => reserva.id !== id);
        renderizarReservas();
    }
}

// Función para editar una reserva
function editarReserva(id) {
    const reserva = reservas.find(r => r.id === id);
    if (reserva) {
        document.getElementById('nombre-cliente-reserva-editar').value = reserva.cliente;
        document.getElementById('fecha-reserva-editar').value = reserva.fecha;
        document.getElementById('personas-reserva-editar').value = reserva.personas;
        document.getElementById('reserva-id-editar').value = reserva.id;

        document.getElementById('formulario-editar-reserva').classList.remove('hidden');
    }
}

// Función para guardar la edición de una reserva
function guardarEdicionReserva() {
    const id = parseInt(document.getElementById('reserva-id-editar').value);
    const cliente = document.getElementById('nombre-cliente-reserva-editar').value;
    const fecha = document.getElementById('fecha-reserva-editar').value;
    const personas = parseInt(document.getElementById('personas-reserva-editar').value);

    const reservaIndex = reservas.findIndex(r => r.id === id);
    if (reservaIndex !== -1) {
        reservas[reservaIndex] = { id, cliente, fecha, personas };
        renderizarReservas();
        cerrarFormulario('formulario-editar-reserva');
    }
}

// Función para mostrar el formulario de agregar reserva
function mostrarFormularioAgregarReserva() {
    document.getElementById('formulario-agregar-reserva').classList.remove('hidden');
}

// Función para cerrar formularios
function cerrarFormulario(formularioId) {
    document.getElementById(formularioId).classList.add('hidden');
}

// Inicializar la tabla de reservas al cargar
document.addEventListener('DOMContentLoaded', () => {
    renderizarReservas();
});
