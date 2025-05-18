// Lista de platos (simulación de base de datos)
let platos = [
    { id: 1, nombre: 'TAPA DE PULPO', descripcion: 'Deliciosa tapa de pulpo fresco.', precio: 2.60 },
    { id: 2, nombre: 'BROCHETA DE PULPO', descripcion: 'Brocheta de pulpo a la parrilla.', precio: 4.90 },
    { id: 3, nombre: 'PULPO A LA GALLEGA', descripcion: 'Pulpo tradicional con pimentón y aceite de oliva.', precio: 5.60 },
    { id: 4, nombre: 'ENSALADA DE PULPO', descripcion: 'Fresca ensalada con pulpo y vegetales.', precio: 4.75 },
    { id: 5, nombre: 'PULPO POKE', descripcion: 'Poke bowl con pulpo marinado.', precio: 6.00 },
    { id: 6, nombre: 'PULPO BURGER', descripcion: 'Hamburguesa de pulpo con alioli.', precio: 6.80 },
    { id: 7, nombre: 'PIZZA DE PULPO', descripcion: 'Pizza artesanal con pulpo y queso.', precio: 8.50 },
    { id: 8, nombre: 'NIGIRI DE PULPO', descripcion: 'Nigiri de pulpo fresco estilo japonés.', precio: 6.20 },
    { id: 9, nombre: 'CALAMARDITOS', descripcion: 'Calamares fritos perfectos para compartir.', precio: 2.60 },
    { id: 10, nombre: 'BAO PULPO', descripcion: 'Bao relleno de pulpo y vegetales.', precio: 4.90 },
    { id: 11, nombre: 'ENSALADILLA DE PULPO', descripcion: 'Ensaladilla rusa con un toque de pulpo.', precio: 5.60 },
    { id: 12, nombre: 'TACO DE PULPO', descripcion: 'Taco mexicano con pulpo y guacamole.', precio: 4.75 },
    { id: 13, nombre: 'PULPO EN TEMPURA', descripcion: 'Pulpo crujiente en tempura ligera.', precio: 6.00 },
    { id: 14, nombre: 'CROQUETAS DE PULPO', descripcion: 'Croquetas cremosas de pulpo.', precio: 6.80 },
    { id: 15, nombre: 'EMPANADA DE PULPO', descripcion: 'Empanada gallega rellena de pulpo.', precio: 8.50 },
    { id: 16, nombre: 'CARPACCIO DE PULPO', descripcion: 'Fino carpaccio de pulpo con cítricos.', precio: 6.20 }
];


// Función para cargar y mostrar los platos en la tabla
function mostrarPlatos() {
    const cuerpoTabla = document.getElementById('cuerpo-tabla-platos');
    cuerpoTabla.innerHTML = '';  // Limpiar tabla antes de volver a cargar

    platos.forEach(plato => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${plato.nombre}</td>
            <td>${plato.descripcion}</td>
            <td>${plato.precio.toFixed(2)} €</td>
            <td>
                <button onclick="editarPlato(${plato.id})">Editar</button>
                <button onclick="eliminarPlato(${plato.id})">Eliminar</button>
            </td>
        `;

        cuerpoTabla.appendChild(tr);
    });
}

// Función para mostrar el formulario para agregar un plato
function mostrarFormularioAgregarPlato() {
    document.getElementById('formulario-agregar-plato').classList.remove('hidden');
}

// Función para ocultar el formulario para agregar un plato
function cerrarFormulario(idFormulario) {
    document.getElementById(idFormulario).classList.add('hidden');
}

// Función para crear un nuevo plato
function agregarPlato(event) {
    event.preventDefault(); // Prevenir el envío del formulario

    const nombre = document.getElementById('nombre-plato').value.trim();
    const descripcion = document.getElementById('descripcion-plato').value.trim();
    const precio = parseFloat(document.getElementById('precio-plato').value.trim());

    // Validar los datos del formulario
    if (!nombre || !descripcion || isNaN(precio) || precio <= 0) {
        alert("Por favor, complete todos los campos correctamente.");
        return;
    }

    const nuevoPlato = {
        id: platos.length + 1,
        nombre: nombre,
        descripcion: descripcion,
        precio: precio
    };

    platos.push(nuevoPlato);
    mostrarPlatos();
    cerrarFormulario('formulario-agregar-plato');
}

// Función para editar un plato
function editarPlato(id) {
    const plato = platos.find(p => p.id === id);

    // Asegurarse de que el plato existe
    if (!plato) {
        alert("Plato no encontrado.");
        return;
    }

    document.getElementById('nombre-plato-editar').value = plato.nombre;
    document.getElementById('descripcion-plato-editar').value = plato.descripcion;
    document.getElementById('precio-plato-editar').value = plato.precio;

    // Guardamos el id del plato en el formulario para futuras actualizaciones
    document.getElementById('formulario-editar-plato').dataset.platoId = id;

    // Mostrar el formulario de edición
    document.getElementById('formulario-editar-plato').classList.remove('hidden');
}

// Función para guardar los cambios de la edición
function guardarEdicionPlato(event) {
    event.preventDefault(); // Prevenir el envío del formulario

    const id = document.getElementById('formulario-editar-plato').dataset.platoId;
    const nombre = document.getElementById('nombre-plato-editar').value.trim();
    const descripcion = document.getElementById('descripcion-plato-editar').value.trim();
    const precio = parseFloat(document.getElementById('precio-plato-editar').value.trim());

    // Validar los datos del formulario
    if (!nombre || !descripcion || isNaN(precio) || precio <= 0) {
        alert("Por favor, complete todos los campos correctamente.");
        return;
    }

    const platoIndex = platos.findIndex(p => p.id == id);
    if (platoIndex !== -1) {
        platos[platoIndex] = { id, nombre, descripcion, precio };
    }

    mostrarPlatos();
    cerrarFormulario('formulario-editar-plato');
}

// Función para eliminar un plato
function eliminarPlato(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este plato?")) {
        platos = platos.filter(plato => plato.id !== id);
        mostrarPlatos();
    }
}

// Inicialización: cargar platos al cargar la página
document.addEventListener('DOMContentLoaded', mostrarPlatos);
