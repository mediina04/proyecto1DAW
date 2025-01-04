// API/Platos.js

// Obtener lista de productos
function obtenerProductos() {
    axios.get('/api/platos')
        .then(response => {
            console.log("Productos obtenidos:", response.data);
            cargarContenidoEnAdmin("Productos", response.data);
        })
        .catch(error => console.error("Error al obtener productos:", error));
}

// Crear un nuevo producto
function crearProducto(producto) {
    axios.post('/api/platos', producto)
        .then(response => console.log("Producto creado:", response.data))
        .catch(error => console.error("Error al crear producto:", error));
}

// Eliminar un producto
function eliminarProducto(idProducto) {
    axios.delete(`/api/platos/${idProducto}`)
        .then(response => console.log("Producto eliminado:", response.data))
        .catch(error => console.error("Error al eliminar producto:", error));
}

// Cargar el contenido de productos en el panel
function cargarContenidoEnAdmin(titulo, data) {
    const content = document.getElementById('admin-content');
    content.innerHTML = `<h2>${titulo}</h2>`;
    content.innerHTML += `<ul>${data.map(item => `<li>${JSON.stringify(item)}</li>`).join('')}</ul>`;
}

// Asociar eventos
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('verProductos').addEventListener('click', obtenerProductos);
    document.getElementById('crearProducto').addEventListener('click', () => {
        const nuevoProducto = { nombre: "Nuevo Plato", precio: 10.99 }; // Simulación
        crearProducto(nuevoProducto);
    });
});
