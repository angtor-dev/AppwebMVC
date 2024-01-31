$(document).ready(function () {

    let choices;

    const dataTable = $('#celulaDatatables').DataTable({
        info: false,
        lengthChange: false,
        pageLength: 15,
        dom: 'ltip',
        searching: true,
        language: {
            url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
        },
   
        drawCallback: function (settings) {
            var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
            pagination.toggle(this.api().page.info().pages > 1);
        },
        ajax: {
            method: "GET",
            url: '/AppwebMVC/CelulaFamiliar/Reunion',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre' },
            { data: 'fecha' },
            {
                defaultContent:

           `<div class="acciones"><a role="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" title="Ver detalles" ><i class="fa-solid fa-circle-info" ></i></a>

            <a role="button" id="editar" data-bs-toggle="modal" title="Actualizar" data-bs-target="#modal_editarInfo" ><i class="fa-solid fa-pen" ></i></a>
  
           <a role="button"  id=eliminar title="Eliminar"><i class="fa-solid fa-trash" ></i></a></div>` 
          },
        ],
    });

    $('#search').keyup(function () {
        dataTable.search($(this).val()).draw();
    });


    $('#celulaDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();


        document.getElementById('inf_codigocelula').textContent = datos.codigo;
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

        document.getElementById('idreunion').textContent = datos.id;
        document.getElementById('fecha').value = datos.fecha;
        document.getElementById('tematica').value = datos.tematica;
        document.getElementById('semana').value = datos.semana;
        document.getElementById('generosidad').value = datos.generosidad;
        document.getElementById('infantil').value = datos.infantil;
        document.getElementById('juvenil').value = datos.juvenil;
        document.getElementById('adulto').value = datos.adulto;
        document.getElementById('actividad').value = datos.actividad;
        document.getElementById('observaciones').value = datos.observaciones;
        Listar_celulas(datos.idCelula);

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
                    url: "/AppwebMVC/CelulaFamiliar/Reunion",
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




    function Listar_celulas(idCelula) {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/CelulaFamiliar/Reunion",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                let data = JSON.parse(response);

                console.log(data);

                let selector = document.getElementById('idCelula');


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

                choices.setChoiceByValue(idCelula.toString());

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }







    /////////////////////////////////// ACTUALIZAR DATOS DE REUNION //////////////////////////////////      
    const ex_re = {
        idCelula: /^[1-9]\d*$/, 
        semana: /^[1-9]\d*$/,
        fecha: /^.+$/,
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        asistencia: /^[0-9]\d*$/,
        info: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };

    const validationStatus3 = {
        idCelula: true, 
        fecha: true,
        tematica: true,
        semana: true,
        generosidad: true,
        infantil: true,
        juvenil: true,
        adulto: true,
        actividad: true,
        observaciones: true
    };



    $("#idCelula").on("change", function (event) {
        const idCelula = document.getElementById("idCelula").value;
        const div = document.getElementById("msj_idCelula");
        if (!ex_re.idCelula.test(idCelula)) {
            div.classList.remove("d-none");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus.idCelula = false;
        } else {
            div.classList.add("d-none");
            div.innerText = "";

            validationStatus.idCelula = true;
        }
    })


    $("#fecha").on("change", function (event) {
        const fecha = document.getElementById("fecha");
        const div = document.getElementById("msj_fecha");
        if (!ex_re.fecha.test(fecha.value)) {
            fecha.classList.remove("is-valid");
            fecha.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus3.fecha = false;
        } else {
            fecha.classList.remove("is-invalid");
            fecha.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.fecha = true;
        }
    })

    $("#tematica").on("keyup", function (event) {
        const tematica = document.getElementById("tematica");
        const div = document.getElementById("msj_tematica");
        if (!ex_re.info.test(tematica.value)) {
            tematica.classList.remove("is-valid");
            tematica.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio, debe poseer mas  de 5 caracteres";
     
            validationStatus3.tematica = false;
        } else {
            tematica.classList.remove("is-invalid");
            tematica.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.tematica = true;
        }
    })

    $("#semana").on("change", function (event) {
        const input = document.getElementById("semana");
        const div = document.getElementById("msj_semana");
        if (!ex_re.semana.test(input.value)) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus3.semana = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.semana = true;
        }
    })

    $("#generosidad").on("keyup", function (event) {
        const generosidad = document.getElementById("generosidad");
        const div = document.getElementById("msj_generosidad");
        if (!ex_re.generosidad.test(generosidad.value)) {
            generosidad.classList.remove("is-valid");
            generosidad.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio, y su formato correcto es 00,00";
     
            validationStatus3.generosidad = false;
        } else {
            generosidad.classList.remove("is-invalid");
            generosidad.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.generosidad = true;
        }
    })

    $("#juvenil").on("change", function (event) {
        const input = document.getElementById("juvenil");
        const div = document.getElementById("msj_juvenil");
        if (!ex_re.asistencia.test(input.value)) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus3.juvenil = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.juvenil = true;
        }
    })

    $("#adulto").on("change", function (event) {
        const input = document.getElementById("adulto");
        const div = document.getElementById("msj_adulto");
        if (!ex_re.asistencia.test(input.value)) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus3.adulto = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.adulto = true;
        }
    })

    $("#infantil").on("change", function (event) {
        const input = document.getElementById("infantil");
        const div = document.getElementById("msj_infantil");
        if (!ex_re.asistencia.test(input.value)) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus3.infantil = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.infantil = true;
        }
    })

    $("#actividad").on("keyup", function (event) {
        const input = document.getElementById("actividad");
        const div = document.getElementById("msj_actividad");
        if (!ex_re.info.test(input.value)) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio, y debe poseer mas de 5 caracteres";
     
            validationStatus3.actividad = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.actividad = true;
        }
    })

    $("#observaciones").on("keyup", function (event) {
        const input = document.getElementById("observaciones");
        const div = document.getElementById("msj_observaciones");
        if (!ex_re.info.test(input.value)) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            div.innerText = "Este campo es obligatorio, y debe poseer mas de 5 caracteres";
     
            validationStatus3.observaciones = false;
        } else {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            div.innerText = "";

            validationStatus3.observaciones = true;
        }
    })





    const form3 = document.getElementById("formularioReunion");

    form3.addEventListener("submit", (e) => {
        e.preventDefault();
        const dato = {

            id: $("#idreunion"),
            idCelula: $("#idCelula"),
            fecha: $("#fecha"),
            tematica: $("#tematica"),
            semana: $("#semana"),
            generosidad: $("#generosidad"),
            infantil: $("#infantil"),
            juvenil: $("#juvenil"),
            adulto: $("#adulto"),
            actividad: $("#actividad"),
            observaciones: $("#observaciones")
        }
        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus3).every(status => status === true)) {
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "/AppwebMVC/CelulaFamiliar/Reunion",
                data: {

                    editar: 'editar',
                    id: dato.id.text(),
                    idCelula: dato.idCelula.val(),
                    fecha: dato.fecha.val(),
                    tematica: dato.tematica.val(),
                    semana: dato.semana.val(),
                    generosidad: dato.generosidad.val(),
                    infantil: dato.infantil.val(),
                    juvenil: dato.juvenil.val(),
                    adulto: dato.adulto.val(),
                    actividad: dato.actividad.val(),
                    observaciones: dato.observaciones.val()
                },
                success: function (response) {

                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    });


                      for (const key in dato) {
                        const input = dato[key];
                        input.removeClass("is-valid");
                      }


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
   

    $('#cerrarReunion').on('click', function () {
    
        document.getElementById('formularioReunion').reset();
    
        const dato = {
    
            idCelula: $("#idCelulaFamiliarR"),
            fecha: $("#fecha"),
            tematica: $("#tematica"),
            semana: $("#semana"),
            generosidad: $("#generosidad"),
            infantil: $("#infantil"),
            juvenil: $("#juvenil"),
            adulto: $("#adulto"),
            actividad: $("#actividad"),
            observaciones: $("#observaciones")
        }
    
        for (const key in dato) {
            const input = dato[key];
            input.removeClass("is-valid");
            input.removeClass("is-invalid")
          }

        $('#msj_idCelula').addClass("d-none");
    
        $('#modal_editarInfo').modal('hide');
    
      });

});

