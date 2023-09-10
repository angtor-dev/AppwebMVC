
$(document).ready(function () {

    let choices;
    function Listar_Pastores() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Sedes/Registrar",
            data: {

                listaPastores: 'listaPastores',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idPastor');
                // Crear y agregar la opción tipo "placeholder"
                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Selecciona un pastor';
                placeholderOption.disabled = true;
                placeholderOption.selected = true;
                selector.appendChild(placeholderOption);

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });
                choices = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }

    Listar_Pastores();






    /////////////////////////////////// REGISTRAR SEDE /////////////////////////////////////

    let validaciones = {
        idPastor: false,
        nombre: false,
        direccion: false,
        estado: false
    };

    const expresiones = {
        nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/,
        id: /^\d{1,9}$/,
        texto: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/,
        estado: ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
            "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TÁCH", "TRU", "VAR", "YAR", "ZUL"]
    }

    // Validación del ID del pastor
    const idPastor = document.getElementById('idPastor');
    idPastor.addEventListener('change', (e) => {
        if (expresiones.id.test(idPastor.value)) {
            validaciones.idPastor = true;
            $("#idPastor").removeClass("is-invalid");
            $("#msj_idPastor").addClass("d-none");
        } else {
            validaciones.idPastor = false;
            $("#idPastor").addClass("is-invalid");
            $("#msj_idPastor").removeClass("d-none");
        }
    })


    // Validación del nombre de la sede
    const nombre = document.getElementById('nombre');
    nombre.addEventListener('keyup', (e) => {
        if (expresiones.nombre.test(nombre.value)) {
            validaciones.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#msj_nombre").addClass("d-none");
        } else {
            validaciones.nombre = false;
            $("#nombre").addClass("is-invalid");
            $("#msj_nombre").removeClass("d-none");
        }
    })


    // Validación de la dirección
    const direccion = document.getElementById('direccion');
    direccion.addEventListener('keyup', (e) => {
        if (expresiones.texto.test(direccion.value)) {
            validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#msj_direccion").addClass("d-none");
        } else {
            validaciones.direccion = false;
            $("#direccion").addClass("is-invalid");
            $("#msj_direccion").removeClass("d-none");
        }
    })


    // Validación del estado
    const estado = document.getElementById('estado');
    estado.addEventListener('change', (e) => {
        if (expresiones.estado.includes(estado.value)) {
            validaciones.estado = true;
            $("#estado").removeClass("is-invalid");
            $("#msj_estado").addClass("d-none");
        } else {
            validaciones.estado = false;
            $("#estado").addClass("is-invalid");
            $("#msj_estado").removeClass("d-none");
        }
    })
    


    $("#formulario").submit(function (event) {
        // Previene el comportamiento predeterminado del formulario
        event.preventDefault();

        // Verificar si todas las validaciones son correctas
        if (Object.values(validaciones).every(val => val)) {
            // Si todas las validaciones son correctas, realiza la petición AJAX
            // ... (tu código AJAX va aquí)
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/Sedes/Registrar",
                data: {

                    registrar: 'registrar',
                    idPastor: idPastor.value,
                    nombre: nombre.value,
                    direccion: direccion.value,
                    estado: estado.value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);

                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    document.getElementById('nombre').value = ''
                    document.getElementById('direccion').value = ''
                    document.getElementById('estado').value = ''
                    choices.setChoiceByValue('')
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
            });

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Formulario invalido. Verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

});