$(document).ready(function () {

    let cedula = null;
    let respuesta = null;
    let correo = null;

    document.getElementById('verificarCedula').addEventListener('click', () => {
        const cedulaRecovery = document.getElementById('cedulaRecovery').value;

        Swal.showLoading()
        setTimeout(() => {
            Swal.close();
            if (cedulaRecovery !== '' && cedulaRecovery !== null) {
                $.ajax({
                    type: "POST",
                    url: '/AppwebMVC/Login/Index',
                    data: {
                        recovery: 'recovery',
                        cedulaRecovery: cedulaRecovery
                    },
                    success: function (response) {
                        const datos = JSON.parse(response);

                        $('#modalRecovery').modal('hide');
                        $('#modalPreguntaRecovery').modal('show');

                        document.getElementById('preguntaRecovery').textContent = datos['pregunta'];
                        cedula = datos['cedula'];
                        respuesta = datos['respuesta'];
                        correo = datos['correo'];

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);

                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Denegado',
                                    text: jsonResponse.msj,
                                    showConfirmButton: true,
                                })
                            } else {
                                const respuesta = JSON.stringify(jsonResponse, null, 2)
                                Swal.fire({
                                    background: 'red',
                                    color: '#fff',
                                    title: respuesta,
                                    showConfirmButton: true,
                                })
                            }
                        } else {
                            alert('Error desconocido: ' + textStatus);
                        }
                    }
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La cedula ingresada es invalida',
                    text: 'Verifique bien sus datos antes de ser enviado',
                    showConfirmButton: true,
                })
            }
        }, 2000);
    })


    $('#enviarRecovery').on('click', () => {
        const respuestaRecovery = document.getElementById('respuestaRecovery').value;
        
        if (cedula !== null && respuesta !== null) {
            if (respuestaRecovery == respuesta) {
                $.ajax({
                    type: "POST",
                    url: '/AppwebMVC/Login/Index',
                    data: {
                        sendRecoveryRespuesta: 'sendRecoveryRespuesta',
                        cedulaRecovery: cedula,
                        respuesta: respuestaRecovery,
                        correo: correo,
                    },
                    success: function (response) {
                        const datos = JSON.parse(response);

                        $('#modalPreguntaRecovery').modal('hide');

                        Swal.showLoading()
                        setTimeout(() => {
                            Swal.close();

                            Swal.fire({
                                icon: 'success',
                                title: datos.msj,
                                text: datos.text,
                                showConfirmButton: true,
                            })

                            document.getElementById('preguntaRecovery').textContent = '';
                            email = null;
                            respuesta = null;
                        }, 1500);

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);

                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Denegado',
                                    text: jsonResponse.msj,
                                    showConfirmButton: true,
                                })
                            } else {
                                const respuesta = JSON.stringify(jsonResponse, null, 2)
                                Swal.fire({
                                    background: 'red',
                                    color: '#fff',
                                    title: respuesta,
                                    showConfirmButton: true,
                                })
                            }
                        } else {
                            alert('Error desconocido: ' + textStatus);
                        }
                    }
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La respuesta enviada es incorrecta',
                    showConfirmButton: true,
                    timer: 2000,
                })
            }

        } else {
            Swal.fire({
                icon: 'error',
                title: 'DENEGADO',
                showConfirmButton: true,
            })
        }
    });
})



// Objeto para almacenar el estado de validación de cada campo
const validationRegister = {
    nombre: false,
    apellido: false,
    telefono: false,
    direccion: false,
    cedula: false,
    sexo: false,
    estadoCivil: false,
    password: false,
    passwordRepeat: false,
    fechaNacimiento: false,
    direccion: false,
    pregunta: false,
    respuesta: false,
}

// Expresiones regulares para validar cada campo
const regexValidaciones = {
    nombre: /^[a-zA-Z\s]{1,40}$/,
    apellido: /^[a-zA-Z\s]{1,40}$/,
    telefono: /^\d{7,14}$/,
    cedula: /^\d{7,10}$/,
    password: /^.{8,16}$/,
    fechaNacimiento: /^\d{4}-\d{2}-\d{2}$/,
    direccion: /^.{1,100}$/,
    preguntaSecurity: /^.{1,100}$/,
    respuestaSecurity: /^.{1,100}$/,
    estadoCivil: /^[SCDV]$/,
    sexo: /^(hombre|mujer)$/
};


// Función para validar un campo dado su ID y el ID de su mensaje de error correspondiente
function validarCampo(campoId, regex) {
    const campoInput = document.getElementById(campoId);
    const campoError = document.getElementById(`${campoId}Error`);
    if (!regex.test(campoInput.value)) {
        campoError.classList.remove('d-none');
        validationRegister[campoId] = false;
    } else {
        campoError.classList.add('d-none');
        validationRegister[campoId] = true;
    }
}

// Agregar eventos keyup para validar en tiempo real
for (let campoId in regexValidaciones) {
    document.getElementById(campoId).addEventListener('keyup', function() {
        validarCampo(campoId, regexValidaciones[campoId]);
    });
}

// Validación especial para la contraseña y la repetición de contraseña
const passwordInput = document.getElementById('password');
const passwordRepeatInput = document.getElementById('passwordRepeat');

passwordInput.addEventListener('keyup', function() {
    validarPassword();
    validarCoincidenciaContraseñas();
});

passwordRepeatInput.addEventListener('keyup', validarCoincidenciaContraseñas);

// Función para validar la contraseña
function validarPassword() {
    const passwordError = document.getElementById('passwordError');
    if (!regexValidaciones.password.test(passwordInput.value)) {
        passwordError.classList.remove('d-none');
        validationRegister.password = false;
    } else {
        passwordError.classList.add('d-none');
        validationRegister.password = true;
    }
}

// Función para validar que la contraseña coincida con la repetición de contraseña
function validarCoincidenciaContraseñas() {
    const passwordRepeatError = document.getElementById('passwordRepeatError');
    if (passwordInput.value !== passwordRepeatInput.value) {
        passwordRepeatError.classList.remove('d-none');
        validationRegister.passwordRepeat = false;
    } else {
        passwordRepeatError.classList.add('d-none');
        validationRegister.passwordRepeat = true;
    }
}

// Validación especial para el campo de estado civil (select)
const estadoCivilInput = document.getElementById('estadoCivil');
const estadoCivilError = document.getElementById('estadoCivilError');

estadoCivilInput.addEventListener('change', function() {
    if (estadoCivilInput.value === "" || !regexValidaciones.estadoCivil.test(estadoCivilInput.value)) {
        estadoCivilError.classList.remove('d-none');
        validationRegister.estadoCivil = false;
    } else {
        estadoCivilError.classList.add('d-none');
        validationRegister.estadoCivil = true;
    }
});

// Validación especial para el campo de sexo (select)
const sexoInput = document.getElementById('sexo');
const sexoError = document.getElementById('sexoError');

sexoInput.addEventListener('change', function() {
    if (sexoInput.value === "" || !regexValidaciones.sexo.test(sexoInput.value)) {
        sexoError.classList.remove('d-none');
        validationRegister.sexo = false;
    } else {
        sexoError.classList.add('d-none');
        validationRegister.sexo = true;
    }
});






