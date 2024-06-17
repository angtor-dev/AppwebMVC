$(document).ready(function () {

let clave1 = false;
let clave2 = false;

document.getElementById('nuevaPassword').addEventListener('keyup', e => {
    const input = e.currentTarget
    const text = document.getElementById('msjNuevaPassword')
    let value = input.value.trim()

    if (value.length < 6) {
        clave1 = false;
      input.classList.remove('is-valid')
        input.classList.add('is-invalid')
        text.textContent = "Debe poseer al menos 6 caracteres de longitud"
    } else if (!/[a-zA-Z]/.test(value)) {
        clave1 = false;
      input.classList.remove('is-valid')
        input.classList.add('is-invalid')
        text.textContent = "Debe poseer al menos una letra"
    } else if (!/[0-9]/.test(value)) {
        clave1 = false;
      input.classList.remove('is-valid')
         input.classList.add('is-invalid')
        text.textContent = "Debe poseer al menos un numero"
    } else {
        clave1 = true;
      input.classList.remove('is-invalid')
      input.classList.add('is-valid')
        text.textContent = ""
    }
})

document.getElementById('repetirPassword').addEventListener('keyup', e => {
    const claveNueva = document.getElementById('nuevaPassword')
    const input = e.currentTarget
    const text = document.getElementById('msjRepetirPassword')

    if (claveNueva.value != input.value) {
        clave2 = false;
      input.classList.remove('is-valid')
      input.classList.add('is-invalid')
        text.textContent = "Las claves no coinciden"
    } else {
        clave2 = true;
      input.classList.remove('is-invalid')
      input.classList.add('is-valid')
        text.textContent = ""
    }
})

let formLogin = document.getElementById('form1');
formLogin.addEventListener('submit', (e) =>{
    e.preventDefault();
    
    Swal.showLoading()
    setTimeout(() => {
        Swal.close();
    if (clave1 === true && clave2 === true) {
       
        $.ajax({
            type: "POST",
            url: '',
            data: {
                recovery: 'recovery',
                nuevaPassword1: document.getElementById('nuevaPassword').value,
                repetirPassword1: document.getElementById('repetirPassword').value,
                token1: document.getElementById('token').value,
            },
            success: function (response) {
                window.location.replace("/AppwebMVC/PasswordRecovery/RecoveryExitosa");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Verifica primero si hay una respuesta y luego intenta parsearla
                if (jqXHR.responseText) {
                    try {
                        let jsonResponse = JSON.parse(jqXHR.responseText);
                        if (jsonResponse.msj) {
                            Swal.fire({
                                icon: 'error',
                                title: jsonResponse.msj,
                                showConfirmButton: true,
                            });
                        } else {
                            const respuesta = JSON.stringify(jsonResponse, null, 2);
                            Swal.fire({
                                background: 'red',
                                color: '#fff',
                                title: respuesta,
                                showConfirmButton: true,
                            });
                        }
                    } catch (e) {
                        // Si hay un error al parsear el JSON, muestra un mensaje genérico
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al procesar la respuesta del servidor',
                            showConfirmButton: true,
                        });
                    }
                } else {
                    // Si no hay respuesta del servidor, muestra un mensaje genérico
                    Swal.fire({
                        icon: 'error',
                        title: 'Error desconocido: ' + textStatus,
                        showConfirmButton: true,
                    });
                }
            }
            
        })
    } else {

        Swal.fire({
            icon: 'error',
            title: 'Debes llenar el formulario correctamente',
            showConfirmButton: false,
            timer: 2000,
        })
    }
    }, 2000);
})

});