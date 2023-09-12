$(document).ready(function () {

    let choices1;
    let choices2;
    let choices3;
    let choices4;


    const dataTable = $('#celulaDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/CelulaConsolidacion/Listar',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre' },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `${data.nombreLider} ${data.apellidoLider}`
                }
            },
            {
                defaultContent: `
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-secondary">Info</button>
            <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
            <button type="button" id="reunion" data-bs-toggle="modal" data-bs-target="#modal_registroreunion" class="btn btn-info">Reunion</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            `}

        ],
    })

    $('#celulaDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        let text = `${datos.cedulaLider} ${datos.nombreLider} ${datos.apellidoLider}`;
        let text2 = `${datos.cedulaCoLider} ${datos.nombreCoLider} ${datos.apellidoCoLider}`;

        document.getElementById('inf_codigo').textContent = datos.id;
        document.getElementById('inf_nombre').textContent = datos.nombre;
        document.getElementById('inf_idLider').textContent = text;
        document.getElementById('inf_idCoLider').textContent = text2;
        document.getElementById('inf_idTerritorio').textContent = datos.idTerritorio;
    })

    $('#celulaDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idCelulaConsolidacion').textContent = datos.id;
        document.getElementById('nombre').value = datos.nombre;

        Listar_Lideres(datos.idLider, datos.idCoLider)
        Listar_Territorio(datos.idTerritorio)

    })

    $('#celulaDatatables tbody').on('click', '#reunion', function () {
        const datos = dataTable.row($(this).parents()).data();
        document.getElementById('idCelulaConsolidacionR').value = datos.id;
        Listar_discipulos_celula(datos.id)
    })


    ///////// ELIMINAR CELULA DE CONSOLIDACION ///////////
    $('#celulaDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: 'No podras acceder a esta celula otra vez!',
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
                    url: "http://localhost/AppwebMVC/CelulaConsolidacion/Listar",
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
                            text: 'La celula ha sido borrada',
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


    function Listar_Lideres(idLider, idCoLider) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Listar",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider');

                let selector2 = document.getElementById('idCoLider');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector2.appendChild(option);

                });


                // Destruir la instancia existente si la hay
                if (choices1) {
                    choices1.destroy();
                }
                choices1 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                // Destruir la instancia existente si la hay
                if (choices2) {
                    choices2.destroy();
                }
                choices2 = new Choices(selector2, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices1.setChoiceByValue(idLider.toString());
                choices2.setChoiceByValue(idCoLider.toString());

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }


    function Listar_Territorio(idTerritorio) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Listar",
            data: {

                listaTerritorio: 'listaTerritorio',

            },
            success: function (response) {


                let data = JSON.parse(response);

                let selector = document.getElementById('idTerritorio');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });

                // Destruir la instancia existente si la hay
                if (choices3) {
                    choices3.destroy();
                }
                choices3 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices3.setChoiceByValue(idTerritorio.toString());

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }


    function Listar_discipulos_celula(idCelulaConsolidacion) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Listar",
            data: {
                cargar_discipulos_celula: 'cargar_discipulos_celula',
                idCelulaConsolidacion: idCelulaConsolidacion
            },
            success: function (response) {

                console.log(response);
                let data = JSON.parse(response);

                let selector = document.getElementById('discipulos');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });

                // Destruir la instancia existente si la hay
                if (choices4) {
                    choices4.destroy();
                }
                
                choices4 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }






    ///////////////////////////// ACTUALIZAR DATOS DE LA CELULA //////////////////////////////

    const regexObj = {

        nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
        idLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        idCoLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        idTerritorio: /^[1-9]\d*$/, // Números enteros mayores a 0

    };

    const validationStatus = {
        nombre: true,
        idLider: true,
        idCoLider: true,
        idTerritorio: true,
        asistencia: true
    };


    //Validar nombre
    const nombre = document.getElementById("nombre");
    nombre.addEventListener('keyup', (e) => {
        // Validar nombre
        if (!regexObj.nombre.test(e.target.value)) {
            document.getElementById("msj_nombre").classList.remove("d-none");
            validationStatus.nombre = false;
        } else {
            document.getElementById("msj_nombre").classList.add("d-none");
            validationStatus.nombre = true;
        }
    })


    // Validacion de idLider y idCoLider

    const idLider = document.getElementById("idLider");
    const idCoLider = document.getElementById("idCoLider");

    idLider.addEventListener('change', (e) => {
        if (!regexObj.idLider.test(e.target.value) || e.target.value === idCoLider.value) {
            document.getElementById("msj_idLider").classList.remove("d-none");
            validationStatus.idLider = false;
        } else {
            document.getElementById("msj_idLider").classList.add("d-none");
            validationStatus.idLider = true;
        }
    })

    idCoLider.addEventListener('change', (e) => {
        if (!regexObj.idCoLider.test(e.target.value) || e.target.value === idLider.value) {
            document.getElementById("msj_idCoLider").classList.remove("d-none");
            validationStatus.idCoLider = false;
        } else {
            document.getElementById("msj_idCoLider").classList.add("d-none");
            validationStatus.idCoLider = true;
        }
    })


    // Validar idTerritorio
    const idTerritorio = document.getElementById("idTerritorio");
    idTerritorio.addEventListener('change', (e) => {
        if (!regexObj.idTerritorio.test(e.target.value)) {
            document.getElementById("msj_idTerritorio").classList.remove("d-none");
            validationStatus.idSede = false;
        } else {
            document.getElementById("msj_idTerritorio").classList.add("d-none");
            validationStatus.idTerritorio = true;
        }
    })


    const discipulos = document.getElementById('discipulos');
    discipulos.addEventListener('change', (e) => {
        const valoresSeleccionados = $("#discipulos").val();

        if (!valoresSeleccionados || valoresSeleccionados.length == 0) {
            document.getElementById("msj_asistencia").classList.remove("d-none");
            validationStatus.asistencia = false;
        } else {
            document.getElementById("msj_asistencia").classList.add("d-none");
            validationStatus.asistencia = true;
        }
    })


    const form = document.getElementById("formulario");
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const id = document.getElementById('idCelulaConsolidacion').textContent;
        
        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Listar",
                data: {

                    editar: 'editar',
                    id: id,
                    nombre: nombre.value,
                    idLider: idLider.value,
                    idCoLider: idCoLider.value,
                    idTerritorio: idTerritorio.value,
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
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
                title: 'Formulario incorrecto. Por favor, verifique sus datos antes de ser enviado',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });









    ///////////////////////////////// REGISTRO DE REUNION DE CELULA ////////////////////////////////  

    const expresiones_regulares2 = {

        tematica: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        actividad: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        observaciones: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };

    const validationStatus2 = {

        tematica: false,
        semana: false,
        generosidad: false,
        actividad: false,
        observaciones: false
    };

    const form2 = document.getElementById("formularioReunion");
    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        const idCelulaConsolidacion = document.getElementById('idCelulaConsolidacionR').value;

        // Validar fecha
        const fecha = document.getElementById("fecha").value;
        if (fecha === "") {
            document.getElementById("msj_fecha").classList.remove("d-none");
            validationStatus2.fecha = false;
        } else {
            document.getElementById("msj_fecha").classList.add("d-none");
            validationStatus2.fecha = true;
        }

        // Validar tematica
        const tematica = document.getElementById("tematica").value;
        if (!expresiones_regulares2.tematica.test(tematica)) {
            document.getElementById("msj_tematica").classList.remove("d-none");
            validationStatus2.tematica = false;
        } else {
            document.getElementById("msj_tematica").classList.add("d-none");
            validationStatus2.tematica = true;
        }

        // Validar semana
        const semana = document.getElementById("semana").value;
        if (!expresiones_regulares2.semana.test(semana)) {
            document.getElementById("msj_semana").classList.remove("d-none");
            validationStatus2.semana = false;
        } else {
            document.getElementById("msj_semana").classList.add("d-none");
            validationStatus2.semana = true;
        }

        // Validar generosidad
        const generosidad = document.getElementById("generosidad").value;
        if (!expresiones_regulares2.generosidad.test(generosidad)) {
            document.getElementById("msj_generosidad").classList.remove("d-none");
            validationStatus2.generosidad = false;
        } else {
            document.getElementById("msj_generosidad").classList.add("d-none");
            validationStatus2.generosidad = true;
        }

        //Validar asistencia
        const asistencia = document.querySelector("#discipulos");
        const selectedValues = Array.from(asistencia.selectedOptions).map(option => option.value);
        if (selectedValues.length === 0) {
            document.getElementById("msj_asistencia").classList.remove("d-none");
            validationStatus2.actividad = false;
        } else {
            document.getElementById("msj_asistencia").classList.add("d-none");
            validationStatus2.actividad = true;
        }


        // Validar actividad
        const actividad = document.getElementById("actividad").value;
        if (!expresiones_regulares2.actividad.test(actividad)) {
            document.getElementById("msj_actividad").classList.remove("d-none");
            validationStatus2.actividad = false;
        } else {
            document.getElementById("msj_actividad").classList.add("d-none");
            validationStatus2.actividad = true;
        }

        // Validar observaciones
        const observaciones = document.getElementById("observaciones").value;
        if (!expresiones_regulares2.observaciones.test(observaciones)) {
            document.getElementById("msj_observaciones").classList.remove("d-none");
            validationStatus2.observaciones = false;
        } else {
            document.getElementById("msj_observaciones").classList.add("d-none");
            validationStatus2.observaciones = true;
        }



        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Listar",
                data: {

                    registroreunion: 'registroreunion',
                    idCelulaConsolidacion: idCelulaConsolidacion,
                    fecha: fecha,
                    tematica: tematica,
                    semana: semana,
                    generosidad: generosidad,
                    asistencias: selectedValues,
                    actividad: actividad,
                    observaciones: observaciones,
                    asistencias: $("#discipulos").val()
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    fecha.value = ''
                    tematica.value = ''
                    semana.value = ''
                    generosidad.value = ''
                    actividad.value = ''
                    observaciones.value = ''
                    choices4.clear();

                    //Volviendo a colocar los estados en false
                    validationStatus2.forEach((value, key) => {
                        validationStatus2[key] = false;
                    });

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

