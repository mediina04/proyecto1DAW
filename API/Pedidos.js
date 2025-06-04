// Obtener tasas de cambio
async function fetchExchangeRates() {
    try {
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

// Renderizar pedidos
function renderizarPedidos(pedidos) {
    const tablaCuerpo = document.getElementById('cuerpo-tabla-pedidos');
    tablaCuerpo.innerHTML = '';
    pedidos.forEach(pedido => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${pedido.nombre_cliente || pedido.cliente || ''}</td>
            <td>${pedido.fecha || pedido.fecha_pedido || ''}</td>
            <td>${parseFloat(pedido.total).toFixed(2)} €</td>
            <td>
                <button onclick="editarPedido(${pedido.id_pedido})">Editar</button>
                <button onclick="eliminarPedido(${pedido.id_pedido})">Eliminar</button>
            </td>
        `;
        tablaCuerpo.appendChild(fila);
    });
}

// Cargar pedidos desde la API
function cargarPedidos() {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerPedidos')
        .then(res => res.json())
        .then(pedidos => renderizarPedidos(pedidos))
        .catch(err => console.error("Error al cargar pedidos:", err));
}

// Agregar un nuevo pedido
function agregarPedido() {
    const id_cliente = document.getElementById('id-cliente-pedido').value;
    const fecha_pedido = document.getElementById('fecha-pedido').value;
    const total = parseFloat(document.getElementById('total-pedido').value);

    if (id_cliente && fecha_pedido && !isNaN(total)) {
        fetch('/proyecto1DAW/controller/ApiController.php?action=agregarPedido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_cliente, fecha_pedido, total })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || data.error);
            cargarPedidos();
            cerrarFormulario('formulario-agregar-pedido');
        });
    } else {
        alert("Por favor, completa todos los campos.");
    }
}

// Eliminar pedido
function eliminarPedido(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este pedido?")) {
        fetch('/proyecto1DAW/controller/ApiController.php?action=eliminarPedido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || data.error);
            cargarPedidos();
        });
    }
}

// Editar pedido (mostrar datos en formulario)
function editarPedido(id) {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerPedidos')
        .then(res => res.json())
        .then(pedidos => {
            const pedido = pedidos.find(p => p.id_pedido == id);
            if (pedido) {
                document.getElementById('id-cliente-pedido-editar').value = pedido.id_cliente;
                document.getElementById('fecha-pedido-editar').value = pedido.fecha || pedido.fecha_pedido;
                document.getElementById('total-pedido-editar').value = pedido.total;
                document.getElementById('pedido-id-editar').value = pedido.id_pedido;
                document.getElementById('formulario-editar-pedido').classList.remove('hidden');
            }
        });
}

// Guardar edición de un pedido
function guardarEdicionPedido() {
    const id = document.getElementById('pedido-id-editar').value;
    const id_cliente = document.getElementById('id-cliente-pedido-editar').value;
    const fecha_pedido = document.getElementById('fecha-pedido-editar').value;
    const total = parseFloat(document.getElementById('total-pedido-editar').value);

    fetch('/proyecto1DAW/controller/ApiController.php?action=actualizarPedido', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, id_cliente, fecha_pedido, total })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || data.error);
        cargarPedidos();
        cerrarFormulario('formulario-editar-pedido');
    });
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
document.addEventListener('DOMContentLoaded', cargarPedidos);
