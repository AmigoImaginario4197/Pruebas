import * as bootstrap from 'bootstrap';

// Definimos la función en el objeto window para que sea accesible desde el HTML
window.openLogModal = function(boton) {
    // 1. Obtener datos del botón
    let accion = boton.getAttribute('data-accion');
    let usuario = boton.getAttribute('data-usuario');
    let fecha = boton.getAttribute('data-fecha');
    let detalle = boton.getAttribute('data-detalle');

    // 2. Llenar el modal
    document.getElementById('modalAccion').innerText = accion;
    document.getElementById('modalUsuario').innerText = usuario;
    document.getElementById('modalFecha').innerText = fecha;
    document.getElementById('modalDetalle').innerText = detalle;
    
    // 3. Mostrar el modal
    let modalElement = document.getElementById('logModal');
    if (modalElement) {
        let myModal = new bootstrap.Modal(modalElement);
        myModal.show();
    } else {
        console.error('No se encontró el modal con ID: logModal');
    }
};