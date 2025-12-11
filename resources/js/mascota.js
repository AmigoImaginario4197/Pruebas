window.previewImage = function(event) {
    const reader = new FileReader();
    const output = document.getElementById('image-preview');

    if (event.target.files.length > 0) {
        reader.onload = function() {
            if (output) {
                output.src = reader.result;
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    
    // CÁLCULO DE EDAD AUTOMÁTICA
    const inputFecha = document.getElementById('fecha_nacimiento');
    const inputEdad = document.getElementById('edad');

    if (inputFecha && inputEdad) {
        inputFecha.addEventListener('change', function() {
            const fechaNacimiento = new Date(this.value);
            const hoy = new Date();
            
            // Si la fecha es futura, limpiamos
            if (fechaNacimiento > hoy) {
                alert("La fecha de nacimiento no puede ser futura.");
                this.value = "";
                inputEdad.value = "";
                return;
            }

            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();

            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }

            inputEdad.value = Math.max(0, edad);
        });
    }

    // VISTA PREVIA DE IMAGEN
    const inputFoto = document.getElementById('foto');
    const imgPreview = document.getElementById('photo-preview');

    if (inputFoto && imgPreview) {
        inputFoto.addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    // Importante: Quitamos d-none y forzamos display block
                    imgPreview.classList.remove('d-none'); 
                    imgPreview.style.display = 'block'; 
                }
                reader.readAsDataURL(file);
            } else {
                imgPreview.src = '#';
                imgPreview.style.display = 'none';
            }
        });
    }
});