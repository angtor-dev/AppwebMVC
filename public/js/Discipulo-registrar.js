
$(document).ready(function () {

    let choices1;
    let choices2;

    function Listar_Consolidador() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Discipulos/Registrar",
            data: {

                listaConsolidador: 'listaConsolidador',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idConsolidador');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });
                choices1 = new Choices(selector, {
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

    Listar_Consolidador();



    function Listar_celulas() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Discipulos/Registrar",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idcelulaconsolidacion');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });

                choices2 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }

    Listar_celulas();


    let validaciones = {
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
        nombrePersona: /^[a-zA-ZñÑ]{1,50}$/,
        apellidoPersona: /^[a-zA-ZñÑ]{1,50}$/,
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


            let asisFamiliar = $("#asisFamiliar").val();
            let asisCrecimiento = $("#asisCrecimiento").val();
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
                url: "http://localhost/AppwebMVC/Discipulos/Registrar",
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

                    let data = JSON.parse(response);

                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    document.getElementById('nombre').value = ''
                    document.getElementById('apellido').value = ''
                    document.getElementById('cedula').value = ''
                    document.getElementById('telefono').value = ''
                    document.getElementById('estadoCivil').value = ''
                    document.getElementById('fechaNacimiento').value = ''
                    document.getElementById('fechaConvercion').value = ''
                    document.getElementById('direccion').value = ''
                    document.getElementById('motivo').value = ''
                    asisCrecimiento.checked = false
                    asisFamiliar.checked = false
                    choices1.setChoiceByValue('')
                    choices2.setChoiceByValue('')

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
                title: 'Debes llenar el formulario correctamente. Por favor, ingrese nuevamente los datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }


    });

});

