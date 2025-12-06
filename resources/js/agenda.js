import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    // Verificamos si existe el elemento para no causar errores en otras páginas
    if (!calendarEl) return;

    // Obtenemos la ruta desde el HTML
    let routeUrl = calendarEl.getAttribute('data-route');

    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        locale: esLocale,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today:    'Hoy',
            month:    'Mes',
            week:     'Semana',
            day:      'Día',
            list:     'Lista'
        },
        
        // Carga de eventos AJAX
        events: routeUrl,
        
        // Configuración visual
        navLinks: true, // permite dar clic en los días/semanas
        editable: false, // pon true si quieres arrastrar (requiere backend extra)
        dayMaxEvents: true, // permite enlace "ver más" si hay muchas citas

        // Formato de hora (24h)
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventTimeFormat: { 
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },

        // --- AL DAR CLIC EN UNA CITA (ABRIR MODAL) ---
        eventClick: function(info) {
            // Evitamos que el navegador siga el link si hubiera
            info.jsEvent.preventDefault(); 

            let props = info.event.extendedProps;
            
            // 1. Llenar datos en el HTML del Modal
            document.getElementById('modalTitulo').innerText = info.event.title;
            document.getElementById('modalCliente').innerText = props.cliente;
            document.getElementById('modalMotivo').innerText = props.motivo;

            // Formatear la fecha bonito
            let fechaObj = info.event.start;
            let opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('modalFecha').innerText = fechaObj.toLocaleDateString('es-ES', opcionesFecha);

            // 2. Configurar el Badge de Estado
            let badge = document.getElementById('modalEstadoBadge');
            badge.innerText = props.estado.toUpperCase();
            
            // Limpiar clases anteriores y poner color según estado
            badge.className = 'badge'; // Reset
            if (props.estado === 'confirmada') {
                badge.classList.add('bg-success');
            } else if (props.estado === 'pendiente') {
                badge.classList.add('bg-warning', 'text-dark');
            } else {
                badge.classList.add('bg-secondary');
            }

            // 3. Configurar botón "Ir a detalle"
            let btnEditar = document.getElementById('btnEditarCita');
            if(btnEditar) {
                // Asumiendo ruta Laravel: /citas/{id}
                btnEditar.href = `/citas/${info.event.id}`;
            }

            // 4. Mostrar el Modal usando Bootstrap (Global)
            let modalElement = document.getElementById('citaModal');
            let modalInstance = new window.bootstrap.Modal(modalElement);
            modalInstance.show();
        }
    });

    calendar.render();
});