document.addEventListener('DOMContentLoaded', function() {
    const vetSelect = document.getElementById('asignado_a_id');
    const espSelect = document.getElementById('especialidad_asignada');

    // Verificamos que los elementos existan para evitar errores en otras páginas
    if (vetSelect && espSelect) {
        
        // Si seleccionas un veterinario, limpia la especialidad
        vetSelect.addEventListener('change', function() {
            if (this.value !== '') {
                espSelect.value = '';
            }
        });

        // Si seleccionas una especialidad, limpia el veterinario
        espSelect.addEventListener('change', function() {
            if (this.value !== '') {
                vetSelect.value = '';
            }
        });
    }
});