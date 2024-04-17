$(document).ready(function () {
    let validCedula = false;
    let datatables1;
    let datatables4;

    const dataTable = $('#estudiantesDatatables').DataTable({
        info: false,
        lengthChange: false,
        pageLength: 15,
        dom: 'ltipB',
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
            url: '/AppwebMVC/Estudiantes/Index',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'cedula' },
            { data: 'nombre' },
            { data: 'apellido' },
            { data: 'fechaInscripcionEscuela'},
            // { data: 'cursando' },
            {
                data: null,
                render: function (data, type, row, meta) {

                    let botonHistorial = `<a type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modalHistorial" title="Ver Historial" ,><i class="fa-solid fa-circle-info" ></i></a>`;

                    let div = `
      <div class="acciones">
                ${botonHistorial}

      </div>
      `
                    return div;
                }
            },
        ],
    });


    $('#estudiantesDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        let text = `<strong>Estudiante: ${datos.nombre} ${datos.apellido}</strong>`;
        $('#nombreEstudiante2').html(text);
        $('#nombreEstudiante').html(text);


        $('#idEstudiante').text(datos.id);

        console.log(datos.id);

        listarGrupos(datos.id);

    });

    $('#search').keyup(function () {
        dataTable.search($(this).val()).draw();
    });

    $('#search1').keyup(function () {
        datatables1.search($(this).val()).draw();
    });



    $("#cedula").on("keyup", function (event) {
        const cedula = document.getElementById("cedula").value;

        if (/^[0-9]{7,8}$/.test(cedula)) {
            validCedula = true;
            $("#cedula").removeClass("is-invalid");
            $("#cedula").addClass("is-valid");
            document.getElementById('msj_cedula').textContent = '';

        } else {
            validCedula = false;
            $("#cedula").removeClass("is-valid");
            $("#cedula").addClass("is-invalid");
            document.getElementById('msj_cedula').textContent = 'Escriba la cedula correctamente';

        }
    });


    $("#formulario").submit(function (event) {

        event.preventDefault();


        if (validCedula === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Estudiantes/Index",
                data: {

                    validarRegistrar: 'validarRegistrar',
                    cedula: document.getElementById("cedula").value,
                },
                success: function (response) {

                    let data = JSON.parse(response);

                    const text = `¿Estas Seguro de inscribir a ${data.nombreCompleto}
                     titular de la cedula ${data.cedula} en la Escuela de Impulso y Desarrollo?`;


                    Swal.fire({
                        title: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#007bff',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                type: "POST",
                                url: "/AppwebMVC/Estudiantes/Index",
                                data: {
                                    registrar: 'registrar',
                                    id: data.id,
                                    tipo: data.tipo
                                },
                                success: function (response) {
                                    // console.log(response);
                                    let data = JSON.parse(response);
                                    dataTable.ajax.reload();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Registrado con exito',
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });

                                    $('#agregar').modal('hide');
                                    document.getElementById('formulario').reset();
                                    validCedula = false;
                                    $("#cedula").removeClass("is-valid");
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


    




    function listarGrupos($id) {

        if (datatables1) {
            datatables1.destroy();
        }

        datatables1 = $('#Historial').DataTable({

            info: false,
            lengthChange: false,
            pageLength: 15,
            dom: 'ltipB',
            searching: true,
            language: {
                url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
            },

            drawCallback: function (settings) {
                var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                pagination.toggle(this.api().page.info().pages > 1);
            },
            ajax: {
                method: "POST",
                url: '/AppwebMVC/Grupos/Index',
                data: {
                    cargar_dataHistorial: 'cargar_dataHistorial',
                    id: $id 
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'infoMentor' },
                { data: 'notaTotal' },
                { data: 'fechaInicio' },
                { data: 'fechaFin' },
                { data: 'estadoMatricula' },
                {
                    data: null,
                    render: function (data, type, row, meta) {


                        let notas = permisos.consultar ? `<a role="button" id="verNotas" data-bs-toggle="modal" title="Ver Notas" data-bs-target="#modal_notas" ><i class="fa-regular fa-clipboard"></i></a>` : '';

                            let div = `
                         <div class="acciones">       
                         ${notas}
                         </div>`
                       
                        return div;
                    }
                },
            ],
        });



        $('#Historial tbody').on('click', '#verNotas', function () {
            const datos = datatables1.row($(this).parents()).data();
    
    
    
            let text = `<strong>Grupo: ${datos.codigo}</strong>`;
            $('#tituloNotas').html(text);
           
            let text2 = `<strong>Nota Total: ${datos.notaTotal}</strong>`;
            $('#notaTotal').html(text2);
    

            let idEstudiante = $('#idEstudiante').text();
    
            NotasEstudiantes(idEstudiante, datos.id);
    
        });
    

        }


        function NotasEstudiantes(idEstudiante, idGrupo) {

            if (datatables4) {
                datatables4.destroy();
            }
    
            datatables4 = $('#NotasEstudiante').DataTable({
    
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
                    method: "POST",
                    url: '/AppwebMVC/Grupos/Index',
                    data: {
                        cargarNotasEstudiante: 'cargarNotasEstudiante',
                        idEstudiante: idEstudiante,
                        idGrupo: idGrupo
                    }
                },
                columns: [
                    { data: 'titulo' },
                    { data: 'ponderacion' },
                    { data: 'calificacion' },
                ],
            });
    
    
        }
        $('#search2').keyup(function () {
            datatables1.search($(this).val()).draw();
        });

        $('#cerrarRegistrar').on('click', function () {
             
            document.getElementById('formulario').reset();
           
            $("#cedula").removeClass("is-valid");
            $("#cedula").removeClass("is-invalid");

        
            $('#modal_registrar').modal('hide');
        
          });


    
   

});