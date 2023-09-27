$(document).ready(function () {

    const dataTable = $('#sedeDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/Discipulos/Index',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'cedula' },
            { data: 'nombre' },
            { data: 'apellido' },
            { data: 'codigo' },
            { data: 'asistencias' },
            {
                defaultContent: `
              <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-secondary">Info</button>
              <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
              <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
              `}
        ],
    })

    $('#registrar').on('click', function () {
        Listar_Consolidador('', 1);
        Listar_celulas('', 1);
    })

    $('#sedeDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        let text = `${datos.cedulaConsolidador} ${datos.nombreConsolidador} ${datos.apellidoConsolidador}`;

        document.getElementById('inf_nombre').textContent = datos.nombre;
        document.getElementById('inf_apellido').textContent = datos.apellido;
        document.getElementById('inf_telefono').textContent = datos.telefono;
        document.getElementById('inf_cedula').textContent = datos.cedula;
        document.getElementById('inf_estadoCivil').textContent = datos.estadoCivil;
        document.getElementById('inf_fechaNacimiento').textContent = datos.fechaNacimiento;
        document.getElementById('inf_fechaConvercion').textContent = datos.fechaConvercion;
        document.getElementById('inf_idConsolidador').textContent = text;
        document.getElementById('inf_codigo').textContent = datos.codigo;
        document.getElementById('inf_asisFamiliar').textContent = datos.asisFamiliar;
        document.getElementById('inf_asisCrecimiento').textContent = datos.asisCrecimiento;
        document.getElementById('inf_direccion').textContent = datos.direccion;
        document.getElementById('inf_motivo').textContent = datos.motivo;

    })



    $('#sedeDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idDiscipulo').textContent = datos.id;
        document.getElementById('nombre2').value = datos.nombre;
        document.getElementById('apellido2').value = datos.apellido;
        document.getElementById('cedula2').value = datos.cedula;
        document.getElementById('telefono2').value = datos.telefono;
        document.getElementById('estadoCivil2').value = datos.estadoCivil;
        document.getElementById('fechaNacimiento2').value = datos.fechaNacimiento;
        document.getElementById('fechaConvercion2').value = datos.fechaConvercion;
        document.getElementById('asisFamiliar2').checked = datos.asisFamiliar == 'si' ? true : false;
        document.getElementById('asisCrecimiento2').checked = datos.asisCrecimiento == 'si' ? true : false;
        document.getElementById('direccion2').value = datos.direccion;
        document.getElementById('motivo2').value = datos.motivo;

        Listar_Consolidador(datos.idConsolidador, 2);
        Listar_celulas(datos.idCelulaConsolidacion, 2);
    })

    $('#sedeDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a este discipulo otra vez",
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
                    url: "http://localhost/AppwebMVC/Discipulos/Listar",
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
                            text: 'El discipulo ha sido borrado',
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



    let choices1;
    let choices2;

    function Listar_Consolidador(idConsolidador, opcion) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Discipulos/Index",
            data: {

                listaConsolidador: 'listaConsolidador',

            },
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);

                let selector;
                if (opcion == 1) {
                    selector = document.getElementById('idConsolidador');
                    /*while (selector.firstChild) {
                        selector.removeChild(selector.firstChild);
                    }*/
                    selector.innerHTML = '';
                    const newOption = document.createElement('option');
                    newOption.value = '';
                    newOption.textContent = 'Selecciona consolidador';
                    newOption.disabled = true;
                    newOption.selected = true;
                    selector.appendChild(newOption);
                } else {
                    selector = document.getElementById('idConsolidador2');
                    selector.innerHTML = '';
                    /*const newOption = document.createElement('option');
                    newOption.value = '';
                    newOption.textContent = 'Selecciona consolidador';
                    newOption.disabled = true;
                    newOption.selected = true;
                    selector.appendChild(newOption);*/
                }


                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
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
                });

                if (idConsolidador !== '') {
                    choices1.setChoiceByValue(idConsolidador.toString())
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }



    function Listar_celulas(idCelulaConsolidacion, opcion) {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Discipulos/Index",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector;
                if (opcion == 1) {
                    selector = document.getElementById('idCelulaConsolidacion');
                    selector.innerHTML = '';
                    const newOption = document.createElement('option');
                    newOption.value = '';
                    newOption.textContent = 'Selecciona celula';
                    newOption.disabled = true;
                    newOption.selected = true;
                    selector.appendChild(newOption);
                } else {
                    selector = document.getElementById('idCelulaConsolidacion2');
                    selector.innerHTML = '';
                }


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
                });

                if (idCelulaConsolidacion !== '') {
                    choices2.setChoiceByValue(idCelulaConsolidacion.toString())
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }








    let validaciones = {
        nombre: true,
        apellido: true,
        cedula: true,
        telefono: true,
        estadoCivil: true,
        fechaNacimiento: true,
        fechaConvercion: true,
        idConsolidador: true,
        idcelulaconsolidacion: true,
        direccion: true,
        motivo: true

    };


    $("#nombre").on("keyup", function (event) {
        let nombre = $("#nombre").val();
        if (/^[a-zA-ZñÑ\s]{1,30}$/.test(nombre)) {
            validaciones.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");

        } else {
            validaciones.nombre = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");

        }
    })

    $("#apellido").on("keyup", function (event) {
        let apellido = $("#apellido").val();
        if (/^[a-zA-ZñÑ\s]{1,30}$/.test(apellido)) {
            validaciones.apellido = true;
            $("#apellido").removeClass("is-invalid");
            $("#apellido").addClass("is-valid");

        } else {
            validaciones.apellido = false;
            $("#apellido").removeClass("is-valid");
            $("#apellido").addClass("is-invalid");

        }
    })

    $("#cedula").on("keyup", function (event) {
        let cedula = $("#cedula").val();
        if (/^[0-9]{7,8}$/.test(cedula)) {
            validaciones.cedula = true;
            $("#cedula").removeClass("is-invalid");
            $("#cedula").addClass("is-valid");

        } else {
            validaciones.cedula = false;
            $("#cedula").removeClass("is-valid");
            $("#cedula").addClass("is-invalid");

        }
    })

    $("#direccion").on("keyup", function (event) {
        let direccion = $("#direccion").val();
        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(direccion)) {
            validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
        } else {
            validaciones.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
        }
    })

    $("#motivo").on("keyup", function (event) {
        let motivo = $("#motivo").val();
        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(motivo)) {
            validaciones.motivo = true;
            $("#motivo").removeClass("is-invalid");
            $("#motivo").addClass("is-valid");
        } else {
            validaciones.motivo = false;
            $("#motivo").removeClass("is-valid");
            $("#motivo").addClass("is-invalid");
        }
    })

    $("#telefono").on("keyup", function (event) {
        let telefono = $("#telefono").val();
        if (/^(0414|0424|0416|0426|0412)[0-9]{7}/.test(telefono)) {
            validaciones.telefono = true;
            $("#telefono").removeClass("is-invalid");
            $("#telefono").addClass("is-valid");
        } else {
            validaciones.telefono = false;
            $("#telefono").removeClass("is-valid");
            $("#telefono").addClass("is-invalid");
        }
    })


    $("#estadoCivil").on("change", function (event) {
        let estadoCivil = $("#estadoCivil").val();
        let estadosPermitido = ["casado", "soltero", "viudo"];
        if (estadosPermitido.includes(estadoCivil)) {
            validaciones.estadoCivil = true;
            $("#estadoCivil").removeClass("is-invalid");
            $("#estadoCivil").addClass("is-valid");
        } else {
            validaciones.estadoCivil = false;
            $("#estadoCivil").removeClass("is-valid");
            $("#estadoCivil").addClass("is-invalid");
        }
    })


    $("#fechaNacimiento").on("change", function (event) {
        let fechaNacimiento = $("#fechaNacimiento").val();
        if (/^.+$/.test(fechaNacimiento)) {
            validaciones.fechaNacimiento = true;
            $("#fechaNacimiento").removeClass("is-invalid");
            $("#fechaNacimiento").addClass("is-valid");
        } else {
            validaciones.fechaNacimiento = false;
            $("#fechaNacimiento").removeClass("is-valid");
            $("#fechaNacimiento").addClass("is-invalid");
        }
    })


    $("#fechaConvercion").on("change", function (event) {
        let fechaConvercion = $("#fechaConvercion").val();
        if (/^.+$/.test(fechaConvercion)) {
            validaciones.fechaConvercion = true;
            $("#fechaConvercion").removeClass("is-invalid");
            $("#fechaConvercion").addClass("is-valid");
        } else {
            validaciones.fechaConvercion = false;
            $("#fechaConvercion").removeClass("is-valid");
            $("#fechaConvercion").addClass("is-invalid");
        }
    })


    $("#idConsolidador").on("change", function (event) {

        let idConsolidador = $("#idConsolidador").val();
        if (/^[1-9]\d*$/.test(idConsolidador)) {
            validaciones.idConsolidador = true;
            $("#msj_idConsolidador").addClass("d-none");
        } else {
            console.log(idConsolidador);
            validaciones.idConsolidador = false;
            $("#msj_idConsolidador").removeClass("d-none");
        }
    })


    $("#idcelulaconsolidacion").on("change", function (event) {
        let idcelulaconsolidacion = $("#idcelulaconsolidacion").val();
        if (/^[1-9]\d*$/.test(idcelulaconsolidacion)) {
            validaciones.idcelulaconsolidacion = true;
            $("#msj_idcelulaconsolidacion").addClass("d-none");
        } else {
            validaciones.idcelulaconsolidacion = false;
            $("#idcelulaconsolidacion").removeClass("is-valid");
            $("#idcelulaconsolidacion").addClass("is-invalid");
        }



        let direccion = $("#direccion").val();
        if (/^[a-zA-ZñÑ\s]{1,100}$/.test(direccion)) {
            validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
        } else {
            validaciones.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
        }

        let motivo = $("#motivo").val();
        if (/^[a-zA-ZñÑ\s]{1,100}$/.test(motivo)) {
            validaciones.motivo = true;
            $("#motivo").removeClass("is-invalid");
            $("#motivo").addClass("is-valid");
        } else {
            validaciones.motivo = false;
            $("#motivo").removeClass("is-valid");
            $("#motivo").addClass("is-invalid");
        }


    });





    $("#formulario").submit(function (event) {
        event.preventDefault();

        if (Object.values(validaciones).every(val => val)) {

            let id = $("#idDiscipulo").text();
            let asisFamiliar
            let asisCrecimiento
            if (document.getElementById('asisFamiliar').checked == true) {
                asisFamiliar = 'si'
            } else {
                asisFamiliar = 'no'
            }
            if (document.getElementById('asisCrecimiento').checked == true) {
                asisCrecimiento = 'si'
            } else {
                asisCrecimiento = 'no'
            }
            let nombre = $("#nombre").val();
            let apellido = $("#apellido").val();
            let cedula = $("#cedula").val();
            let telefono = $("#telefono").val();
            let estadoCivil = $("#estadoCivil").val();
            let fechaNacimiento = $("#fechaNacimiento").val();
            let fechaConvercion = $("#fechaConvercion").val();
            let idConsolidador = $("#idConsolidador").val();
            let idCelulaConsolidacion = $("#idcelulaconsolidacion").val();
            let direccion = $("#direccion").val();
            let motivo = $("#motivo").val();

            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/Discipulos/Listar",
                data: {

                    editar: 'editar',
                    id: id,
                    nombre: nombre,
                    apellido: apellido,
                    cedula: cedula,
                    telefono: telefono,
                    estadoCivil: estadoCivil,
                    fechaNacimiento: fechaNacimiento,
                    fechaConvercion: fechaConvercion,
                    asisCrecimiento: asisCrecimiento,
                    asisFamiliar: asisFamiliar,
                    idConsolidador: idConsolidador,
                    idCelulaConsolidacion: idCelulaConsolidacion,
                    direccion: direccion,
                    motivo: motivo
                },
                success: function (response) {

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
                title: 'Debes llenar el formulario correctamente. Por favor, ingrese nuevamente los datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }


    });




    let validaciones2 = {
        nombre: false,
        apellido: false,
        cedula: false,
        telefono: false,
        estadoCivil: false,
        fechaNacimiento: false,
        fechaConvercion: false,
        idConsolidador: false,
        idcelulaconsolidacion: false,
        direccion: false,
        motivo: false

    };

    const expresiones = {
        nombrePersona: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ]{1,50}$/,
        apellidoPersona: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ]{1,50}$/,
        texto: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/,
        telefono: /^(0414|0424|0416|0426|0412)[0-9]{7}/,
        cedula: /^[0-9]{7,8}$/
    }


    $("#nombre").on("keyup", function (event) {
        let nombre = $("#nombre").val();
        if (expresiones.nombrePersona.test(nombre)) {
            validaciones.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");

        } else {
            validaciones.nombre = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");

        }
    })

    $("#apellido").on("keyup", function (event) {
        let apellido = $("#apellido").val();
        if (expresiones.apellidoPersona.test(apellido)) {
            validaciones.apellido = true;
            $("#apellido").removeClass("is-invalid");
            $("#apellido").addClass("is-valid");

        } else {
            validaciones.apellido = false;
            $("#apellido").removeClass("is-valid");
            $("#apellido").addClass("is-invalid");

        }
    })

    $("#cedula").on("keyup", function (event) {
        let cedula = $("#cedula").val();
        if (expresiones.cedula.test(cedula)) {
            validaciones.cedula = true;
            $("#cedula").removeClass("is-invalid");
            $("#cedula").addClass("is-valid");

        } else {
            validaciones.cedula = false;
            $("#cedula").removeClass("is-valid");
            $("#cedula").addClass("is-invalid");

        }
    })

    $("#direccion").on("keyup", function (event) {
        let direccion = $("#direccion").val();
        if (expresiones.texto.test(direccion)) {
            validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
        } else {
            validaciones.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
        }
    })

    $("#motivo").on("keyup", function (event) {
        let motivo = $("#motivo").val();
        if (expresiones.texto.test(motivo)) {
            validaciones.motivo = true;
            $("#motivo").removeClass("is-invalid");
            $("#motivo").addClass("is-valid");
        } else {
            validaciones.motivo = false;
            $("#motivo").removeClass("is-valid");
            $("#motivo").addClass("is-invalid");
        }
    })

    $("#telefono").on("keyup", function (event) {
        let telefono = $("#telefono").val();
        if (expresiones.telefono.test(telefono)) {
            validaciones.telefono = true;
            $("#telefono").removeClass("is-invalid");
            $("#telefono").addClass("is-valid");
        } else {
            validaciones.telefono = false;
            $("#telefono").removeClass("is-valid");
            $("#telefono").addClass("is-invalid");
        }
    })


    $("#estadoCivil").on("change", function (event) {
        let estadoCivil = $("#estadoCivil").val();
        let estadosPermitido = ["casado/a", "soltero/a", "viudo/a"];
        if (estadosPermitido.includes(estadoCivil)) {
            validaciones.estadoCivil = true;
            $("#estadoCivil").removeClass("is-invalid");
            $("#estadoCivil").addClass("is-valid");
        } else {
            validaciones.estadoCivil = false;
            $("#estadoCivil").removeClass("is-valid");
            $("#estadoCivil").addClass("is-invalid");
        }
    })


    $("#fechaNacimiento").on("change", function (event) {

        const fechaNacimiento = $("#fechaNacimiento").val();
        const hoy = new Date();
        const cumpleanos18 = new Date(fechaNacimiento);
        cumpleanos18.setFullYear(cumpleanos18.getFullYear() + 18);

        if (hoy >= cumpleanos18) {
            validaciones.fechaNacimiento = true;
            $("#fechaNacimiento").removeClass("is-invalid");
            $("#fechaNacimiento").addClass("is-valid");
        } else {
            validaciones.fechaNacimiento = false;
            $("#fechaNacimiento").removeClass("is-valid");
            $("#fechaNacimiento").addClass("is-invalid");
        }
    })


    $("#fechaConvercion").on("change", function (event) {
        let fechaConvercion = $("#fechaConvercion").val();
        if (fechaConvercion.value !== '') {
            validaciones.fechaConvercion = true;
            $("#fechaConvercion").removeClass("is-invalid");
            $("#fechaConvercion").addClass("is-valid");
        } else {
            validaciones.fechaConvercion = false;
            $("#fechaConvercion").removeClass("is-valid");
            $("#fechaConvercion").addClass("is-invalid");
        }
    })


    $("#idConsolidador").on("change", function (event) {

        let idConsolidador = $("#idConsolidador").val();
        if (/^[1-9]\d*$/.test(idConsolidador)) {
            validaciones.idConsolidador = true;
            $("#msj_idConsolidador").addClass("d-none");
        } else {
            console.log(idConsolidador);
            validaciones.idConsolidador = false;
            $("#msj_idConsolidador").removeClass("d-none");
        }
    })


    $("#idcelulaconsolidacion").on("change", function (event) {
        let idcelulaconsolidacion = $("#idcelulaconsolidacion").val();
        if (/^[1-9]\d*$/.test(idcelulaconsolidacion)) {
            validaciones.idcelulaconsolidacion = true;
            $("#msj_idcelulaconsolidacion").addClass("d-none");
        } else {
            validaciones.idcelulaconsolidacion = false;
            $("#idcelulaconsolidacion").removeClass("is-valid");
            $("#idcelulaconsolidacion").addClass("is-invalid");
        }



        let direccion = $("#direccion").val();
        if (/^[a-zA-ZñÑ\s]{1,100}$/.test(direccion)) {
            validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
        } else {
            validaciones.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
        }

        let motivo = $("#motivo").val();
        if (/^[a-zA-ZñÑ\s]{1,100}$/.test(motivo)) {
            validaciones.motivo = true;
            $("#motivo").removeClass("is-invalid");
            $("#motivo").addClass("is-valid");
        } else {
            validaciones.motivo = false;
            $("#motivo").removeClass("is-valid");
            $("#motivo").addClass("is-invalid");
        }

    });




    /////////////////////////////// REGISTRAR DISCIPULOS ///////////////////////////////////

    $("#formulario").submit(function (event) {
        event.preventDefault();

        if (Object.values(validaciones).every(val => val)) {


            let asisFamiliar;
            let asisCrecimiento;

            if (document.getElementById('asisCrecimiento').checked) {
                asisCrecimiento = 'si'
            } else {
                asisCrecimiento = 'no'
            }

            if (document.getElementById('asisFamiliar').checked) {
                asisFamiliar = 'si'
            } else {
                asisFamiliar = 'no'
            }


            let nombre = $("#nombre").val();
            let apellido = $("#apellido").val();
            let cedula = $("#cedula").val();
            let telefono = $("#telefono").val();
            let estadoCivil = $("#estadoCivil").val();
            let fechaNacimiento = $("#fechaNacimiento").val();
            let fechaConvercion = $("#fechaConvercion").val();
            let idConsolidador = $("#idConsolidador").val();
            let idcelulaconsolidacion = $("#idcelulaconsolidacion").val();
            let direccion = $("#direccion").val();
            let motivo = $("#motivo").val();

            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/Discipulos/Index",
                data: {

                    registrar: 'registrar',
                    nombre: nombre,
                    apellido: apellido,
                    cedula: cedula,
                    telefono: telefono,
                    estadoCivil: estadoCivil,
                    fechaNacimiento: fechaNacimiento,
                    fechaConvercion: fechaConvercion,
                    asisCrecimiento: asisCrecimiento,
                    asisFamiliar: asisFamiliar,
                    idConsolidador: idConsolidador,
                    idcelulaconsolidacion: idcelulaconsolidacion,
                    direccion: direccion,
                    motivo: motivo
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);

                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    document.getElementById('formulario').reset()
                    Listar_Consolidador('');
                    Listar_celulas('');

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
                title: 'Debes llenar el formulario correctamente. Por favor, ingrese nuevamente los datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }


    });

});


