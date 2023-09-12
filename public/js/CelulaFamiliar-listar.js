$(document).ready(function () {

    let choices1;
    let choices2;
    let choices3;

    const dataTable = $('#celulaDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/CelulaFamiliar/Listar',
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

        document.getElementById('inf_codigo').textContent = datos.codigo;
        document.getElementById('inf_nombre').textContent = datos.nombre;
        document.getElementById('inf_idLider').textContent = text;
        document.getElementById('inf_idCoLider').textContent = text2;
        document.getElementById('inf_idTerritorio').textContent = datos.idTerritorio;

    })


    $('#celulaDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idCelulaFamiliar').textContent = datos.id;
        document.getElementById('nombre').value = datos.nombre;

        Listar_Territorio(datos.idTerritorio);
        Listar_Lideres(datos.idLider, datos.idCoLider);

    })


    $('#celulaDatatables tbody').on('click', '#reunion', function () {
        const datos = dataTable.row($(this).parents()).data();
        document.getElementById('idCelulaFamiliarR').textContent = datos.id;

    })


    $('#celulaDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a esta celula otra vez!",
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
                    url: "http://localhost/AppwebMVC/CelulaFamiliar/Listar",
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
            url: "http://localhost/AppwebMVC/CelulaFamiliar/Listar",
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
            url: "http://localhost/AppwebMVC/CelulaFamiliar/Listar",
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

    












    ////////////////////////////// ACTUALIZAR DATOS DE LA CELULA ///////////////////////////////


    const regexObj = {

        nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
        idLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        idCoLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        idTerritorio: /^[1-9]\d*$/, // Números enteros mayores a 0

    };

    const validationStatus = {
        nombre: false,
        idLider: false,
        idCoLider: false,
        idTerritorio: false
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


    const form = document.getElementById("formulario");
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const id = document.getElementById('idCelulaFamiliar').textContent;

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaFamiliar/Listar",
                data: {

                    editar: 'editar',
                    id: id,
                    nombre: nombre.value,
                    idLider: idLider.value,
                    idCoLider: idCoLider.value,
                    idTerritorio: idTerritorio.value
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
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique bien el formulario antes de enviar',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });








    //////////////////////////// REGISTRO DE REUNION ////////////////////////////////   


    const regexObj2 = {

        tematica: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        infantil: /^[0-9]\d*$/,
        juvenil: /^[0-9]\d*$/,
        adulto: /^[0-9]\d*$/,
        actividad: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        observaciones: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };

    const validationStatus2 = {

        tematica: false,
        semana: false,
        generosidad: false,
        infantil: false,
        juvenil: false,
        adulto: false,
        actividad: false,
        observaciones: false
    };


    const form2 = document.getElementById("formularioReunion");

    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        const idCelulaFamiliar = document.getElementById('idCelulaFamiliarR').textContent;

        // Validar fecha
        const fecha = document.getElementById("fecha").value;
       /* if (fecha === "") {
            document.getElementById("msj_fecha").classList.remove("d-none");
            validationStatus2.fecha = false;
        } else {
            // Comprobar que la fecha esté en un formato válido
            if (!regexObj2.actividad.test(fecha)) {
                document.getElementById("msj_fecha").classList.remove("d-none");
                validationStatus2.fecha = false;
            } else {
                document.getElementById("msj_fecha").classList.add("d-none");
                validationStatus2.fecha = true;
            }
        }*/

        // Validar tematica
        const tematica = document.getElementById("tematica").value;
        if (!regexObj2.tematica.test(tematica)) {
            document.getElementById("msj_tematica").classList.remove("d-none");
            validationStatus2.tematica = false;
        } else {
            document.getElementById("msj_tematica").classList.add("d-none");
            validationStatus2.tematica = true;
        }

        // Validar semana
        const semana = document.getElementById("semana").value;
        if (!regexObj2.semana.test(semana)) {
            document.getElementById("msj_semana").classList.remove("d-none");
            validationStatus2.semana = false;
        } else {
            document.getElementById("msj_semana").classList.add("d-none");
            validationStatus2.semana = true;
        }

        // Validar generosidad
        const generosidad = document.getElementById("generosidad").value;
        if (!regexObj2.generosidad.test(generosidad)) {
            document.getElementById("msj_generosidad").classList.remove("d-none");
            validationStatus2.generosidad = false;
        } else {
            document.getElementById("msj_generosidad").classList.add("d-none");
            validationStatus2.generosidad = true;
        }

        // Validar infantil
        const infantil = document.getElementById("infantil").value;
        if (!regexObj2.infantil.test(infantil)) {
            document.getElementById("msj_infantil").classList.remove("d-none");
            validationStatus2.infantil = false;
        } else {
            document.getElementById("msj_infantil").classList.add("d-none");
            validationStatus2.infantil = true;
        }

        // Validar juvenil
        const juvenil = document.getElementById("juvenil").value;
        if (!regexObj2.juvenil.test(juvenil)) {
            document.getElementById("msj_juvenil").classList.remove("d-none");
            validationStatus2.juvenil = false;
        } else {
            document.getElementById("msj_juvenil").classList.add("d-none");
            validationStatus2.juvenil = true;
        }

        // Validar adulto
        const adulto = document.getElementById("adulto").value;
        if (!regexObj2.adulto.test(adulto)) {
            document.getElementById("msj_adulto").classList.remove("d-none");
            validationStatus2.adulto = false;
        } else {
            document.getElementById("msj_adulto").classList.add("d-none");
            validationStatus2.adulto = true;
        }

        // Validar actividad
        const actividad = document.getElementById("actividad").value;
        if (!regexObj2.actividad.test(actividad)) {
            document.getElementById("msj_actividad").classList.remove("d-none");
            validationStatus2.actividad = false;
        } else {
            document.getElementById("msj_actividad").classList.add("d-none");
            validationStatus2.actividad = true;
        }

        // Validar observaciones
        const observaciones = document.getElementById("observaciones").value;
        if (!regexObj2.observaciones.test(observaciones)) {
            document.getElementById("msj_juvenil").classList.remove("d-none");
            validationStatus2.observaciones = false;
        } else {
            document.getElementById("msj_observaciones").classList.add("d-none");
            validationStatus2.observaciones = true;
        }



        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaFamiliar/Listar",
                data: {

                    registroreunion: 'registroreunion',
                    idCelulaFamiliar: idCelulaFamiliar,
                    fecha: fecha,
                    tematica: tematica,
                    semana: semana,
                    generosidad: generosidad,
                    infantil: infantil,
                    juvenil: juvenil,
                    adulto: adulto,
                    actividad: actividad,
                    observaciones: observaciones
                },
                success: function (response) {

                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Se registro correctamente la Reunion',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    form2.reset()

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
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique bien el formulario antes de ser enviado',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

});

