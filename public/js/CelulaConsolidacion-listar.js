$(document).ready(function () {

    let choices1;
    let choices2;
    let choices3;
    let choices4;
    let choices5;
    let choices6;
    let choices7;

    const dataTable = $('#celulaDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/CelulaConsolidacion/Index',
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
                <div class="d-flex justify-content-center gap-1">
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-secondary">Info</button>
            <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
            <button type="button" id="reunion" data-bs-toggle="modal" data-bs-target="#modal_registroreunion" class="btn btn-info">Reunion</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            </div>
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

        document.getElementById('idCelulaConsolidacion').textContent = datos.id;
        document.getElementById('nombre2').value = datos.nombre;

        Listar_TerritorioEditar(datos.idTerritorio);
        Listar_LideresEditar(datos.idLider, datos.idCoLider);

    })

    $('#registrar').on('click', function () {

        Listar_TerritorioRegistrar();
        Listar_LideresRegistrar();
    })


    $('#celulaDatatables tbody').on('click', '#reunion', function () {
        const datos = dataTable.row($(this).parents()).data();
        document.getElementById('idCelulaConsolidacionR').textContent = datos.id;
        Listar_discipulos_celula(datos.id);
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
                    url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
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

    function Listar_discipulos_celula(idCelula) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
            data: {
                cargar_discipulos_celula: 'cargar_discipulos_celula',
                idCelula: idCelula
            },
            success: function (response) {

                console.log(response);
                let data = JSON.parse(response);

                let selector = document.getElementById('discipulos');
                // Destruir la instancia existente si la hay
                if (choices7) {
                    choices7.destroy();
                }

                // Limpiar el select
                selector.innerHTML = "";

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });

                choices7 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }


    function Listar_LideresEditar(idLider, idCoLider) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider2');

                let selector2 = document.getElementById('idCoLider2');

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




    function Listar_TerritorioEditar(idTerritorio) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
            data: {

                listaTerritorio: 'listaTerritorio',

            },
            success: function (response) {


                let data = JSON.parse(response);

                let selector = document.getElementById('idTerritorio2');

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



    function Listar_LideresRegistrar() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider');
                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Seleccione el Lider de Celula';
                placeholderOption.disabled = true;
                placeholderOption.selected = true;
                selector.appendChild(placeholderOption);

                let selector2 = document.getElementById('idCoLider');

                const placeholderOption2 = document.createElement('option');
                placeholderOption2.value = '';
                placeholderOption2.text = 'Seleccione el CoLider de las Celula';
                placeholderOption2.disabled = true;
                placeholderOption2.selected = true;
                selector2.appendChild(placeholderOption2);

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
                if (choices4) {
                    choices4.destroy();
                }

                choices4 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                // Destruir la instancia existente si la hay
                if (choices5) {
                    choices5.destroy();
                }

                choices5 = new Choices(selector2, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });


            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }




    function Listar_TerritorioRegistrar() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
            data: {

                listaTerritorio: 'listaTerritorio',

            },
            success: function (response) {

                let data = JSON.parse(response);


                let selector = document.getElementById('idTerritorio');

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Selecciona el Territorio';
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
                if (choices6) {
                    choices6.destroy();
                }

                choices6 = new Choices(selector, {
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





    ///////////////////////////// REGISTRAR CELULA ////////////////////////////////

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

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
                data: {

                    registrar: 'registrar',
                    nombre: nombre.value,
                    idLider: idLider.value,
                    idCoLider: idCoLider.value,
                    idTerritorio: idTerritorio.value
                },
                success: function (response) {
                    console.log(response);
                    dataTable.ajax.reload();

                    let data = JSON.parse(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    nombre.value = '';
                    choices4.setChoiceByValue('');
                    choices5.setChoiceByValue('');
                    choices6.setChoiceByValue('');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
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










    ////////////////////////////// ACTUALIZAR DATOS DE LA CELULA ///////////////////////////////


    const validationStatus2 = {
        nombre: true,
        idLider: true,
        idCoLider: true,
        idTerritorio: true
    };


    //Validar nombre
    const nombre2 = document.getElementById("nombre2");
    nombre2.addEventListener('keyup', (e) => {
        // Validar nombre
        if (!regexObj.nombre.test(e.target.value)) {
            document.getElementById("msj_nombre2").classList.remove("d-none");
            validationStatus2.nombre = false;
        } else {
            document.getElementById("msj_nombre2").classList.add("d-none");
            validationStatus2.nombre = true;
        }
    })


    // Validacion de idLider y idCoLider

    const idLider2 = document.getElementById("idLider2");
    const idCoLider2 = document.getElementById("idCoLider2");

    idLider2.addEventListener('change', (e) => {
        if (!regexObj.idLider.test(e.target.value) || e.target.value === idCoLider2.value) {
            document.getElementById("msj_idLider2").classList.remove("d-none");
            validationStatus2.idLider = false;
        } else {
            document.getElementById("msj_idLider2").classList.add("d-none");
            validationStatus2.idLider = true;
        }
    })

    idCoLider2.addEventListener('change', (e) => {
        if (!regexObj.idCoLider.test(e.target.value) || e.target.value === idLider2.value) {
            document.getElementById("msj_idCoLider2").classList.remove("d-none");
            validationStatus2.idCoLider = false;
        } else {
            document.getElementById("msj_idCoLider2").classList.add("d-none");
            validationStatus2.idCoLider = true;
        }
    })


    // Validar idTerritorio
    const idTerritorio2 = document.getElementById("idTerritorio2");
    idTerritorio2.addEventListener('change', (e) => {
        if (!regexObj.idTerritorio.test(e.target.value)) {
            document.getElementById("msj_idTerritorio2").classList.remove("d-none");
            validationStatus2.idSede = false;
        } else {
            document.getElementById("msj_idTerritorio2").classList.add("d-none");
            validationStatus2.idTerritorio = true;
        }
    })


    const form2 = document.getElementById("formulario2");
    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        const id2 = document.getElementById('idCelulaConsolidacion').textContent;

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
                data: {

                    editar: 'editar',
                    id2: id2,
                    nombre2: nombre2.value,
                    idLider2: idLider2.value,
                    idCoLider2: idCoLider2.value,
                    idTerritorio2: idTerritorio2.value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
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
                title: 'Verifique bien el formulario antes de enviar',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });








    //////////////////////////// REGISTRO DE REUNION ////////////////////////////////   


    const expresiones_regulares2 = {

        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        infantil: /^[0-9]\d*$/,
        juvenil: /^[0-9]\d*$/,
        adulto: /^[0-9]\d*$/,
        texto: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        fecha: /^\d{4}-\d{2}-\d{2}$/
    };

    const validationStatus3 = {

        tematica: false,
        semana: false,
        generosidad: false,
        asistencia: false,
        actividad: false,
        observaciones: false
    };


    // Validar fecha
    const fecha = document.getElementById("fecha");
    fecha.addEventListener('change', (e) => {
        // Comprobar que la fecha esté en un formato válido
        if (!expresiones_regulares2.fecha.test(fecha.value)) {
            document.getElementById("msj_fecha").classList.remove("d-none");
            validationStatus3.fecha = false;
        } else {
            document.getElementById("msj_fecha").classList.add("d-none");
            validationStatus3.fecha = true;
        }
    })


    // Validar tematica
    const tematica = document.getElementById("tematica");
    tematica.addEventListener('keyup', (e) => {
        if (!expresiones_regulares2.texto.test(tematica.value)) {
            document.getElementById("msj_tematica").classList.remove("d-none");
            validationStatus3.tematica = false;
        } else {
            document.getElementById("msj_tematica").classList.add("d-none");
            validationStatus3.tematica = true;
        }
    })


    // Validar semana
    const semana = document.getElementById("semana");
    semana.addEventListener('change', (e) => {
        if (!expresiones_regulares2.semana.test(semana.value)) {
            document.getElementById("msj_semana").classList.remove("d-none");
            validationStatus3.semana = false;
        } else {
            document.getElementById("msj_semana").classList.add("d-none");
            validationStatus3.semana = true;
        }
    })


    // Validar generosidad
    const generosidad = document.getElementById("generosidad");
    generosidad.addEventListener('change', (e) => {
        if (!expresiones_regulares2.generosidad.test(generosidad.value)) {
            document.getElementById("msj_generosidad").classList.remove("d-none");
            validationStatus3.generosidad = false;
        } else {
            document.getElementById("msj_generosidad").classList.add("d-none");
            validationStatus3.generosidad = true;
        }
    })


    //Validar asistencia
    const asistencia = document.querySelector("#discipulos");
    let selectedValues;
    asistencia.addEventListener('change', (e) => {
        selectedValues = Array.from(asistencia.selectedOptions).map(option => option.value);
        if (selectedValues.length === 0) {
            document.getElementById("msj_discipulos").classList.remove("d-none");
            validationStatus3.asistencia = false;
        } else {
            document.getElementById("msj_discipulos").classList.add("d-none");
            validationStatus3.asistencia = true;
        }
    })


    // Validar actividad
    const actividad = document.getElementById("actividad");
    actividad.addEventListener('keyup', (e) => {
        if (!expresiones_regulares2.texto.test(actividad.value)) {
            document.getElementById("msj_actividad").classList.remove("d-none");
            validationStatus3.actividad = false;
        } else {
            document.getElementById("msj_actividad").classList.add("d-none");
            validationStatus3.actividad = true;
        }
    })


    // Validar observaciones
    const observaciones = document.getElementById("observaciones");
    observaciones.addEventListener('keyup', (e) => {
        if (!expresiones_regulares2.texto.test(observaciones.value)) {
            document.getElementById("msj_observaciones").classList.remove("d-none");
            validationStatus3.observaciones = false;
        } else {
            document.getElementById("msj_observaciones").classList.add("d-none");
            validationStatus3.observaciones = true;
        }
    })



    const form3 = document.getElementById("formularioReunion");

    form3.addEventListener("submit", (e) => {
        e.preventDefault();

        const idCelula = document.getElementById('idCelulaConsolidacionR').textContent;

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus3).every(status => status === true)) {
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Index",
                data: {

                    registroreunion: 'registroreunion',
                    idCelula: idCelula,
                    fecha: fecha.value,
                    tematica: tematica.value,
                    semana: semana.value,
                    generosidad: generosidad.value,
                    selectedValues: selectedValues,
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
                        title: 'Se registro correctamente la Reunion',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    Object.keys(validationStatus3).forEach((key) => {
                        validationStatus3[key] = true;
                    });

                    document.getElementById("formularioReunion").reset();
                    Listar_discipulos_celula(idCelula);

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
                title: 'Verifique bien el formulario antes de ser enviado',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

});





