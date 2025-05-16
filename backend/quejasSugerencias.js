
export function guardarCorreoEnElServidor(correo){
    if (!correo.includes('@')) {
        alert("Correo inválido.");
        return;
    }
    alert(`Ya te suscribiste: ${correo} xd`);
}

export function empaquetarElFormulario(form){
    const formulario = document.getElementById(form);
    const formData = new FormData(formulario);

    // Validación personalizada
    const nombre = document.getElementById("NombreFormQS").value;
    const apellido = document.getElementById("ApellidoFormQS").value;
    const email = document.getElementById('EmailFormQS').value;
    const tipo = document.getElementById("TipoFormQS").value;
    const mensaje = document.getElementById("MensajeFormQS").value;
    if(!nombre || !apellido || !email || !tipo || !mensaje){
        alert("Hay campos por llenar en el formulario");
        console.log(tipo);
        return;
    }
    if (!email.includes('@')) {
        alert("Correo inválido.");
        return;
    }

    const archivo = document.getElementById('FileFormQS').files[0];
    if (archivo) {
        const tiposPermitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!tiposPermitidos.includes(archivo.type)) {
        alert("Archivo no permitido. Solo JPG, PNG o PDF.");
        return;
        }

        const tamMax = 2 * 1024 * 1024; // 2MB
        if (archivo.size > tamMax) {
        alert("El archivo es demasiado grande. Máximo 2MB.");
        return;
        }
    }

    alert("Se a enviado xd");

    /* // Enviar con fetch
    fetch('procesar_formulario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert("Respuesta del servidor: " + data);
        formulario.reset(); // Limpia el formulario
    })
    .catch(error => {
        console.error('Error al enviar el formulario:', error);
        alert("Ocurrió un error al enviar el formulario.");
    }); */
}