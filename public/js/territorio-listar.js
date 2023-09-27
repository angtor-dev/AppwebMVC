$(document).ready(function () {

    let choices1;
    let choices2;
    let choices3;
    let choices4;

    const dataTable = $('#territorioDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/Territorios/Listar',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre' },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `${data.nombreLider} ${data.apellido}`
                }
            },
            {
                defaultContent: `
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-secondary">Info</button>
            <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            `}

        ],
    })

    $('#territorioDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        let text = `${datos.cedula} ${datos.nombreLider} ${datos.apellido}`;
        document.getElementById('inf_codigo').textContent = datos.codigo;
        document.getElementById('inf_nombre').textContent = datos.nombre;
        document.getElementById('inf_idLider').textContent = text;
        document.getElementById('inf_detalles').textContent = datos.detalles;



    })

    $('#territorioDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idTerritorio').textContent = datos.id;
        document.getElementById('nombre2').value = datos.nombre;
        document.getElementById('detalles2').value = datos.detalles;
        Listar_LideresEditar(datos.idLider);
        Listar_SedesEditar(datos.idSede)

    })


    $('#registrar').on('click', function () {

        Listar_LideresRegistrar();
        Listar_SedesRegistrar()
    
    })


    $('#territorioDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a este territorio otra vez!",
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
                    url: "http://localhost/AppwebMVC/Territorios/Listar",
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
                            text: 'El territorio ha sido borrado',
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




    function Listar_LideresRegistrar() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Territorios/Listar",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider');
                selector.innerHTML = '';
                // Limpiar el selector antes de agregar nuevas opciones
                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Seleccione un Lider';
                placeholderOption.disabled = true;
                placeholderOption.selected = true;
                selector.appendChild(placeholderOption);

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

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
                
                choices1.setChoiceByValue('');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }




    function Listar_SedesRegistrar() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Territorios/Listar",
            data: {

                listaSedes: 'listaSedes',

            },
            success: function (response) {


                let data = JSON.parse(response);

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
                if (choices2) {
                    choices2.destroy();
                }
                choices2 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices2.setChoiceByValue('');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }

    function Listar_LideresEditar(idLider) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Territorios/Listar",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider2');
                // Limpiar el selector antes de agregar nuevas opciones
                selector.innerHTML = '';

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
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

                choices3.setChoiceByValue(idLider.toString());

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }




    function Listar_SedesEditar(idSede) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Territorios/Listar",
            data: {

                listaSedes: 'listaSedes',

            },
            success: function (response) {


                let data = JSON.parse(response);

                let selector = document.getElementById('idSede2');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
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

                choices4.setChoiceByValue(idSede.toString());

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }







    ////////////////////////////// ACTUALIZAR DATOS DE TERRITORIO //////////////////////////////

    const regexObj = {
        idSede: /^[1-9]\d*$/, // Números enteros mayores a 0
        nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
        idLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        detalles: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };


    const validationStatus = {
        idSede: false,
        nombre: false,
        idLider: false,
        detalles: false
    };


    const form = document.getElementById("formulario");

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Validar idSede
        const idSede = document.getElementById("idSede").value;
        if (!regexObj.idSede.test(idSede)) {
            document.getElementById("msj_idSede").classList.remove("d-none");
            validationStatus.idSede = false;
        } else {
            document.getElementById("msj_idSede").classList.add("d-none");
            validationStatus.idSede = true;
        }

        // Validar nombre
        const nombre = document.getElementById("nombre").value;
        if (!regexObj.nombre.test(nombre)) {
            document.getElementById("msj_nombre").classList.remove("d-none");
            validationStatus.nombre = false;
        } else {
            document.getElementById("msj_nombre").classList.add("d-none");
            validationStatus.nombre = true;
        }

        // Validar idLider
        const idLider = document.getElementById("idLider").value;
        if (!regexObj.idLider.test(idLider)) {
            document.getElementById("msj_idLider").classList.remove("d-none");
            validationStatus.idLider = false;
        } else {
            document.getElementById("msj_idLider").classList.add("d-none");
            validationStatus.idLider = true;
        }

        // Validar detalles
        const detalles = document.getElementById("detalles").value;
        if (!regexObj.detalles.test(detalles)) {
            document.getElementById("msj_detalles").classList.remove("d-none");
            validationStatus.detalles = false;
        } else {
            document.getElementById("msj_detalles").classList.add("d-none");
            validationStatus.detalles = true;
        }

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/Territorios/Listar",
                data: {

                    registrar: 'registrar',
                    idSede: idSede,
                    nombre: nombre,
                    idLider: idLider,
                    detalles: detalles
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
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
                title: 'Formulario invalido para enviar. Por favor, ingrese nuevamente sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

    const validationStatus2 = {
        idSede: true,
        nombre: true,
        idLider: true,
        detalles: true
    };


    const form2 = document.getElementById("formulario2");

    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        let id = document.getElementById('idTerritorio2').textContent;

        // Validar idSede
        const idSede = document.getElementById("idSede2").value;
        if (!regexObj.idSede.test(idSede)) {
            document.getElementById("msj_idSede2").classList.remove("d-none");
            validationStatus2.idSede = false;
        } else {
            document.getElementById("msj_idSede2").classList.add("d-none");
            validationStatus2.idSede = true;
        }

        // Validar nombre
        const nombre = document.getElementById("nombre2").value;
        if (!regexObj.nombre.test(nombre)) {
            document.getElementById("msj_nombre2").classList.remove("d-none");
            validationStatus2.nombre = false;
        } else {
            document.getElementById("msj_nombre2").classList.add("d-none");
            validationStatus2.nombre = true;
        }

        // Validar idLider
        const idLider = document.getElementById("idLider2").value;
        if (!regexObj.idLider.test(idLider2)) {
            document.getElementById("msj_idLider").classList.remove("d-none");
            validationStatus2.idLider = false;
        } else {
            document.getElementById("msj_idLider2").classList.add("d-none");
            validationStatus2.idLider = true;
        }

        // Validar detalles
        const detalles = document.getElementById("detalles2").value;
        if (!regexObj.detalles.test(detalles)) {
            document.getElementById("msj_detalles2").classList.remove("d-none");
            validationStatus2.detalles = false;
        } else {
            document.getElementById("msj_detalles2").classList.add("d-none");
            validationStatus2.detalles = true;
        }



        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/Territorios/Listar",
                data: {

                    editar: 'editar',
                    id: id,
                    idSede: idSede,
                    nombre: nombre,
                    idLider: idLider,
                    detalles: detalles
                },
                success: function (response) {

                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Territorio actualizado correctamente',
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

