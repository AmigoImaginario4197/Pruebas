// Este evento asegura que el código se ejecute solo cuando toda la página se ha cargado.
document.addEventListener('DOMContentLoaded', function () {

    // Buscamos los elementos del formulario por su ID.
    const rolSelect = document.getElementById('rolSelect');
    const especialidadWrapper = document.getElementById('especialidad-wrapper');

    // Comprobamos si los elementos existen en la página actual para evitar errores.
    if (rolSelect && especialidadWrapper) {

        // Esta es la función que hace la magia de mostrar u ocultar.
        function toggleEspecialidadField() {
            if (rolSelect.value === 'veterinario') {
                // Si el rol es 'veterinario', muestra el campo.
                especialidadWrapper.style.display = 'block';
            } else {
                // Para cualquier otro rol, lo oculta.
                especialidadWrapper.style.display = 'none';
            }
        }

        // 1. Ejecutamos la función una vez al cargar la página.
        //    Esto establece el estado correcto si la página se carga con 'veterinario' ya seleccionado.
        toggleEspecialidadField();

        // 2. Le decimos al selector de rol que ejecute nuestra función cada vez que su valor cambie.
        rolSelect.addEventListener('change', toggleEspecialidadField);
    }
});