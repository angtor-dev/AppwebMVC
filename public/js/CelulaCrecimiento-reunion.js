$(document).ready(function () {

    let choices;

    const dataTable = $('#celulaDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/CelulaCrecimiento/Reunion',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre' },
            { data: 'fecha' },
            {
                defaultContent: `
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-secondary">Info</button>
            <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            `}

        ],
    })

    $('#celulaDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('inf_codigocelulacrecimiento').textContent = datos.codigo;
        document.getElementById('inf_fecha').textContent = datos.fecha;
        document.getElementById('inf_tematica').textContent = datos.tematica;
        document.getElementById('inf_semana').textContent = datos.semana;
        document.getElementById('inf_generosidad').textContent = datos.generosidad;
        document.getElementById('inf_infantil').textContent = datos.infantil;
        document.getElementById('inf_juvenil').textContent = datos.juvenil;
        document.getElementById('inf_adulto').textContent = datos.adulto;
        document.getElementById('inf_actividad').textContent = datos.actividad;
        document.getElementById('inf_observaciones').textContent = datos.observaciones;

    })

    $('#celulaDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();


        document.getElementById('idreunioncrecimiento').textContent = datos.id;
        document.getElementById('fecha').value = datos.fecha;
        document.getElementById('tematica').value = datos.tematica;
        document.getElementById('semana').value = datos.semana;
        document.getElementById('generosidad').value = datos.generosidad;
        document.getElementById('infantil').value = datos.infantil;
        document.getElementById('juvenil').value = datos.juvenil;
        document.getElementById('adulto').value = datos.adulto;
        document.getElementById('actividad').value = datos.actividad;
        document.getElementById('observaciones').value = datos.observaciones;
        Listar_celulas(datos.idCelulaCrecimiento);

    })



    $('#celulaDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a esta reunion otra vez!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '¡Si, estoy seguro!',
            confirmButtonColor: '#007bff',
            cancelButtonText: '¡No, cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {


                $.ajax({
                    type: "POST",
                    url: "http://localhost/AppwebMVC/CelulaCrecimiento/Reunion",
                    data: {

                        eliminar: 'eliminar',
                        id: datos.id,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        dataTable.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'La reunion ha sido borrada',
                            showConfirmButton: false,
                            timer: 2000,
                        })


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
            }
        });
    });



    function Listar_celulas(idCelulaCrecimiento) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaCrecimiento/Reunion",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                let data = JSON.parse(response);

                console.log(data);

                let selector = document.getElementById('idCelulaCrecimiento');


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
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices.setChoiceByValue(idCelulaCrecimiento.toString());

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
    }

    




    //////////////////////////// ACTUALIZAR DATOS DE REUNION ////////////////////////////

    const expresiones_regulares2 = {

        idCelulaCrecimiento: /^[1-9]\d*$/, // Números enteros mayores a 0
        tematica: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        infantil: /^[0-9]\d*$/,
        juvenil: /^[0-9]\d*$/,
        adulto: /^[0-9]\d*$/,
        actividad: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        observaciones: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        fecha: /^\d{4}-\d{2}-\d{2}$/
    };

    const validationStatus2 = {

        idCelulaCrecimiento: true,
        tematica: true,
        semana: true,
        generosidad: true,
        infantil: true,
        juvenil: true,
        adulto: true,
        actividad: true,
        observaciones: true,
        fecha: true
    };

    // Validar idCelulaCrecimiento
    const idCelulaCrecimiento = document.getElementById("idCelulaCrecimiento");
    idCelulaCrecimiento.addEventListener('change', () => {
        if (!expresiones_regulares2.idCelulaCrecimiento.test(idCelulaCrecimiento.value)) {
            document.getElementById("msj_idCelulaCrecimiento").classList.remove("d-none");
            validationStatus2.idCelulaCrecimiento = false;
        } else {
            document.getElementById("msj_idCelulaCrecimiento").classList.add("d-none");
            validationStatus2.idCelulaCrecimiento = true;
        }
    })


    // Validar fecha
    const fecha = document.getElementById("fecha");
    fecha.addEventListener('change', () => {
        if (!expresiones_regulares2.fecha.test(fecha.value)) {
            document.getElementById("msj_fecha").classList.remove("d-none");
            validationStatus2.fecha = false;
        }else{
            document.getElementById("msj_fecha").classList.add("d-none");
            validationStatus2.fecha = true;
        }
    })

    // Validar tematica
    const tematica = document.getElementById("tematica");
    tematica.addEventListener('keyup', () => {
        if (!expresiones_regulares2.tematica.test(tematica.value)) {
            document.getElementById("msj_tematica").classList.remove("d-none");
            validationStatus2.tematica = false;
        } else {
            document.getElementById("msj_tematica").classList.add("d-none");
            validationStatus2.tematica = true;
        }
    })
    
    // Validar semana
    const semana = document.getElementById("semana");
    semana.addEventListener('keyup', () => {
        if (!expresiones_regulares2.semana.test(semana.value)) {
            document.getElementById("msj_semana").classList.remove("d-none");
            validationStatus2.semana = false;
        } else {
            document.getElementById("msj_semana").classList.add("d-none");
            validationStatus2.semana = true;
        }
    })
    
    // Validar generosidad
    const generosidad = document.getElementById("generosidad");
    generosidad.addEventListener('keyup', () => {
        if (!expresiones_regulares2.generosidad.test(generosidad.value)) {
            document.getElementById("msj_generosidad").classList.remove("d-none");
            validationStatus2.generosidad = false;
        } else {
            document.getElementById("msj_generosidad").classList.add("d-none");
            validationStatus2.generosidad = true;
        }
    })


    // Validar infantil
    const infantil = document.getElementById("infantil");
    infantil.addEventListener('keyup', () => {
        if (!expresiones_regulares2.infantil.test(infantil.value)) {
            document.getElementById("msj_infantil").classList.remove("d-none");
            validationStatus2.infantil = false;
        } else {
            document.getElementById("msj_infantil").classList.add("d-none");
            validationStatus2.infantil = true;
        }
    })
    

    // Validar juvenil
    const juvenil = document.getElementById("juvenil");
    juvenil.addEventListener('keyup', () => {
        if (!expresiones_regulares2.juvenil.test(juvenil.value)) {
            document.getElementById("msj_juvenil").classList.remove("d-none");
            validationStatus2.juvenil = false;
        } else {
            document.getElementById("msj_juvenil").classList.add("d-none");
            validationStatus2.juvenil = true;
        }
    })
    

    // Validar adulto
    const adulto = document.getElementById("adulto");
    adulto.addEventListener('keyup', () => {
        if (!expresiones_regulares2.adulto.test(adulto.value)) {
            document.getElementById("msj_adulto").classList.remove("d-none");
            validationStatus2.adulto = false;
        } else {
            document.getElementById("msj_adulto").classList.add("d-none");
            validationStatus2.adulto = true;
        }
    })
    

    // Validar actividad
    const actividad = document.getElementById("actividad");
    actividad.addEventListener('keyup', () => {
        if (!expresiones_regulares2.actividad.test(actividad.value)) {
            document.getElementById("msj_actividad").classList.remove("d-none");
            validationStatus2.actividad = false;
        } else {
            document.getElementById("msj_actividad").classList.add("d-none");
            validationStatus2.actividad = true;
        }
    })
    

    // Validar observaciones
    const observaciones = document.getElementById("observaciones");
    observaciones.addEventListener('keyup', () => {
        if (!expresiones_regulares2.observaciones.test(observaciones.value)) {
            document.getElementById("msj_juvenil").classList.remove("d-none");
            validationStatus2.observaciones = false;
        } else {
            document.getElementById("msj_observaciones").classList.add("d-none");
            validationStatus2.observaciones = true;
        }
    })


    const form2 = document.getElementById("formularioReunion");

    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        const id = document.getElementById('idreunioncrecimiento').textContent;

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaCrecimiento/Reunion",
                data: {

                    editar: 'editar',
                    id: id,
                    idCelulaCrecimiento: idCelulaCrecimiento.value,
                    fecha: fecha.value,
                    tematica: tematica.value,
                    semana: semana.value,
                    generosidad: generosidad.value,
                    infantil: infantil.value,
                    juvenil: juvenil.value,
                    adulto: adulto.value,
                    actividad: actividad.value,
                    observaciones: observaciones.value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo correctamente la reunion',
                        showConfirmButton: false,
                        timer: 2000,
                    })

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
            });
        }
    });

});

