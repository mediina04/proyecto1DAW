document.addEventListener('DOMContentLoaded', () => {
    let indiceActual = 0;
    const diapositivas = document.querySelectorAll('.diapositiva');
    const puntos = document.querySelectorAll('.punto');
    const circulosProgreso = document.querySelectorAll('.progreso');
    const duracionDiapositiva = 5000; // Duración de cada diapositiva en milisegundos

    // Función para cambiar de diapositiva y activar el progreso
    function irADiapositiva(indice) {
        indiceActual = indice;
        actualizarSlider();
        reiniciarAutoDeslizamiento();
    }

    // Función para actualizar el slider
    function actualizarSlider() {
        // Mostrar solo la diapositiva activa
        diapositivas.forEach((diapositiva, idx) => {
            diapositiva.classList.toggle('activa', idx === indiceActual);
        });

        // Actualizar estado visual de los puntos de navegación
        puntos.forEach((punto, idx) => {
            punto.classList.toggle('activo', idx === indiceActual);
        });

        // Resetear y activar la animación de progreso solo en el botón activo
        circulosProgreso.forEach((circulo, idx) => {
            circulo.style.animation = 'none';
            if (idx === indiceActual) {
                void circulo.offsetWidth; // Reinicia la animación
                circulo.style.animation = `progreso ${duracionDiapositiva / 500}s linear forwards`;
            }
        });
    }

    // Deslizamiento automático cada 5 segundos
    let intervaloAutoDeslizamiento = setInterval(() => cambiarDiapositiva(1), duracionDiapositiva);

    function reiniciarAutoDeslizamiento() {
        clearInterval(intervaloAutoDeslizamiento);
        intervaloAutoDeslizamiento = setInterval(() => cambiarDiapositiva(1), duracionDiapositiva);
    }

    // Cambiar a la siguiente diapositiva automáticamente
    function cambiarDiapositiva(direccion) {
        indiceActual = (indiceActual + direccion + diapositivas.length) % diapositivas.length;
        actualizarSlider();
    }

    // Inicializar slider
    actualizarSlider();

    // Hacer que los puntos respondan al clic
    puntos.forEach((punto, idx) => {
        punto.addEventListener('click', () => irADiapositiva(idx));
    });
});
