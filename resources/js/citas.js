import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA 1: PRECIOS (Se mantiene igual) ---
    const servicioSelect = document.getElementById('servicio_id');
    const priceElement = document.getElementById('service-price');
    const totalElement = document.getElementById('total-price');

    if (servicioSelect && priceElement && totalElement) {
        function updatePriceDisplay() { /* ... tu código de precios ... */ }
        servicioSelect.addEventListener('change', updatePriceDisplay);
        updatePriceDisplay();
    }

    // --- LÓGICA 2: HORARIOS (Mejorada) ---
    const vetSelect = document.getElementById('veterinario_id');
    const dateInput = document.getElementById('fecha_selector');
    const slotsContainer = document.getElementById('slots-container');
    const finalInput = document.getElementById('fecha_hora_final');

    if (!vetSelect || !dateInput || !slotsContainer || !finalInput) return;

    // Habilitar fecha al elegir veterinario (solo en 'create')
    if (!window.location.pathname.includes('/edit')) {
        vetSelect.addEventListener('change', () => {
            dateInput.disabled = false;
            dateInput.value = '';
            slotsContainer.innerHTML = '<span class="text-muted fst-italic">Ahora selecciona una fecha...</span>';
        });
    }

    async function cargarHorarios() {
        const vetId = vetSelect.value;
        const fecha = dateInput.value;

        slotsContainer.innerHTML = '';
        // No borramos el valor del input final para que en 'edit' mantenga el valor inicial
        if (!window.location.pathname.includes('/edit')) {
            finalInput.value = '';
        }

        if (!vetId || !fecha) return;

        slotsContainer.innerHTML = '<div class="spinner-border text-primary spinner-border-sm"></div>';

        try {
            const response = await fetch(`/api/horarios-disponibles?veterinario_id=${vetId}&fecha=${fecha}`);
            if (!response.ok) {
                const data = await response.json();
                slotsContainer.innerHTML = `<div class="text-danger small w-100">${data.mensaje || 'Error.'}</div>`;
                return;
            }

            const slots = await response.json();
            slotsContainer.innerHTML = '';

            if (slots.length === 0) {
                slotsContainer.innerHTML = '<div class="alert alert-warning py-1 px-2 w-100 mb-0 small">No hay huecos.</div>';
                return;
            }

            slots.forEach(hora => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'slot-button';
                btn.innerText = hora;
                
                // Si estamos en 'edit' y esta es la hora guardada, la marcamos
                const horaActualGuardada = finalInput.value.split(' ')[1]?.substring(0, 5);
                if (hora === horaActualGuardada) {
                    btn.classList.add('selected');
                }
                
                btn.onclick = function() {
                    document.querySelectorAll('.slot-button').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    finalInput.value = `${fecha} ${hora}:00`;
                };
                slotsContainer.appendChild(btn);
            });

        } catch (error) {
            console.error('Error:', error);
            slotsContainer.innerHTML = '<div class="text-danger small">Error de conexión.</div>';
        }
    }

    // Escuchamos cambios de fecha y vet
    dateInput.addEventListener('change', cargarHorarios);
    vetSelect.addEventListener('change', cargarHorarios);

    // MEJORA: Si estamos en la página de edición, cargamos los horarios al inicio.
    if (window.location.pathname.includes('/edit')) {
        cargarHorarios();
    }

    // --- LÓGICA 3: VALIDACIÓN Y ENVÍO (Se mantiene igual) ---
    const form = document.querySelector('form[action*="citas."]');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            if (!finalInput.value) {
                Swal.fire({ icon: 'error', title: '¡Falta la hora!', text: 'Selecciona un horario disponible.' });
                return;
            }
            Swal.fire({
                title: '¿Confirmar Cita?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, agendar',
                cancelButtonText: 'Revisar',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    const backendErrorsDiv = document.getElementById('backend-errors');
    if (backendErrorsDiv && backendErrorsDiv.innerHTML.trim() !== '') {
        Swal.fire({
            icon: 'error',
            title: '¡Ups! Hubo un problema',
            html: backendErrorsDiv.innerHTML,
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos del DOM
    const servicioSelect = document.getElementById('servicio_id');
    const totalPriceDisplay = document.getElementById('total-price');
    const serviceNameDisplay = document.getElementById('service-name-display');
    const hiddenPriceSpan = document.getElementById('service-price');

    // Función para actualizar el precio
    function updatePrice() {
        if (!servicioSelect) return;

        // Obtener la opción seleccionada
        const selectedOption = servicioSelect.options[servicioSelect.selectedIndex];
        
        // Leer el precio del atributo data-price (o 0 si no hay nada)
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const name = selectedOption.text;

        // Actualizar el texto en pantalla
        if (totalPriceDisplay) {
            totalPriceDisplay.textContent = price.toFixed(2) + ' €';
        }
        
        // Actualizar el nombre del servicio en el resumen
        if (serviceNameDisplay) {
            // Si es la opción por defecto ("-- Selecciona --"), ponemos guiones
            serviceNameDisplay.textContent = (price === 0) ? '--' : name;
        }

        // Actualizar el span oculto (por si tu otra lógica lo usa)
        if (hiddenPriceSpan) {
            hiddenPriceSpan.textContent = price;
        }
    }

    // Escuchar cambios en el select de servicios
    if (servicioSelect) {
        servicioSelect.addEventListener('change', updatePrice);
        
        // Ejecutar una vez al cargar (por si hay un old value tras un error de validación)
        updatePrice();
    }
});