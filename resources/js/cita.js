document.addEventListener('DOMContentLoaded', function () {
    
    // ========== GESTOR DE PRECIOS ==========
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

    // ========== SELECTOR DE HORARIOS ==========
    const vetSelect = document.getElementById('veterinario_id');
    const dateInput = document.getElementById('fecha_selector');
    const slotsContainer = document.getElementById('slots-container');
    const finalInput = document.getElementById('fecha_hora_final');

    if (vetSelect && dateInput && slotsContainer && finalInput) {
        async function cargarHorarios() {
            const vetId = vetSelect.value;
            const fecha = dateInput.value;

            slotsContainer.innerHTML = '';
            finalInput.value = '';

            if (!vetId || !fecha) {
                slotsContainer.innerHTML = '<span class="text-muted fst-italic">Selecciona doctor y fecha.</span>';
                return;
            }

            slotsContainer.innerHTML = '<div class="spinner-border text-primary spinner-border-sm" role="status"></div> Cargando...';

            try {
                const response = await fetch(`/api/horarios-disponibles?veterinario_id=${vetId}&fecha=${fecha}`);
                
                if (!response.ok) {
                    const data = await response.json();
                    slotsContainer.innerHTML = `<div class="text-danger w-100">${data.mensaje || 'No disponible'}</div>`;
                    return;
                }

                const slots = await response.json();

                slotsContainer.innerHTML = ''; 

                if (slots.length === 0) {
                    slotsContainer.innerHTML = '<div class="alert alert-warning py-1 px-2 w-100 mb-0">No hay huecos disponibles este día.</div>';
                    return;
                }

                slots.forEach(hora => {
                    const btn = document.createElement('button');
                    btn.type = 'button'; 
                    btn.className = 'btn btn-outline-primary btn-sm px-3';
                    btn.innerText = hora;
                    
                    btn.onclick = function() {
                        document.querySelectorAll('#slots-container button').forEach(b => {
                            b.classList.remove('btn-primary', 'text-white');
                            b.classList.add('btn-outline-primary');
                        });
                        
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-primary', 'text-white');

                        finalInput.value = `${fecha} ${hora}:00`;
                    };

                    slotsContainer.appendChild(btn);
                });

            } catch (error) {
                console.error(error);
                slotsContainer.innerHTML = '<div class="text-danger">Error de conexión.</div>';
            }
        }

        vetSelect.addEventListener('change', cargarHorarios);
        dateInput.addEventListener('change', cargarHorarios);
    }
});