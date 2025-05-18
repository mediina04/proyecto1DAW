let pedidos = [
    { id: 1, cliente: "Carlos López", fecha: "2025-01-20", total: 30 },
    { id: 2, cliente: "Laura Martín", fecha: "2025-01-21", total: 50 }
];

let exchangeRates = {}; // Almacenará los tipos de cambio
const API_KEY = 'TU_API_KEY'; // Sustituir con una clave válida

// Obtener tasas de cambio
async function fetchExchangeRates() {
    try {
        const response = await fetch(`https://api.freecurrencyapi.com/v1/latest?apikey=${API_KEY}&base_currency=EUR`);
        const data = await response.json();
        exchangeRates = data.data;
    } catch (error) {
        console.error("Error obteniendo tasas de cambio", error);
    }
}

// Convertir precios según la moneda seleccionada
function convertirPrecio(montoEnEUR, monedaDestino) {
    if (monedaDestino === "EUR") return montoEnEUR.toFixed(2) + " €";
    const tasa = exchangeRates[monedaDestino] || 1;
    return (montoEnEUR * tasa).toFixed(2) + " " + monedaDestino;
}

// Renderizar pedidos con conversión de moneda
function renderizarPedidos() {
    const tablaCuerpo = document.getElementById('cuerpo-tabla-pedidos');
    tablaCuerpo.innerHTML = '';
    const monedaSeleccionada = localStorage.getItem('selectedCurrency') || 'EUR';

    pedidos.forEach(pedido => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${pedido.cliente}</td>
            <td>${pedido.fecha}</td>
            <td>${convertirPrecio(pedido.total, monedaSeleccionada)}</td>
            <td>
                <button onclick="editarPedido(${pedido.id})">Editar</button>
                <button onclick="eliminarPedido(${pedido.id})">Eliminar</button>
            </td>
        `;
        tablaCuerpo.appendChild(fila);
    });
}

// Cambiar moneda y actualizar precios
document.getElementById('currency-select').addEventListener('change', () => {
    const selectedCurrency = document.getElementById('currency-select').value;
    localStorage.setItem('selectedCurrency', selectedCurrency);
    renderizarPedidos();
});

// Agregar un nuevo pedido
function agregarPedido() {
    const cliente = document.getElementById('nombre-cliente').value;
    const fecha = document.getElementById('fecha-pedido').value;
    const total = parseFloat(document.getElementById('total-pedido').value);

    if (cliente && fecha && total) {
        pedidos.push({ id: Date.now(), cliente, fecha, total });
        renderizarPedidos();
        cerrarFormulario('formulario-agregar-pedido');
    } else {
        alert("Por favor, completa todos los campos.");
    }
}

// Eliminar pedido
function eliminarPedido(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este pedido?")) {
        pedidos = pedidos.filter(pedido => pedido.id !== id);
        renderizarPedidos();
    }
}

// Editar pedido
function editarPedido(id) {
    const pedido = pedidos.find(p => p.id === id);
    if (pedido) {
        document.getElementById('nombre-cliente-editar').value = pedido.cliente;
        document.getElementById('fecha-pedido-editar').value = pedido.fecha;
        document.getElementById('total-pedido-editar').value = pedido.total;
        document.getElementById('pedido-id-editar').value = pedido.id;
        document.getElementById('formulario-editar-pedido').classList.remove('hidden');
    }
}

// Guardar edición de un pedido
function guardarEdicionPedido() {
    const id = parseInt(document.getElementById('pedido-id-editar').value);
    const cliente = document.getElementById('nombre-cliente-editar').value;
    const fecha = document.getElementById('fecha-pedido-editar').value;
    const total = parseFloat(document.getElementById('total-pedido-editar').value);

    const pedidoIndex = pedidos.findIndex(p => p.id === id);
    if (pedidoIndex !== -1) {
        pedidos[pedidoIndex] = { id, cliente, fecha, total };
        renderizarPedidos();
        cerrarFormulario('formulario-editar-pedido');
    }
}

// Mostrar formulario de agregar pedido
function mostrarFormularioAgregarPedido() {
    document.getElementById('formulario-agregar-pedido').classList.remove('hidden');
}

// Cerrar formularios
function cerrarFormulario(formularioId) {
    document.getElementById(formularioId).classList.add('hidden');
}

// Inicializar la página
document.addEventListener('DOMContentLoaded', async () => {
    await fetchExchangeRates();
    
    // Restaurar la selección de moneda
    const savedCurrency = localStorage.getItem('selectedCurrency') || 'EUR';
    document.getElementById('currency-select').value = savedCurrency;

    renderizarPedidos();
});
