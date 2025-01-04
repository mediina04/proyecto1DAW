// API/Pedidos.js

// Obtener lista de pedidos
function obtenerPedidos() {
    axios.get('/api/pedidos')
        .then(response => {
            console.log("Pedidos obtenidos:", response.data);
            cargarContenidoEnAdmin("Pedidos", response.data);
        })
        .catch(error => console.error("Error al obtener pedidos:", error));
}

// Crear un pedido (simulación)
function crearPedido(pedido) {
    axios.post('/api/pedidos', pedido)
        .then(response => console.log("Pedido creado:", response.data))
        .catch(error => console.error("Error al crear pedido:", error));
}

// Cancelar un pedido
function cancelarPedido(idPedido) {
    axios.delete(`/api/pedidos/${idPedido}`)
        .then(response => console.log("Pedido cancelado:", response.data))
        .catch(error => console.error("Error al cancelar pedido:", error));
}

// Cargar el contenido de pedidos en el panel
function cargarContenidoEnAdmin(titulo, data) {
    const content = document.getElementById('admin-content');
    content.innerHTML = `<h2>${titulo}</h2>`;
    content.innerHTML += `<ul>${data.map(item => `<li>${JSON.stringify(item)}</li>`).join('')}</ul>`;
}

// Asociar eventos
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('verUsuarios').addEventListener('click', obtenerPedidos);
});
