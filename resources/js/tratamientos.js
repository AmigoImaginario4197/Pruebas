/* public/js/tratamientos.js */

document.addEventListener('DOMContentLoaded', function() {
    // Si estamos en CREATE y no hay medicamentos, agregar uno vacío por defecto
    const container = document.getElementById('medicamentos-container');
    if (container && container.children.length === 0) {
        agregarMedicamento();
    }
});

// Definida en window para asegurar que el onclick del HTML la encuentre
window.agregarMedicamento = function() {
    const container = document.getElementById('medicamentos-container');
    const template = document.getElementById('medicamento-template');
    
    if (container && template) {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    } else {
        console.error('Error: No se encuentra el contenedor o el template de medicamentos.');
    }
};

window.eliminarFila = function(btn) {
    const row = btn.closest('.medicamento-row');
    if (row) {
        row.remove();
    }
};