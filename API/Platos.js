// Renderizar platos desde la base de datos
function renderizarPlatos(platos) {
    const cuerpoTabla = document.getElementById('cuerpo-tabla-platos');
    cuerpoTabla.innerHTML = '';
    platos.forEach(plato => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${plato.nombre}</td>
            <td>${plato.descripcion}</td>
            <td>${parseFloat(plato.precio).toFixed(2)} €</td>
            <td>
                <button onclick="editarPlato(${plato.id_plato})">Editar</button>
                <button onclick="eliminarPlato(${plato.id_plato})">Eliminar</button>
            </td>
        `;
        cuerpoTabla.appendChild(tr);
    });
}

// Cargar platos desde la API
function cargarPlatos() {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerPlatos')
        .then(res => res.json())
        .then(platos => renderizarPlatos(platos))
        .catch(err => console.error("Error al cargar platos:", err));
}

// Agregar plato
function agregarPlato(event) {
    event.preventDefault();
    const nombre = document.getElementById('nombre-plato').value.trim();
    const descripcion = document.getElementById('descripcion-plato').value.trim();
    const precio = parseFloat(document.getElementById('precio-plato').value.trim());

    if (!nombre || !descripcion || isNaN(precio) || precio <= 0) {
        alert("Por favor, complete todos los campos correctamente.");
        return;
    }

    fetch('/proyecto1DAW/controller/ApiController.php?action=agregarPlato', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, descripcion, precio })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || data.error);
        cargarPlatos();
        cerrarFormulario('formulario-agregar-plato');
    });
}

// Eliminar plato
function eliminarPlato(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este plato?")) {
        fetch('/controller/ApiController.php?action=eliminarPlato', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || data.error);
            cargarPlatos();
        });
    }
}

// Editar plato (mostrar datos en formulario)
function editarPlato(id) {
    fetch('/proyecto1DAW/controller/ApiController.php?action=obtenerPlatos')
        .then(res => res.json())
        .then(platos => {
            const plato = platos.find(p => p.id_plato == id);
            if (plato) {
                document.getElementById('nombre-plato-editar').value = plato.nombre;
                document.getElementById('descripcion-plato-editar').value = plato.descripcion;
                document.getElementById('precio-plato-editar').value = plato.precio;
                document.getElementById('formulario-editar-plato').dataset.platoId = id;
                document.getElementById('formulario-editar-plato').classList.remove('hidden');
            }
        });
}

// Guardar edición de plato
function guardarEdicionPlato(event) {
    event.preventDefault();
    const id = document.getElementById('formulario-editar-plato').dataset.platoId;
    const nombre = document.getElementById('nombre-plato-editar').value.trim();
    const descripcion = document.getElementById('descripcion-plato-editar').value.trim();
    const precio = parseFloat(document.getElementById('precio-plato-editar').value.trim());

    if (!nombre || !descripcion || isNaN(precio) || precio <= 0) {
        alert("Por favor, complete todos los campos correctamente.");
        return;
    }

    fetch('/proyecto1DAW/controller/ApiController.php?action=actualizarPlato', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, nombre, descripcion, precio })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || data.error);
        cargarPlatos();
        cerrarFormulario('formulario-editar-plato');
    });
}

// Mostrar formulario para agregar plato
function mostrarFormularioAgregarPlato() {
    document.getElementById('formulario-agregar-plato').classList.remove('hidden');
}

// Cerrar formularios
function cerrarFormulario(idFormulario) {
    document.getElementById(idFormulario).classList.add('hidden');
}

// Función para mostrar el formulario de agregar plato
function mostrarFormularioAgregarPlato() {
    document.getElementById('formulario-agregar-plato').classList.remove('hidden');
}

// Inicialización: cargar platos al cargar la página
document.addEventListener('DOMContentLoaded', cargarPlatos);
