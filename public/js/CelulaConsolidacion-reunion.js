$(document).ready(function () {

    let dataTable2;
    let choices;
    let choices2;

    const dataTable = $('#celulaDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/CelulaConsolidacion/Reunion',
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
            <button type="button" id="asistencia" data-bs-toggle="modal" data-bs-target="#modal_editarAsistencia" class="btn btn-info">Asistencia</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            `}

        ],
    })

    $('#celulaDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('inf_codigocelulaconsolidacion').textContent = datos.codigo;
        document.getElementById('inf_fecha').textContent = datos.fecha;
        document.getElementById('inf_tematica').textContent = datos.tematica;
        document.getElementById('inf_semana').textContent = datos.semana;
        document.getElementById('inf_generosidad').textContent = datos.generosidad;
        document.getElementById('inf_actividad').textContent = datos.actividad;
        document.getElementById('inf_observaciones').textContent = datos.observaciones;

    })

    $('#celulaDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idreunionconsolidacion').textContent = datos.id;
        document.getElementById('fecha').value = datos.fecha;
        document.getElementById('tematica').value = datos.tematica;
        document.getElementById('semana').value = datos.semana;
        document.getElementById('generosidad').value = datos.generosidad;
        document.getElementById('actividad').value = datos.actividad;
        document.getElementById('observaciones').value = datos.observaciones;

        Listar_celulas(datos.idCelulaConsolidacion)
    })


    let idReunionAsistencia;
    let idCelulaConsolidacionDatatable;
    $('#celulaDatatables tbody').on('click', '#asistencia', function () {
        const datos = dataTable.row($(this).parents()).data();

        idReunionAsistencia = datos.id
        idCelulaConsolidacionDatatable = datos.idCelulaConsolidacion
        Listar_asistencia(datos.id)
        Listar_discipulos_reunion(datos.idCelulaConsolidacion, datos.id)
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
                    url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
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
            }
        });
    });


    function Listar_celulas(idCelulaConsolidacion) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                let data = JSON.parse(response);

                console.log(data);

                let selector = document.getElementById('idCelulaConsolidacion');


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

                choices.setChoiceByValue(idCelulaConsolidacion.toString())

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }


    function Listar_discipulos_reunion(idCelulaConsolidacion, idReunion) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
            data: {
                cargar_discipulos_reunion: 'cargar_discipulos_reunion',
                idCelulaConsolidacion: idCelulaConsolidacion,
                idReunion: idReunion
            },
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);

                let selector = document.getElementById('discipulos');

                // Destruir la instancia existente si la hay
                if (choices2) {
                    choices2.destroy();
                }

                // Limpiar el select
                selector.innerHTML = "";

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });

                choices2 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona los discipulos',  // Texto del placeholder
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }

    function Listar_asistencia(idReunion) {

        if (dataTable2) {
            dataTable2.destroy();
        }

        dataTable2 = $('#asistenciasDatatables').DataTable({
            language: {
                info: "",         // para ocultar "Showing x to y of z entries"
                infoEmpty: ""     // para ocultar "Showing 0 to 0 of 0 entries"
            },
            paging: false,
            searching: false,
            responsive: true,
            ajax: {
                method: "GET",
                url: 'http://localhost/AppwebMVC/CelulaConsolidacion/Reunion',
                data: {
                    cargar_data_asistencia: 'cargar_data_asistencia',
                    idReunion: idReunion
                }
            },
            columns: [
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return data.cedula + ' ' + data.nombre + ' ' + data.apellido;
                    }
                },
                {
                    defaultContent: `
                <button type="button" id="eliminarAsistencia" class="btn btn-danger delete-btn">Eliminar</button>
                `}

            ],
        })
    }


    $('#asistenciasDatatables tbody').on('click', '#eliminarAsistencia', function () {
        const datos = dataTable2.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
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
                    url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
                    data: {

                        eliminarAsistencia: 'eliminarAsistencia',
                        id: datos.idAsistencia,
                    },
                    success: function (response) {
                        //console.log(response);
                        let data = JSON.parse(response);

                        dataTable2.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: data.msj,
                            showConfirmButton: false,
                            timer: 2000,
                        })

                        Listar_discipulos_reunion(idCelulaConsolidacionDatatable, datos.idReunion)

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
            }
        });
    })


    let validation_selecteDiscipulos = false;

    const selectorDiscipulos = document.getElementById('discipulos');
    selectorDiscipulos.addEventListener('change', () => {

        const valoresSeleccionados = $("#discipulos").val();

        if (!valoresSeleccionados || valoresSeleccionados.length == 0) {
            document.getElementById("msj_discipulosAsistencia").classList.remove("d-none");
            validation_selecteDiscipulos = false;
        } else {
            document.getElementById("msj_discipulosAsistencia").classList.add("d-none");
            validation_selecteDiscipulos = true;
        }
    })

    let actualizarDiscipulos = document.getElementById('actualizarDiscipulos')
    actualizarDiscipulos.addEventListener('click', () => {
        if (validation_selecteDiscipulos) {
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
                data: {

                    actualizarAsistencia: 'actualizarAsistencia',
                    idReunion: idReunionAsistencia,
                    discipulos: $("#discipulos").val()
                },
                success: function (response) {

                    let data = JSON.parse(response);
                    dataTable2.ajax.reload();

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizaron correctamente las asistencias',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    Listar_discipulos_reunion(idCelulaConsolidacionDatatable, idReunionAsistencia)

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
                title: 'Formulario invalido. Por favor, verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    })







    ///////////////////////////// ACTUALIZAR DATOS DE REUNION /////////////////////////////// 

    const expresiones_regulares2 = {

        idCelulaConsolidacion: /^[1-9]\d*$/, // Números enteros mayores a 0
        tematica: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        actividad: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        observaciones: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        fecha: /^\d{4}-\d{2}-\d{2}$/
    };

    const validationStatus2 = {

        idCelulaConsolidacion: true,
        tematica: true,
        semana: true,
        generosidad: true,
        actividad: true,
        observaciones: true,
        fecha: true
    };


    // Validar idCelulaConsolidacion
    const idCelulaConsolidacion = document.getElementById("idCelulaConsolidacion");
    idCelulaConsolidacion.addEventListener('change', () => {
        if (!expresiones_regulares2.idCelulaConsolidacion.test(idCelulaConsolidacion.value)) {
            document.getElementById("msj_idCelulaConsolidacion").classList.remove("d-none");
            validationStatus2.idCelulaConsolidacion = false;
        } else {
            document.getElementById("msj_idCelulaConsolidacion").classList.add("d-none");
            validationStatus2.idCelulaConsolidacion = true;
        }
    })


    // Validar fecha
    const fecha = document.getElementById("fecha");
    fecha.addEventListener('change', () => {
        if (!expresiones_regulares2.fecha.test(fecha.value)) {
            document.getElementById("msj_fecha").classList.remove("d-none");
            validationStatus2.fecha = false;
        } else {
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

        const id = document.getElementById('idreunionconsolidacion').textContent

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
                data: {

                    editar: 'editar',
                    id: id,
                    idCelulaConsolidacion: idCelulaConsolidacion.value,
                    fecha: fecha.value,
                    tematica: tematica.value,
                    semana: semana.value,
                    generosidad: generosidad.value,
                    actividad: actividad.value,
                    observaciones: observaciones.value
                },
                success: function (response) {

                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
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
                title: 'Formulario invalido. Por favor, verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

});

