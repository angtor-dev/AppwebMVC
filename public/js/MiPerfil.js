let formulario_original;
let formulario_pregunta_original;

$.ajax({
    type: "GET",
    url: '/AppwebMVC/Usuarios/MiPerfil',
    data: {
        getDatos: 'getDatos',
    },
    success: function (response) {
        const datos = JSON.parse(response);
        const inputs = document.getElementsByClassName('form-control')


        for (let i = 0; i < datos.length; i++) {
            if (inputs[i].tagName == 'telefono' || inputs[i].tagName == 'cedula') {
                inputs[i].value = parseInt(datos[i])
            }
            inputs[i].value = datos[i]
        }

        //Guardando datos originales
        formulario_original = $('#saveDatosForm').serialize();
        formulario_original += "&saveDatos=saveDatos";

        formulario_pregunta_original = $('#savePreguntaSecurityForm').serialize();
        formulario_pregunta_original += "&savePregunta=savePregunta";

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


// Objeto para almacenar el estado de validación de cada campo
const validationSaveDatos = {
    nombre: true,
    apellido: true,
    telefono: true,
    direccion: true,
    cedula: true,
    //sexo: false,
    estadoCivil: true,
    fechaNacimiento: true,
    direccion: true,
    correo: true,
}

const validationSavePregunta = {
    preguntaSecurity: true,
    respuestaSecurity: true,
}

// Expresiones regulares para validar cada campo
const regexValidaciones = {
    nombre: /^[a-zA-Z\s]{1,50}$/,
    apellido: /^[a-zA-Z\s]{1,50}$/,
    telefono: /^\d{7,14}$/,
    cedula: /^\d{7,10}$/,
    fechaNacimiento: /^\d{4}-\d{2}-\d{2}$/,
    direccion: /^.{1,100}$/,
    estadoCivil: /^[SCDV]$/,
    correo: /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/,
    //sexo: /^(hombre|mujer)$/
}

const regexValidaciones2 = {
    preguntaSecurity: /^.{1,100}$/,
    respuestaSecurity: /^.{1,100}$/,
}


// Función para validar un campo dado su ID y el ID de su mensaje de error correspondiente
// Formulario de datos basicos
function validarCampo(campoId, regex) {
    const campoInput = document.getElementById(campoId);
    const campoError = document.getElementById(`${campoId}Error`);
    if (!regex.test(campoInput.value)) {
        campoError.classList.remove('d-none');
        validationSaveDatos[campoId] = false;
    } else {
        campoError.classList.add('d-none');
        validationSaveDatos[campoId] = true;
    }
}

// Función para validar un campo dado su ID y el ID de su mensaje de error correspondiente
// Formulario de pregunta de seguridad
function validarFormulario_pregunta(campoId, regex) {
    const campoInput = document.getElementById(campoId);
    const campoError = document.getElementById(`${campoId}Error`);
    if (!regex.test(campoInput.value)) {
        campoError.classList.remove('d-none');
        validationSavePregunta[campoId] = false;
    } else {
        campoError.classList.add('d-none');
        validationSavePregunta[campoId] = true;
    }
}

// Agregar eventos keyup para validar en tiempo real del formulario de datos basicos
for (let campoId in regexValidaciones) {
    document.getElementById(campoId).addEventListener('keyup', function () {
        validarCampo(campoId, regexValidaciones[campoId]);
    });
}

// Agregar eventos keyup para validar en tiempo real del formulario de pregunta de seguridad
for (let campoId in regexValidaciones2) {
    document.getElementById(campoId).addEventListener('keyup', function () {
        validarFormulario_pregunta(campoId, regexValidaciones2[campoId]);
    });
}


// Validación especial para el campo de estado civil (select)
const estadoCivilInput = document.getElementById('estadoCivil');
const estadoCivilError = document.getElementById('estadoCivilError');

estadoCivilInput.addEventListener('change', function () {
    if (estadoCivilInput.value === "" || !regexValidaciones.estadoCivil.test(estadoCivilInput.value)) {
        estadoCivilError.classList.remove('d-none');
        validationSaveDatos.estadoCivil = false;
    } else {
        estadoCivilError.classList.add('d-none');
        validationSaveDatos.estadoCivil = true;
    }
});



////////// Evento para actualizar datos del perfil /////////

$('#saveDatosForm').on('submit', (e) => {
    e.preventDefault();

    // Serializar los datos del formulario
    let datosFormulario = $('#saveDatosForm').serialize();
    // Agregar el índice adicional
    datosFormulario += "&saveDatos=saveDatos";

    if (datosFormulario === formulario_original) {
        Swal.fire({
            icon: 'info',
            title: 'Oops',
            text: 'No has actualizado datos en ninguno de los campos',
            showConfirmButton: true,
        })
    } else {

        if (validarTodosCampos(validationSaveDatos)) {
            $.ajax({
                type: "POST",
                url: '/AppwebMVC/Usuarios/MiPerfil',
                data: datosFormulario,
                success: function (response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);

                    Swal.showLoading()
                    setTimeout(() => {

                        Swal.fire({
                            icon: 'success',
                            title: respuesta.msj,
                            showConfirmButton: true,
                        })

                        //Guardando datos originales nuevamente
                        formulario_original = $('#saveDatosForm').serialize();
                        formulario_original += "&saveDatos=saveDatos";

                    }, 1000);

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
            Swal.fire({
                icon: 'error',
                title: 'DENEGADO',
                text: 'Ingrese los campos correctamente antes de ser enviados',
                showConfirmButton: true,
            })
        }
    }
})


////////// Evento para actualizar pregunta de seguridad /////////

$('#savePreguntaSecurityForm').on('submit', (e) => {
    e.preventDefault();

    // Serializar los datos del formulario
    let datosFormulario = $('#savePreguntaSecurityForm').serialize();
    // Agregar el índice adicional
    datosFormulario += "&savePregunta=savePregunta";

    if (datosFormulario === formulario_pregunta_original) {
        Swal.fire({
            icon: 'info',
            title: 'Oops',
            text: 'No has actualizado los datos en el formulario',
            showConfirmButton: true,
        })
    } else {

        if (validarTodosCampos(validationSavePregunta)) {
            $.ajax({
                type: "POST",
                url: '/AppwebMVC/Usuarios/MiPerfil',
                data: datosFormulario,
                success: function (response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);

                    Swal.showLoading()
                    setTimeout(() => {

                        Swal.fire({
                            icon: 'success',
                            title: respuesta.msj,
                            showConfirmButton: true,
                        })

                        //Guardando datos originales nuevamente
                        formulario_pregunta_original = $('#savePreguntaSecurityForm').serialize();
                        formulario_pregunta_original += "&savePregunta=savePregunta";

                    }, 1000);

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
            Swal.fire({
                icon: 'error',
                title: 'DENEGADO',
                text: 'Ingrese los campos correctamente antes de ser enviados',
                showConfirmButton: true,
            })
        }
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