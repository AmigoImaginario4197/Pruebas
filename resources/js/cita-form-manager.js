// ARCHIVO: resources/js/cita-form-manager.js

document.addEventListener('DOMContentLoaded', function () {
    
    const servicioSelect = document.getElementById('servicio_id');
    const priceElement = document.getElementById('service-price');
    const totalElement = document.getElementById('total-price');

    // Solo ejecutamos si los elementos existen (es decir, si es un cliente viendo el resumen)
    if (servicioSelect && priceElement && totalElement) {

        function updatePriceDisplay() {
            // Obtenemos el precio del atributo data-price de la opción seleccionada
            const selectedOption = servicioSelect.options[servicioSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            
            // Formateamos el precio
            const formattedPrice = '$' + parseFloat(price).toFixed(2);
            
            // Actualizamos el DOM
            priceElement.textContent = formattedPrice;
            totalElement.textContent = formattedPrice;
        }

        // Escuchamos cambios en el selector
        servicioSelect.addEventListener('change', updatePriceDisplay);

        // Ejecutamos una vez al inicio para cargar el precio si hay un valor preseleccionado (old input)
        updatePriceDisplay();
    }
});