import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    let routeUrl = calendarEl.getAttribute('data-route');

    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        locale: esLocale,
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: routeUrl,
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            let props = info.event.extendedProps;
            
            // Llenar datos básicos del modal
            document.getElementById('modalTitle').innerText = info.event.title;
            
            let start = info.event.start ? info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
            let end = info.event.end ? info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
            document.getElementById('modalTime').innerText = `${info.event.start.toLocaleDateString()} | ${start} - ${end}`;

            let modalResponsible = document.getElementById('modalResponsible');
            let modalDescription = document.getElementById('modalDescription');
            let modalActions = document.getElementById('modalActions');
            
            modalActions.innerHTML = ''; 

            if (props.type === 'appointment') {
                // --- CITA ---
                modalResponsible.innerText = `Cliente: ${props.client} | Estado: ${props.status.toUpperCase()}`;
                modalDescription.innerText = props.reason || 'Sin motivo';

                if (props.can_edit) {
                    modalActions.innerHTML = `
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <a href="${props.edit_url}" class="btn btn-primary">Gestionar Cita</a>
                    `;
                } else {
                    modalActions.innerHTML = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`;
                }

            } else {
                // --- TAREA INTERNA ---
                modalResponsible.innerText = `Creado por: ${props.created_by}`;
                modalDescription.innerText = props.notes || 'Sin notas';

                // Si es ADMIN (can_edit = true), puede editar/borrar
                if (props.can_edit) {
                    let deleteUrl = `/tareas/${props.source_id}`;
                    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    modalActions.innerHTML = `
                        <a href="${props.edit_url}" class="btn btn-primary me-2">Editar</a>
                        
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('¿Borrar tarea?');" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    `;
                } else {
                    // Si es VETERINARIO (can_edit = false), solo puede VER
                    // Asumimos ruta: /tareas/{id}
                    let showUrl = `/tareas/${props.source_id}`;
                    modalActions.innerHTML = `
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <a href="${showUrl}" class="btn btn-info text-white">Ver Detalles</a>
                    `;
                }
            }

            let modalEl = document.getElementById('eventDetailModal');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });

    calendar.render();
});