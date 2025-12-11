document.addEventListener('DOMContentLoaded', function() {
    
    const inputFoto = document.getElementById('foto');
    const previewImg = document.getElementById('preview');
    const placeholder = document.querySelector('.upload-placeholder');

    // Verificamos que existan los elementos (para evitar errores en otras páginas)
    if (inputFoto && previewImg && placeholder) {
        
        inputFoto.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Asignar la imagen cargada al src
                    previewImg.src = e.target.result;
                    
                    // Mostrar la etiqueta img
                    previewImg.style.display = 'block';
                    
                    // Ocultar el texto de "Haz clic para subir"
                    placeholder.style.opacity = '0';
                }

                reader.readAsDataURL(file);
            } else {
                // Si el usuario cancela la selección
                previewImg.src = '#';
                previewImg.style.display = 'none';
                placeholder.style.opacity = '1';
            }
        });
    }
});