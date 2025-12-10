document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    // Buscamos el atributo 'data-errors' que pusimos en el body
    const errorsJson = body.dataset.errors;

    if (errorsJson && errorsJson !== '[]') {
        try {
            const errors = JSON.parse(errorsJson);
            if (errors.length > 0) {
                let errorList = '<ul style="text-align: left; list-style-position: inside; padding-left: 0;">';
                errors.forEach(error => {
                    errorList += `<li>${error}</li>`;
                });
                errorList += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: errorList,
                });
            }
        } catch (e) {
            console.error("Error al parsear los errores JSON:", e);
        }
    }

    // Buscamos el atributo 'data-success-message'
    const successMessage = body.dataset.successMessage;

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: successMessage,
            timer: 2500,
            showConfirmButton: false
        });
    }
});