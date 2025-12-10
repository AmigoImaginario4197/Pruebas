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

            // Elementos del modal
            const modalTitle = document.getElementById('modalTitle');
            const modalTime = document.getElementById('modalTime');
            const modalResponsible = document.getElementById('modalResponsible');
            const modalDescription = document.getElementById('modalDescription');
            const modalActions = document.getElementById('modalActions');
            
            // Limpiar acciones anteriores
            modalActions.innerHTML = ''; 

            // Llenar datos básicos
            modalTitle.innerText = info.event.title;
            const start = info.event.start ? info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
            const end = info.event.end ? info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
            modalTime.innerText = `${info.event.start.toLocaleDateString()} | ${start} - ${end}`;

            if (props.type === 'appointment') {
                // --- LÓGICA PARA CITAS ---
                modalResponsible.innerText = `Cliente: ${props.client} | Estado: ${props.status.toUpperCase()}`;
                modalDescription.innerText = props.reason || 'Sin motivo';
                
                if (props.view_url) { // Usamos la URL que viene del backend
                    modalActions.innerHTML = `
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <a href="${props.view_url}" class="btn btn-primary">Gestionar Cita</a>
                    `;
                } else {
                    modalActions.innerHTML = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`;
                }

            } else if (props.type === 'task') {
                // --- LÓGICA MEJORADA PARA TAREAS ---
                
                // 1. Asignación
                let assignmentText = '';
                if (props.assigned_to) {
                    assignmentText = `Asignada a: <strong>${props.assigned_to}</strong>`;
                } else if (props.assigned_specialty) {
                    assignmentText = `Asignada a especialidad: <strong>${props.assigned_specialty}</strong>`;
                } else {
                    assignmentText = 'Asignación: <strong>General</strong>';
                }
                modalResponsible.innerHTML = assignmentText;

                // 2. Descripción y creador
                modalDescription.innerHTML = `
                    <p class="mb-2"><strong>Notas:</strong> ${props.notes || 'Sin notas'}</p>
                    <p class="text-muted small mt-3"><em>Tarea creada por: ${props.created_by}</em></p>
                `;

                // 3. Botones de Acción
                if (props.can_edit) { // Si es ADMIN
                    const deleteUrl = `/tareas/${props.source_id}`;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    modalActions.innerHTML = `
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <a href="${props.edit_url}" class="btn btn-primary me-2">Editar</a>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta tarea?');" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    `;
                } else { // Si es VETERINARIO
                    modalActions.innerHTML = `
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <a href="${props.view_url}" class="btn btn-info text-white">Ver Detalles</a>
                    `;
                }
            }
            
            // Mostrar el modal
            const modalEl = document.getElementById('eventDetailModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });
    calendar.render();
});