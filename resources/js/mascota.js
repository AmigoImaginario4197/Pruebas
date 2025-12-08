// Definimos la función en el objeto window para que el HTML la pueda llamar
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