
let encrypt = new JSEncrypt();
let cedula = null;
let respuesta = null;


let choices;


let cedulaV = false;
let claveV = false;
let cedulaRecoverV = false;

$("#cedulaLogin").keyup(function (event) {

    let cedula = $("#cedulaLogin");
    let div = $("#msjcedulaLogin");
    if ((/^\d{7,9}(-\d{1,3})?$/).test(cedula.val())) {
        cedula.removeClass("is-invalid");
        div.text("");
        cedulaV = true;
    } else {
        cedula.addClass("is-invalid");
        div.text("Formato incorrecto");
        cedulaV = false;
    }

});


$("#claveLogin").keyup(function (event) {

    let clave = $("#claveLogin");
    let div = $("#msjclaveLogin");
    if ((/^[^\s]*$/).test(clave.val())) {
        claveV = true;
        clave.removeClass('is-invalid');
        div.text('');
    } else {
        claveV = false;
        clave.addClass('is-invalid');
        div.text('Este campo no acepta espacios en blanco');
    }

});


$("#showPassword").on('click', function (event) {
    var passwordInput = document.getElementById('claveLogin');
    var toggleIcon = $('#showPassword');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.addClass('fa-eye-slash');
        toggleIcon.removeClass('fa-eye');
    } else {
        passwordInput.type = 'password';

        toggleIcon.addClass('fa-eye');
        toggleIcon.removeClass('fa-eye-slash');
    }
});


let formLogin = document.getElementById('loginForm');
formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();

    const url = '?getKey';
    const response = await fetch(url);
    const publicKey = await response.json();

    encrypt.setPublicKey(publicKey);

    const json = {
        cedula: document.getElementById('cedulaLogin').value,
        clave: document.getElementById('claveLogin').value,
    }

    let jsonString = JSON.stringify(json);
    let encrypted = encrypt.encrypt(jsonString);

    if (cedulaV === true && claveV === true) {
        $.ajax({
            type: "POST",
            url: '',
            data: {
                encryptedLogin: encrypted,
            },
            success: function (response) {
                window.location.replace("/AppwebMVC/");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseText) {
                    let jsonResponse = JSON.parse(jqXHR.responseText);

                    if (jsonResponse.msj) {
                        Swal.fire({
                            icon: 'error',
                            title: jsonResponse.msj,
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
            title: 'Debes llenar el formulario correctamente',
            showConfirmButton: false,
            timer: 2000,
        })
    }
})


$("#cedulaRecovery").keyup(function (event) {

    let cedula = $("#cedulaRecovery");
    let div = $("#msjcedulaRecovery");
    if ((/^\d{7,9}(-\d{1,3})?$/).test(cedula.val())) {
        cedula.removeClass("is-invalid");
        div.text("");
        cedulaRecoverV = true;
    } else {
        cedula.addClass("is-invalid");
        div.text("Formato incorrecto");
        cedulaRecoverV = false;
    }

});


let verificarCedula = document.getElementById('verificarCedula');
verificarCedula.addEventListener('click', async (e) => {
    e.preventDefault();

    const url = '?getKey';
    const response = await fetch(url);
    const publicKey = await response.json();
    encrypt.setPublicKey(publicKey);

    const cedulaRecovery = document.getElementById('cedulaRecovery').value;

    Swal.showLoading()
    setTimeout(() => {
        Swal.close();
        if (cedulaRecovery !== '' && cedulaRecovery !== null) {

            if (cedulaRecoverV === true) {



                const json = {
                    cedula: cedulaRecovery,
                }

                let jsonString = JSON.stringify(json);
                let encrypted = encrypt.encrypt(jsonString);

                $.ajax({
                    type: "POST",
                    url: '',
                    data: {
                        encryptedCedulaRecovery: encrypted,
                    },
                    success: function (response) {
                        const datos = JSON.parse(response);

                        $('#modalRecovery').modal('hide');
                        $('#modalPreguntaRecovery').modal('show');

                        document.getElementById('preguntaRecovery').textContent = datos['preguntaSecurity'];
                        cedula = cedulaRecovery;
                        respuesta = datos['respuestaSecurity'];


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);

                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'DENEGADO',
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
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La cedula ingresada es invalida',
                    text: 'Verifique bien sus datos antes de ser enviado',
                    showConfirmButton: true,
                })
            }

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


$('#enviarRecovery').on('click', async (e) => {
    e.preventDefault();
    const respuestaRecovery = document.getElementById('respuestaRecovery').value;

    Swal.close();
    if (cedula !== null && respuesta !== null) {


        if (respuestaRecovery == respuesta) {

            const json = {
                cedulaRecovery: cedula,
                respuesta: respuestaRecovery,

            }

            Swal.showLoading()
            setTimeout(() => {

                let jsonString = JSON.stringify(json);
                let encrypted = encrypt.encrypt(jsonString);
                $.ajax({
                    type: "POST",
                    url: '',
                    data: {
                        sendRecoveryRespuestaWeb: encrypted,
                    },
                    success: function (response) {
                        const datos = JSON.parse(response);

                        $('#modalPreguntaRecovery').modal('hide');


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


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);

                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'DENEGADO',
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
            }, 4000);
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




// Objeto para almacenar el estado de validación de cada campo
const validationRegister = {
    nombre: false,
    apellido: false,
    telefono: false,
    direccion: false,
    cedula: false,
    //sexo: false,
    estadoCivil: false,
    password: false,
    passwordRepeat: false,
    fechaNacimiento: false,
    direccion: false,
    preguntaSecurity: false,
    respuestaSecurity: false,
    correo: false,
    idSede: false
}

// Expresiones regulares para validar cada campo
const regexValidaciones = {
    nombre: /^[a-zA-Z\s]{1,50}$/,
    apellido: /^[a-zA-Z\s]{1,50}$/,
    telefono: /^\d{7,14}$/,
    cedula: /^\d{7,10}$/,
    password: /^.{8,16}$/,
    fechaNacimiento: /^\d{4}-\d{2}-\d{2}$/,
    direccion: /^.{1,100}$/,
    preguntaSecurity: /^.{1,100}$/,
    respuestaSecurity: /^.{1,100}$/,
    estadoCivil: /^[SCDV]$/,
    correo: /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/,
    //sexo: /^(hombre|mujer)$/
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
    document.getElementById(campoId).addEventListener('keyup', function () {
        validarCampo(campoId, regexValidaciones[campoId]);
    });
}

// Validación especial para la contraseña y la repetición de contraseña
const passwordInput = document.getElementById('password');
const passwordRepeatInput = document.getElementById('passwordRepeat');

passwordInput.addEventListener('keyup', function () {
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

estadoCivilInput.addEventListener('change', function () {
    if (estadoCivilInput.value === "" || !regexValidaciones.estadoCivil.test(estadoCivilInput.value)) {
        estadoCivilError.classList.remove('d-none');
        validationRegister.estadoCivil = false;
    } else {
        estadoCivilError.classList.add('d-none');
        validationRegister.estadoCivil = true;
    }
});

const fechaNacimientoInput = document.getElementById('fechaNacimiento');
const fechaNacimientoError = document.getElementById('fechaNacimientoError');

fechaNacimientoInput.addEventListener('change', function () {
    if (fechaNacimientoInput.value === "" || !regexValidaciones.fechaNacimiento.test(fechaNacimientoInput.value)) {
        fechaNacimientoError.classList.remove('d-none');
        validationRegister.fechaNacimiento = false;
    } else {
        fechaNacimientoError.classList.add('d-none');
        validationRegister.fechaNacimiento = true;
    }
});

// Validación especial para el campo de Sede (select)
const idSedeInput = document.getElementById('idSede');
const idSedeError = document.getElementById('idSedeError');

idSedeInput.addEventListener('change', function () {

    let valor = $('#idSede').val();

    // Convertir a número entero
    const numero = parseInt(valor, 10);

    if (numero === "" || !Number.isInteger(numero)) {
        idSedeError.classList.remove('d-none');
        validationRegister.idSede = false;
    } else {
        idSedeError.classList.add('d-none');
        validationRegister.idSede = true;
    }
});

// Validación especial para el campo de sexo (select)
/*const sexoInput = document.getElementById('sexo');
const sexoError = document.getElementById('sexoError');

sexoInput.addEventListener('change', function() {
    if (sexoInput.value === "" || !regexValidaciones.sexo.test(sexoInput.value)) {
        sexoError.classList.remove('d-none');
        validationRegister.sexo = false;
    } else {
        sexoError.classList.add('d-none');
        validationRegister.sexo = true;
    }
});*/


//Inicializacion del Choices JS
$('#registerButton').on('click', () => {
    Listar_SedesRegistrar();
})

function Listar_SedesRegistrar() {

    $.ajax({
        type: "GET",
        url: "/AppwebMVC/Login/Index",
        data: {
            getSedes: 'getSedes',
        },
        success: function (response) {
            let data = JSON.parse(response);

            if (data.length > 0) {

                let selector = document.getElementById('idSede');
                selector.innerHTML = '';

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Seleccione una Sede';
                placeholderOption.disabled = true;
                placeholderOption.selected = true;
                selector.appendChild(placeholderOption);

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });

                // Destruir la instancia existente si la hay
                if (choices) {
                    choices.destroy();
                }
                choices = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices.setChoiceByValue('');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);

            if (jqXHR.responseText) {
                let jsonResponse = JSON.parse(jqXHR.responseText);

                if (jsonResponse.msj) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DENEGADO',
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
}





// Obtener el formulario
const formulario = document.getElementById('registerForm');

$('#register').on('click', async (e) => {

    e.preventDefault();

    const url = '?getKey';
    const response = await fetch(url);
    const publicKey = await response.json();

    encrypt.setPublicKey(publicKey);

    // Serializar los datos del formulario y convertirlos en un objeto
    let datosFormulario = $(formulario).serializeArray();

    // Objeto para almacenar los datos encriptados
    let datosEncriptados = {};

    // Recorrer cada elemento del formulario serializado
    datosFormulario.forEach(function (campo) {
        // Convertir el valor del campo a JSON
        let valorJson = JSON.stringify(campo.value);

        // Encriptar el valor
        let valorEncriptado = encrypt.encrypt(valorJson);

        // Almacenar el valor encriptado en el objeto usando el nombre del campo como clave
        datosEncriptados[campo.name] = valorEncriptado;
    });

    // Ahora 'datosEncriptados' contiene los valores encriptados de cada campo


    if (validarTodosCampos(validationRegister)) {
        $.ajax({
            type: "POST",
            url: '',
            data: {
                encryptedRegister: datosEncriptados,
            },
            success: function (response) {
                console.log(response);
                const respuesta = JSON.parse(response);

                Swal.showLoading()
                setTimeout(() => {
                    $('#modalRegister').modal('hide');

                    resetearCampos(validationRegister)
                    document.getElementById("registerForm").reset()
                    ocultarMensajesError()

                    Swal.close();

                    Swal.fire({
                        icon: 'success',
                        title: respuesta.msj,
                        showConfirmButton: true,
                    })

                }, 1500);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                if (jqXHR.responseText) {
                    let jsonResponse = JSON.parse(jqXHR.responseText);

                    if (jsonResponse.msj) {
                        Swal.fire({
                            icon: 'error',
                            title: 'DENEGADO',
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
        console.log(validationRegister);
        Swal.fire({
            icon: 'error',
            title: 'DENEGADO',
            text: 'Ingrese los campos correctamente antes de ser enviados',
            showConfirmButton: true,
        })
    }
})

// Función para validar si todos los campos están en true
function validarTodosCampos(objeto) {
    for (let campo in objeto) {
        if (!objeto[campo]) {
            return false; // Si encuentra al menos un campo en false, devuelve false
        }
    }
    return true; // Si todos los campos están en true, devuelve true
}

// Función para restablecer todos los campos a false
function resetearCampos(objeto) {
    for (let campo in objeto) {
        objeto[campo] = false;
    }
}

// Función para vaciar los campos de un formulario
function vaciarFormulario(formulario) {
    const elementosFormulario = formulario.elements;
    for (let i = 0; i < elementosFormulario.length; i++) {
        const elemento = elementosFormulario[i];
        if (elemento.type !== "submit") { // No vaciar el botón de enviar
            elemento.value = ""; // Establecer el valor del campo en cadena vacía
        }
    }
}

// Función para ocultar todos los mensajes de error
function ocultarMensajesError() {
    const mensajesError = document.querySelectorAll('.text-danger');
    mensajesError.forEach(mensaje => {
        mensaje.classList.add('d-none'); // Agrega la clase d-none para ocultar el mensaje de error
    });
}







