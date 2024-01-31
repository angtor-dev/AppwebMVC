$(document).ready(function () {
    
    let choices1;
    let choices2;

    const dataTable = $('#sedeDatatables').DataTable({
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
            url: '/AppwebMVC/Discipulos/Index',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'cedula' },
            { data: 'nombre' },
            { data: 'apellido' },
            { data: 'codigo' },
            { data: 'asistencias' },
            {
                data: null,
        render: function (data, type, row, meta) {

            let botonInfo = `<a type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" title="Ver detalles" ,><i class="fa-solid fa-circle-info" ></i></a>`;

            let botonEditar = permisos.actualizar ? `<a type="button" id="editar" data-bs-toggle="modal" title="Actualizar" data-bs-target="#modal_editarInfo" ,><i class="fa-solid fa-pen" ></i></a>` : '';
  
            let botonEliminar = permisos.eliminar ? `<a type="button"  id=eliminar , title="Eliminar"><i class="fa-solid fa-trash" ></i></a>` : '';
  
          

          let div = `
          <div class="acciones">
                    ${botonInfo}
                    ${botonEditar}
                    ${botonEliminar}
          </div>
          `
          return div;
        }},
        ],
    });

    $('#search').keyup(function () {
        dataTable.search($(this).val()).draw();
    });

    
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
                    url: "/AppwebMVC/Discipulos/Index",
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

   

    function Listar_Consolidador(idConsolidador, opcion) {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/Discipulos/Index",
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
            url: "/AppwebMVC/Discipulos/Index",
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






// editar desicipulos 

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


    $("#nombre2").on("keyup", function (event) {
        let nombre = $("#nombre2").val();
        if (/^[a-zA-ZñÑ\s]{1,30}$/.test(nombre)) {
            validaciones.nombre = true;
            $("#nombre2").removeClass("is-invalid");
            $("#nombre2").addClass("is-valid");

        } else {
            validaciones.nombre = false;
            $("#nombre2").removeClass("is-valid");
            $("#nombre2").addClass("is-invalid");

        }
    })

    $("#apellido2").on("keyup", function (event) {
        let apellido = $("#apellido2").val();
        if (/^[a-zA-ZñÑ\s]{1,30}$/.test(apellido)) {
            validaciones.apellido = true;
            $("#apellido2").removeClass("is-invalid");
            $("#apellido2").addClass("is-valid");

        } else {
            validaciones.apellido = false;
            $("#apellido2").removeClass("is-valid");
            $("#apellido2").addClass("is-invalid");

        }
    })

    $("#cedula2").on("keyup", function (event) {
        const cedula = document.getElementById("cedula2").value
        $.ajax({
          type: "POST",
          url: "/AppwebMVC/Discipulos/Index",
          data: {
            coincidencias: 'coincidencias',
            cedula: cedula,
            id: $("#idDiscipulo").text()
          },
          success: function (response) {
                  
          var data = JSON.parse(response);
      
            if (data != true) {
              validaciones.cedula = true;
              $("#cedula2").removeClass("is-invalid");
              $("#cedula2").addClass("is-valid");
              document.getElementById('msj_cedula2').textContent = '';
              if (/^[0-9]{7,8}$/.test(cedula)) {
                validaciones.cedula = true;
                $("#cedula2").removeClass("is-invalid");
                $("#cedula2").addClass("is-valid");
                document.getElementById('msj_cedula2').textContent = '';
      
              } else {
                validaciones.cedula = false;
                $("#cedula2").removeClass("is-valid");
                $("#cedula2").addClass("is-invalid");
                document.getElementById('msj_cedula2').textContent = 'La Cedula es obligatoria';
      
              }
            } else {

             
              validaciones.cedula = false;
              $("#cedula2").removeClass("is-valid");
              $("#cedula2").addClass("is-invalid");
              document.getElementById('msj_cedula2').textContent = 'Este Discipulo ya se encuentra inscrito en la Sede:';
      
            }
      
          },
          error: function (jqXHR, textStatus, errorThrown) {
            // Aquí puedes manejar errores, por ejemplo:
            console.error("Error al enviar:", textStatus, errorThrown);
          }
        })
      
      
      });

    $("#direccion2").on("keyup", function (event) {
        let direccion = $("#direccion2").val();
        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(direccion)) {
            validaciones.direccion = true;
            $("#direccion2").removeClass("is-invalid");
            $("#direccion2").addClass("is-valid");
        } else {
            validaciones.direccion = false;
            $("#direccion2").removeClass("is-valid");
            $("#direccion2").addClass("is-invalid");
        }
    })

    $("#motivo2").on("keyup", function (event) {
        let motivo = $("#motivo2").val();
        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(motivo)) {
            validaciones.motivo = true;
            $("#motivo2").removeClass("is-invalid");
            $("#motivo2").addClass("is-valid");
        } else {
            validaciones.motivo = false;
            $("#motivo2").removeClass("is-valid");
            $("#motivo2").addClass("is-invalid");
        }
    })

    $("#telefono2").on("keyup", function (event) {
        let telefono = $("#telefono2").val();
        if (/^(0414|0424|0416|0426|0412)[0-9]{7}/.test(telefono)) {
            validaciones.telefono = true;
            $("#telefono2").removeClass("is-invalid");
            $("#telefono2").addClass("is-valid");
        } else {
            validaciones.telefono = false;
            $("#telefono2").removeClass("is-valid");
            $("#telefono2").addClass("is-invalid");
        }
    })


    $("#estadoCivil2").on("change", function (event) {
        let estadoCivil = $("#estadoCivil2").val();
        let estadosPermitido = ["casado/a", "soltero/a", "viudo/a"];
        if (estadosPermitido.includes(estadoCivil)) {
            validaciones.estadoCivil = true;
            $("#estadoCivil2").removeClass("is-invalid");
            $("#estadoCivil2").addClass("is-valid");
        } else {
            validaciones.estadoCivil = false;
            $("#estadoCivil2").removeClass("is-valid");
            $("#estadoCivil2").addClass("is-invalid");
        }
    })


    $("#fechaNacimiento2").on("change", function (event) {

        const fechaNacimiento = $("#fechaNacimiento2").val();
        const hoy = new Date();
        const cumpleanos18 = new Date(fechaNacimiento);
        cumpleanos18.setFullYear(cumpleanos18.getFullYear() + 18);

        if (hoy >= cumpleanos18) {
            validaciones.fechaNacimiento = true;
            $("#fechaNacimiento2").removeClass("is-invalid");
            $("#fechaNacimiento2").addClass("is-valid");
        } else {
            validaciones.fechaNacimiento = false;
            $("#fechaNacimiento2").removeClass("is-valid");
            $("#fechaNacimiento2").addClass("is-invalid");
        }
    })


    $("#fechaConvercion2").on("change", function (event) {
        let fechaConvercion = $("#fechaConvercion2").val();
        if (/^.+$/.test(fechaConvercion)) {
            validaciones.fechaConvercion = true;
            $("#fechaConvercion2").removeClass("is-invalid");
            $("#fechaConvercion2").addClass("is-valid");
        } else {
            validaciones.fechaConvercion = false;
            $("#fechaConvercion2").removeClass("is-valid");
            $("#fechaConvercion2").addClass("is-invalid");
        }
    })


    $("#idConsolidador2").on("change", function (event) {

        let idConsolidador = $("#idConsolidador2").val();
        if (/^[1-9]\d*$/.test(idConsolidador)) {
            validaciones.idConsolidador = true;
            $("#msj_idConsolidador2").addClass("d-none");
        } else {
            console.log(idConsolidador);
            validaciones.idConsolidador = false;
            $("#msj_idConsolidador2").removeClass("d-none");
        }
    })


    $("#idCelulaConsolidacion2").on("change", function (event) {
        let idcelulaconsolidacion = $("#idCelulaConsolidacion2").val();
        if (/^[1-9]\d*$/.test(idcelulaconsolidacion)) {
            validaciones.idcelulaconsolidacion = true;
            $("#msj_idCelulaConsolidacion2").addClass("d-none");
        } else {
            validaciones.idcelulaconsolidacion = false;
            $("#msj_idCelulaConsolidacion2").removeClass("d-none");
           
        }



    });


    $("#formulario2").submit(function (event) {
        event.preventDefault();

        if (Object.values(validaciones).every(status => status === true)) {

            let id = $("#idDiscipulo").text();
            let asisFamiliar
            let asisCrecimiento
            if (document.getElementById('asisFamiliar2').checked == true) {
                asisFamiliar = 'si'
            } else {
                asisFamiliar = 'no'
            }
            if (document.getElementById('asisCrecimiento2').checked == true) {
                asisCrecimiento = 'si'
            } else {
                asisCrecimiento = 'no'
            }

            const dato = {
                 apellido: $("#apellido2"),
                 cedula: $("#cedula2"),
                 telefono: $("#telefono2"),
                 estadoCivil: $("#estadoCivil2"),
                 fechaNacimiento: $("#fechaNacimiento2"),
                 fechaConvercion: $("#fechaConvercion2"),
                 idConsolidador: $("#idConsolidador2"),
                 idCelulaConsolidacion: $("#idCelulaConsolidacion2"),
                 nombre: $("#nombre2"),
                 direccion: $("#direccion2"),
                 motivo: $("#motivo2")
              }
            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Discipulos/Index",
                data: {

                    editar: 'editar',
                    id: id,
                    nombre: dato.nombre.val(),
                    apellido: dato.apellido.val(),
                    cedula: dato.cedula.val(),
                    telefono: dato.telefono.val(),
                    estadoCivil: dato.estadoCivil.val(),
                    fechaNacimiento: dato.fechaNacimiento.val(),
                    fechaConvercion: dato.fechaConvercion.val(),
                    asisCrecimiento: asisCrecimiento,
                    asisFamiliar: asisFamiliar,
                    idConsolidador: dato.idConsolidador.val(),
                    idCelulaConsolidacion: dato.idCelulaConsolidacion.val(),
                    direccion: dato.direccion.val(),
                    motivo: dato.motivo.val()
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

                    for (const key in dato) {
                        const input = dato[key];
                        input.removeClass("is-valid");
                      }


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

    $("#nombre").on("keyup", function (event) {
        let nombre = $("#nombre").val();
        if (/^[a-zA-ZñÑ\s]{1,30}$/.test(nombre)) {
            validaciones2.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");

        } else {
            validaciones2.nombre = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");

        }
    })

    $("#apellido").on("keyup", function (event) {
        let apellido = $("#apellido").val();
        if (/^[a-zA-ZñÑ\s]{1,30}$/.test(apellido)) {
            validaciones2.apellido = true;
            $("#apellido").removeClass("is-invalid");
            $("#apellido").addClass("is-valid");

        } else {
            validaciones2.apellido = false;
            $("#apellido").removeClass("is-valid");
            $("#apellido").addClass("is-invalid");

        }
    })

    $("#cedula").on("keyup", function (event) {
        const cedula = document.getElementById("cedula").value
        $.ajax({
          type: "POST",
          url: "/AppwebMVC/Discipulos/Index",
          data: {
            coincidencias: 'coincidencias',
            cedula: cedula,
          },
          success: function (response) {
                  
          var data = JSON.parse(response);
      
            if (data != true) {
              validaciones2.cedula = true;
              $("#cedula").removeClass("is-invalid");
              $("#cedula").addClass("is-valid");
              document.getElementById('msj_cedula').textContent = '';
              if (/^[0-9]{7,8}$/.test(cedula)) {
                validaciones2.cedula = true;
                $("#cedula").removeClass("is-invalid");
                $("#cedula").addClass("is-valid");
                document.getElementById('msj_cedula').textContent = '';
      
              } else {
                validaciones2.cedula = false;
                $("#cedula").removeClass("is-valid");
                $("#cedula").addClass("is-invalid");
                document.getElementById('msj_cedula').textContent = 'La Cedula es obligatoria';
      
              }
            } else {

             
              validaciones2.cedula = false;
              $("#cedula").removeClass("is-valid");
              $("#cedula").addClass("is-invalid");
              document.getElementById('msj_cedula').textContent = 'Este Discipulo ya se encuentra inscrito en la Sede:';
      
            }
      
          },
          error: function (jqXHR, textStatus, errorThrown) {
            // Aquí puedes manejar errores, por ejemplo:
            console.error("Error al enviar:", textStatus, errorThrown);
          }
        })
      
      
      });

    $("#direccion").on("keyup", function (event) {
        let direccion = $("#direccion").val();
        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(direccion)) {
            validaciones2.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
        } else {
            validaciones2.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
        }
    })

    $("#motivo").on("keyup", function (event) {
        let motivo = $("#motivo").val();
        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(motivo)) {
            validaciones2.motivo = true;
            $("#motivo").removeClass("is-invalid");
            $("#motivo").addClass("is-valid");
        } else {
            validaciones2.motivo = false;
            $("#motivo").removeClass("is-valid");
            $("#motivo").addClass("is-invalid");
        }
    })

    $("#telefono").on("keyup", function (event) {
        let telefono = $("#telefono").val();
        if (/^(0414|0424|0416|0426|0412)[0-9]{7}/.test(telefono)) {
            validaciones2.telefono = true;
            $("#telefono").removeClass("is-invalid");
            $("#telefono").addClass("is-valid");
        } else {
            validaciones2.telefono = false;
            $("#telefono").removeClass("is-valid");
            $("#telefono").addClass("is-invalid");
        }
    })


    $("#estadoCivil").on("change", function (event) {
        let estadoCivil = $("#estadoCivil").val();
        let estadosPermitido = ["casado/a", "soltero/a", "viudo/a"];
        if (estadosPermitido.includes(estadoCivil)) {
            validaciones2.estadoCivil = true;
            $("#estadoCivil").removeClass("is-invalid");
            $("#estadoCivil").addClass("is-valid");
        } else {
            validaciones2.estadoCivil = false;
            $("#estadoCivil").removeClass("is-valid");
            $("#estadoCivil").addClass("is-invalid");
        }
    })



    $("#fechaConvercion").on("change", function (event) {
        let fechaConvercion = $("#fechaConvercion").val();
        if (/^.+$/.test(fechaConvercion)) {
            validaciones2.fechaConvercion = true;
            $("#fechaConvercion").removeClass("is-invalid");
            $("#fechaConvercion").addClass("is-valid");
        } else {
            validaciones2.fechaConvercion = false;
            $("#fechaConvercion").removeClass("is-valid");
            $("#fechaConvercion").addClass("is-invalid");
        }
    })


    $("#idConsolidador").on("change", function (event) {

        let idConsolidador = $("#idConsolidador").val();
        if (/^[1-9]\d*$/.test(idConsolidador)) {
            validaciones2.idConsolidador = true;
            $("#msj_idConsolidador").addClass("d-none");
        } else {
            console.log(idConsolidador);
            validaciones2.idConsolidador = false;
            $("#msj_idConsolidador").removeClass("d-none");
        }
    })


    $("#idCelulaConsolidacion").on("change", function (event) {
        let idcelulaconsolidacion = $("#idCelulaConsolidacion").val();
        if (/^[1-9]\d*$/.test(idcelulaconsolidacion)) {
            validaciones2.idcelulaconsolidacion = true;
            $("#msj_idCelulaConsolidacion").addClass("d-none");
        } else {
            validaciones2.idcelulaconsolidacion = false;
            $("#msj_idCelulaConsolidacion").removeClass("d-none");
           
        }



    });


    $("#fechaNacimiento").on("change", function (event) {

        const fechaNacimiento = $("#fechaNacimiento").val();
        const hoy = new Date();
        const cumpleanos18 = new Date(fechaNacimiento);
        cumpleanos18.setFullYear(cumpleanos18.getFullYear() + 18);

        if (hoy >= cumpleanos18) {
            validaciones2.fechaNacimiento = true;
            $("#fechaNacimiento").removeClass("is-invalid");
            $("#fechaNacimiento").addClass("is-valid");
        } else {
            validaciones2.fechaNacimiento = false;
            $("#fechaNacimiento").removeClass("is-valid");
            $("#fechaNacimiento").addClass("is-invalid");
        }
    })






    /////////////////////////////// REGISTRAR DISCIPULOS ///////////////////////////////////

    $("#formulario").submit(function (event) {
        event.preventDefault();

        if (Object.values(validaciones2).every(status => status === true)) {


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


            const dato = {
                apellido: $("#apellido"),
                cedula: $("#cedula"),
                telefono: $("#telefono"),
                estadoCivil: $("#estadoCivil"),
                fechaNacimiento: $("#fechaNacimiento"),
                fechaConvercion: $("#fechaConvercion"),
                idConsolidador: $("#idConsolidador"),
                idCelulaConsolidacion: $("#idCelulaConsolidacion"),
                nombre: $("#nombre"),
                direccion: $("#direccion"),
                motivo: $("#motivo")
             }

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Discipulos/Index",
                data: {

                    registrar: 'registrar',
                    nombre: dato.nombre.val(),
                    apellido: dato.apellido.val(),
                    cedula: dato.cedula.val(),
                    telefono: dato.telefono.val(),
                    estadoCivil: dato.estadoCivil.val(),
                    fechaNacimiento: dato.fechaNacimiento.val(),
                    fechaConvercion: dato.fechaConvercion.val(),
                    asisCrecimiento: asisCrecimiento,
                    asisFamiliar: asisFamiliar,
                    idConsolidador: dato.idConsolidador.val(),
                    idCelulaConsolidacion: dato.idCelulaConsolidacion.val(),
                    direccion: dato.direccion.val(),
                    motivo: dato.motivo.val()
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);

                    dataTable.ajax.reload();
                    document.getElementById('formulario').reset()
                
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    for (const key in dato) {
                        const input = dato[key];
                        input.removeClass("is-valid");
                      }

                      choices1.destroy();
                      choices2.destroy();
                    
                      $('#modal_registrar').modal('hide');
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



    $('#cerrarRegistrar').on('click', function () {
    
        document.getElementById('formulario').reset();
    
        const dato = {
            apellido: $("#apellido"),
            cedula: $("#cedula"),
            telefono: $("#telefono"),
            estadoCivil: $("#estadoCivil"),
            fechaNacimiento: $("#fechaNacimiento"),
            fechaConvercion: $("#fechaConvercion"),
            nombre: $("#nombre"),
            direccion: $("#direccion"),
            motivo: $("#motivo")
         }

         for (const key in dato) {
            const input = dato[key];
            input.removeClass("is-valid");
            input.removeClass("is-invalid")
          }

          $("#idConsolidador").addClass("d-none");
          $("#idCelulaConsolidacion").addClass("d-none");

    
    
        $('#modal_registrar').modal('hide');
        
      });
    
      $('#cerrarEditar').on('click', function () {
        
        const dato = {
            apellido: $("#apellido2"),
            cedula: $("#cedula2"),
            telefono: $("#telefono2"),
            estadoCivil: $("#estadoCivil2"),
            fechaNacimiento: $("#fechaNacimiento2"),
            fechaConvercion: $("#fechaConvercion2"),
            nombre: $("#nombre2"),
            direccion: $("#direccion2"),
            motivo: $("#motivo2")
         }


         for (const key in dato) {
            const input = dato[key];
            input.removeClass("is-valid");
            input.removeClass("is-invalid")
          }

          $("#idConsolidador2").addClass("d-none");
          $("#idCelulaConsolidacion2").addClass("d-none");
    
        $('#modal_editarInfo').modal('hide');
    
      });


});


