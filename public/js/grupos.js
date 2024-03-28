$(document).ready(function () {

    let choices1;
    let choices2;
    let datatables;
    let datatables1;
    let datatables2;
    let datatables3;
    let datatables4;
    let ponderacion1;
    let validcedula = false;

    if(permisos.rolEstudiante == true){
      
        listarGrupos(4)
        $("#activo").removeClass("active");
        $("#misGrupos").addClass("active")

    }else{
    
        listarGrupos(2)
    }
    // Variable que almacena el objeto Quill JS
    let editorQuill;


    const validClase = {
        titulo: false,
        Objetivo: false,
        ponderacion: false
    };

    $("#activo").on("click", function (event) {
        listarGrupos(2)
    });
    $("#abierto").on("click", function (event) {
        listarGrupos(1)
    });
    $("#cerrado").on("click", function (event) {
        listarGrupos(3)
    });
    $("#misGrupos").on("click", function (event) {
        listarGrupos(4)
    });

    function listarGrupos(tipo) {

        if (datatables) {
            datatables.destroy();
        }

        datatables = $('#Grupos').DataTable({

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
                    cargar_data: 'cargar_data',
                    tipo: tipo
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'infoMentor' },
                { data: 'estudiantes' },
                { data: 'fechaInicio' },
                { data: 'fechaFin' },
                {
                    data: null,
                    render: function (data, type, row, meta) {


                        let botonEditarGrupoAbierto = permisos.actualizar ? `<a role="button" id="editarGrupoAbierto" data-bs-toggle="modal" title="Editar Nombre" data-bs-target="#modal_registrar" ><i class="fa-solid fa-pen" ></i></a>` : '';

                        let Clases = permisos.actualizar ? `<a role="button" id="clases" data-bs-toggle="modal" title="Clases" data-bs-target="#modalClases" ><i class="fa-solid fa-chalkboard"></i></a>` : '';

                        let activar = permisos.registrar ? ` <a role="button" id="activar" title="Activar Grupo"><i class="fa-solid fa-school-circle-check"></i></a>` : '';

                        let cerrar = permisos.registrar ? ` <a role="button" id="cerrarGrupo" title="Cerrar Grupo"><i class="fa-solid fa-school-lock"></i></a>` : '';

                        let botonEliminar = permisos.eliminar ? `<a role="button"  id=eliminarGrupo title="Eliminar Grupo"><i class="fa-solid fa-trash" ></i></a>` : '';

                        let Matricula = permisos.registrar ? `<a role="button" id="registrarMatricula" data-bs-toggle="modal" data-bs-target="#modal_registroMatricula" title="Registrar Matricula"><i class="fa-solid fa-users"></i></a>` : '';

                        let Matricula2 = permisos.registrar ? `<a role="button" id="Matricula2" data-bs-toggle="modal" data-bs-target="#modal_registroMatricula" title="Matricula"><i class="fa-solid fa-users"></i></a>` : '';
                        
                        let Matricula3 = permisos.registrar ? `<a role="button" id="Matricula3" data-bs-toggle="modal" data-bs-target="#modal_registroMatricula" title="Matricula"><i class="fa-solid fa-users"></i></a>` : '';

                         let asignarRoles = `<a role="button" id="asignarRoles${data.id}" class="d-none" title="Asignar Roles"><i class="fa-solid fa-graduation-cap"></i></a>`;
                         validarAsignarRoles(data.id, data.idEid);

                        let div = '';


                        if (tipo == '1') {
                            div = `
                        <div class="acciones">
                      
                        ${activar}
                        ${Matricula}
                        ${botonEditarGrupoAbierto}
                        ${botonEliminar}
                        </div>
                        `}

                        if (tipo == '2') {
                            div = `
                         <div class="acciones">
                         ${Clases}
                         ${Matricula2}
                         ${cerrar}
        
                         </div>
                         `}

                        if (tipo == '3') {
                            div = `
                         <div class="acciones" id="guia2">
                         
                         ${Clases}
                         ${Matricula3}
                         ${asignarRoles}
        
                         </div>
                         `}
                        return div;
                    }
                },
            ],
        });

        if (tipo == '1' || tipo == '2') {
            datatables.column(4).visible(false);
        }
    };

    $('#Grupos').on('click', '#guia2', function (e) {
        const datos = datatables.row($(this).parents()).data();
       
        $(`#Grupos`).on(`click`, `#asignarRoles${datos.id}`, function (e) {

            Swal.fire({
                title: '¿Estas Seguro? esta acción solo se puedo hacer una vez.',
                text: "Se asignaran los roles adquiridos a los estudiantes que hayan aprobado en su totalidad esta Eid.",
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
                        url: "/AppwebMVC/Grupos/Index",
                        data: {
    
                            asignarRolesAdqr: 'asignarRolesAdqr',
                            idGrupo: datos.id,
                            idEid: datos.idEid,
                        },
                        success: function (response) {
                            let jsonResponse = JSON.parse(response);
        
                            Swal.fire({
                                icon: 'success',
                                title: '¡Roles Asignados!',
                                text: jsonResponse.msj,
    
                            })
                            datatables.ajax.reload();
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
});





    function validarAsignarRoles(idGrupo, idEid) {

        $.ajax({
            type: "POST",
            url: "/AppwebMVC/Grupos/Index",
            data: {

                validarAsignarRoles: 'validarAsignarRoles',
                idGrupo: idGrupo,
                idEid: idEid
            },
            success: function (response) {
                let data = JSON.parse(response);
               
                if (data == true){
                    
                    const boton = $('#Grupos tbody').find(`#asignarRoles${idGrupo}`);
                    boton.removeClass("d-none");
                    
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
        })
       

    }

   


    $('#Grupos tbody').on('click', '#registrarMatricula', function () {
        const datos = datatables.row($(this).parents()).data();

        let div = permisos.registrar ? `<form id="formulario1">
        <div class="mb-2 mt-2">
            <p id="GrupoMatricula" class="visually-hidden"></p>
            <div class="row g-3"">               
                <div class="col-7">                   
                    <input type="number" maxlength="12" id="cedula" placeholder="Cedula de el Estudiante" class="form-control" min="0"  aria-describedby="msj_cedula">
                                        <div id="msj_cedula" class="invalid-feedback"></div>
                    </div>
                <div class="col-5">
                <div class="d-flex justify-content-end gap-1">
                        <button type="button" id="registrarEst"
                            class="btn btn-primary">Inscribir</button>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </form>` : '';


        document.getElementById('registrarEstudiante').innerHTML = div;

        $('#registrarEstudiante form').find('#GrupoMatricula').text(datos.id);

        let text = `Grupo: ${datos.codigo}`;
        $('#titulomatricula').text(text);


        ListarMatricula(datos.id, 1);

    });


    $('#Grupos tbody').on('click', '#Matricula2', function () {
        const datos = datatables.row($(this).parents()).data();

        let text = `Grupo: ${datos.codigo}`;
        $('#titulomatricula').text(text);

        document.getElementById('registrarEstudiante').innerHTML = '';

        ListarMatricula(datos.id, 2);

    });

    $('#Grupos tbody').on('click', '#Matricula3', function () {
        const datos = datatables.row($(this).parents()).data();

        let text = `Grupo: ${datos.codigo}`;
        $('#titulomatricula').text(text);

        document.getElementById('registrarEstudiante').innerHTML = '';

        ListarMatricula(datos.id, 3);

    });

    $('#Grupos tbody').on('click', '#clases', function () {
        const datos = datatables.row($(this).parents()).data();

        document.getElementById('idGrupo1').textContent = datos.id;


        let text = `Grupo: ${datos.codigo}`;
        $('#tituloClases').text(text);

        listarClases(datos.id);


    });



    $('#registrarEstudiante').on('keyup', '#cedula', function (e) {

        const cedula = $("#cedula").val();

        if (/^[0-9]{7,8}$/.test(cedula)) {
            validcedula = true;
            $("#cedula").removeClass("is-invalid");
            $("#cedula").addClass("is-valid");
            document.getElementById('msj_cedula').textContent = '';

        } else {
            validcedula = false;
            $("#cedula").removeClass("is-valid");
            $("#cedula").addClass("is-invalid");
            document.getElementById('msj_cedula').textContent = 'La Cedula es obligatoria';

        }
    });


    $('#titulo').on("keyup", function (event) {

        const titulo = $("#titulo").val();

        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/.test(titulo)) {
            validClase.titulo = true;
            $("#titulo").removeClass("is-invalid");
            $("#titulo").addClass("is-valid");
            document.getElementById('msj_titulo').textContent = '';

        } else {
            validClase.titulo = false;
            $("#titulo").removeClass("is-valid");
            $("#titulo").addClass("is-invalid");
            document.getElementById('msj_titulo').textContent = 'El titulo es obligatorio y debe tener mas de 5 caracteres';

        }
    });

    $('#Objetivo').on("keyup", function (event) {

        const Objetivo = $("#Objetivo").val();

        if (/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,200}$/.test(Objetivo)) {
            validClase.Objetivo = true;
            $("#Objetivo").removeClass("is-invalid");
            $("#Objetivo").addClass("is-valid");
            document.getElementById('msj_Objetivo').textContent = '';

        } else {
            validClase.Objetivo = false;
            $("#Objetivo").removeClass("is-valid");
            $("#Objetivo").addClass("is-invalid");
            document.getElementById('msj_Objetivo').textContent = 'Este campo es obligatorio y debe tener mas de 5 caracteres';

        }
    });



    $('#ponderacion').on("keyup", function (event) {


        const ponderacion = $("#ponderacion").val();


        if (/^\s*$/.test(ponderacion)) {
            ponderacion1 = '0';
            $("#ponderacion").removeClass("is-invalid");
            $("#ponderacion").addClass("is-valid");
            document.getElementById('msj_ponderacion').textContent = '';
            validClase.ponderacion = true;

        } else {

            if (/^([0-9]+)(\.[0-9]{2})$/.test(ponderacion)) {
              
                    $("#ponderacion").removeClass("is-invalid");
                    $("#ponderacion").addClass("is-valid");
                    document.getElementById('msj_ponderacion').textContent = '';
                    validClase.ponderacion = true;
                    ponderacion1 = $("#ponderacion").val();
                }else if (/^[0-9]+$/.test(ponderacion)) {

                    $("#ponderacion").removeClass("is-invalid");
                    $("#ponderacion").addClass("is-valid");
                    document.getElementById('msj_ponderacion').textContent = '';
                    validClase.ponderacion = true;
                    ponderacion1 = $("#ponderacion").val();
                } else {
                    validClase.ponderacion = false;
                $("#ponderacion").removeClass("is-valid");
                $("#ponderacion").addClass("is-invalid");
                document.getElementById('msj_ponderacion').textContent = 'El formato es incorrecto, si se coloca 0 o se deja en blanco este campo se entendera que la clase no es evaluada';

                }

            } 
        



    });



    $("#registrarClase").on("click", function () {
        registrarEditarClase('Registrar')
    });

    $("#editarClase").on("click", function () {
        registrarEditarClase('Editar')
    });

    function registrarEditarClase(accion) {


        const ponderacion = $("#ponderacion").val();

        if (/^\s*$/.test(ponderacion)) {
            ponderacion1 = '0';
            $("#ponderacion").removeClass("is-invalid");
            $("#ponderacion").addClass("is-valid");
            document.getElementById('msj_ponderacion').textContent = '';
            validClase.ponderacion = true;

        } else {

            if (/^([0-9]+)(\.[0-9]{2})$/.test(ponderacion)) {
              
                    $("#ponderacion").removeClass("is-invalid");
                    $("#ponderacion").addClass("is-valid");
                    document.getElementById('msj_ponderacion').textContent = '';
                    validClase.ponderacion = true;
                    ponderacion1 = $("#ponderacion").val();
                }else if (/^[0-9]+$/.test(ponderacion)) {

                    $("#ponderacion").removeClass("is-invalid");
                    $("#ponderacion").addClass("is-valid");
                    document.getElementById('msj_ponderacion').textContent = '';
                    validClase.ponderacion = true;
                    ponderacion1 = $("#ponderacion").val();
                } else {
                    validClase.ponderacion = false;
                $("#ponderacion").removeClass("is-valid");
                $("#ponderacion").addClass("is-invalid");
                document.getElementById('msj_ponderacion').textContent = 'El formato es incorrecto, si indica decimal debe ser 0,00. Dejar en blanco este campo o en "0" se entendera que la clase no es evaluada';

                }

            } 





        if (Object.values(validClase).every(status => status === true)) {



            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Grupos/Clase",
                data: {

                    registrar_editar: 'registrar_editar',
                    accion: accion,
                    idGrupo: document.getElementById("idGrupo1").textContent,
                    idClase: document.getElementById("idClase").textContent,
                    titulo: $("#titulo").val(),
                    Objetivo: $("#Objetivo").val(),
                    ponderacion: ponderacion1
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);

                    document.getElementById('formulario2').reset();

                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                    });

                    for (const key in validClase) {
                        validClase[key] = false;
                    }
                    $("#titulo").removeClass("is-valid");
                    $("#Objetivo").removeClass("is-valid");
                    $("#ponderacion").removeClass("is-valid");

                    datatables2.ajax.reload();

                    if (accion == 'Editar') {
                        $("#registrarClase").removeClass("d-none");
                        $("#editarClase").addClass("d-none");
                        $("#cancelar4").addClass("d-none");
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
                title: 'Formulario invalido. Verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }

    };

    function ListarMatricula(idGrupo, tipo) {

        if (datatables1) {
            datatables1.destroy();
        }

        datatables1 = $('#Matricula').DataTable({

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
                    cargarMatricula: 'cargarMatricula',
                    idGrupo: idGrupo
                }
            },
            columns: [
                { data: 'cedula' },
                { data: 'nombres' },
                { data: 'notaTotal' },
                { data: 'notaAcumulada' },
                { data: 'estado' },
                {
                    data: null,
                    render: function (data, type, row, meta) {


                        let botonEditarGrupoAbierto = permisos.actualizar ? `<a role="button" id="editarGrupoAbierto" data-bs-toggle="modal" title="Editar Nombre" data-bs-target="#modal_registrar" ><i class="fa-solid fa-pen" ></i></a>` : '';

                        let botonRequisitos = permisos.actualizar ? `<a role="button" id="requisitos" data-bs-toggle="modal" title="Editar Requisitos" data-bs-target="#modal_requisitos" ><i class="fa-solid fa-key" ></i></a>` : '';

                        let botonModulo = permisos.registrar ? `<a role="button" id="Modulo" data-bs-toggle="modal" title="Gestionar Modulos" data-bs-target="#modal_modulos" ><i class="fa-solid fa-users"></i></a>` : '';

                        let botonEliminar = permisos.eliminar ? `<a role="button"  id=eliminarEstudiante title="Eliminar estudiante"><i class="fa-solid fa-trash" ></i></a>` : '';

                        let verNotasEstudiante = permisos.consultar ? `<a role="button" id="verNotasEstudiante" title="Consultar Notas"><i class="fa-regular fa-clipboard"></i></a>` : '';

                        let div = '';

                        if (tipo == '1') { div = `<div class="acciones">${botonEliminar}</div>` }
                        if (tipo == '2' || tipo == '3') { div = `<div class="acciones">${verNotasEstudiante}</div>` }
                        return div;
                    }
                },
            ],
        });

        if (tipo == '1') {
            datatables1.column(2).visible(false);
            datatables1.column(3).visible(false);
            datatables1.column(4).visible(false);

        }

        if (tipo == '2') {
            datatables1.column(2).visible(false);
            datatables1.column(4).visible(false);
        }

        if (tipo == '3') {

            datatables1.column(3).visible(false);
        }
    };

    $('#Matricula tbody').on('click', '#eliminarEstudiante', function () {
        const datos = datatables1.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a este Grupo otra vez",
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
                    url: "/AppwebMVC/Grupos/Index",
                    data: {

                        eliminarMatricula: 'eliminarMatricula',
                        id: datos.id,
                        idGrupo: $("#GrupoMatricula").text(),
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables.ajax.reload();
                        datatables1.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'Grupo borrado con Exito',

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

    $('#Grupos tbody').on('click', '#editarGrupoAbierto', function () {
        const datos = datatables.row($(this).parents()).data();

        for (const key in validacion1) {
            validacion1[key] = true;
        }

        document.getElementById('Grupo').textContent = datos.id;
        document.getElementById('accion').textContent = 'editar';
        document.getElementById('titulo1').textContent = 'Actualizar Grupo';
        document.getElementById('submitRE').textContent = 'Actualizar';



        Listar_Niveles(datos.idNivel, 2);
        Listar_Mentores(datos.idMentor, 2);


    });

    $('#Grupos tbody').on('click', '#eliminarGrupo', function () {
        const datos = datatables.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a este Grupo otra vez",
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
                    url: "/AppwebMVC/Grupos/Index",
                    data: {

                        eliminar: 'eliminar',
                        id: datos.id,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'Grupo borrado con Exito',
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

    $('#Grupos tbody').on('click', '#activar', function () {
        const datos = datatables.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro de Activar este grupo?',
            text: "Ya no podras editar la matricula ni cambiar otros datos",
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
                    url: "/AppwebMVC/Grupos/Index",
                    data: {

                        activarGrupo: 'activarGrupo',
                        id: datos.id,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'El grupo ahora esta Activo',
                            text: data.msj,
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

    $('#Grupos tbody').on('click', '#cerrarGrupo', function () {
        const datos = datatables.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro de Cerrar este grupo?',
            text: "Ya no podras agregar ni editar calificaciones, al cerrar el grupo se pasara nota final a los estudiantes",
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
                    url: "/AppwebMVC/Grupos/Index",
                    data: {

                        cerrarGrupo: 'cerrarGrupo',
                        id: datos.id,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'El grupo ahora esta Cerrado',
                            text: data.msj,

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



    function Listar_Niveles(idNivel, opcion) {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/Grupos/Index",
            data: {

                ListaNiveles: 'ListaNiveles',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idNivel');

                if (opcion == 1) {

                    selector.innerHTML = '';
                    const newOption = document.createElement('option');
                    newOption.value = '';
                    newOption.textContent = 'Seleccione un Nivel';
                    newOption.disabled = true;
                    newOption.selected = true;
                    selector.appendChild(newOption);
                } else {
                    selector.innerHTML = '';
                }



                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo}`;
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

                // Destruir la instancia existente si la hay

                if (idNivel !== '') {
                    choices1.setChoiceByValue(idNivel.toString())
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }

    function Listar_Mentores(idMentor, opcion) {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/Grupos/Index",
            data: {

                ListarMentores: 'ListarMentores',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idMentor');
                if (opcion == 1) {

                    selector.innerHTML = '';
                    const newOption = document.createElement('option');
                    newOption.value = '';
                    newOption.textContent = 'Seleccione un Mentor';
                    newOption.disabled = true;
                    newOption.selected = true;
                    selector.appendChild(newOption);
                } else {
                    selector.innerHTML = '';
                }


                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
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

                // Destruir la instancia existente si la hay


                if (idMentor !== '') {
                    choices2.setChoiceByValue(idMentor.toString())
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }

    // // // // // // // // // // // REGISTRAR o EDITAR GRUPO

    $('#registrar').on('click', function () {

        document.getElementById('accion').textContent = 'registrar';
        document.getElementById('titulo1').textContent = 'Registrar Grupo';
        document.getElementById('submitRE').textContent = 'Registrar';


        Listar_Niveles('', 1);
        Listar_Mentores('', 1);
    });

    const validacion1 = {
        idNivel: false,
        idMentor: false,
    };

    // Validar Nivel

    $("#idNivel").on("change", function (event) {
        const idNivel = document.getElementById("idNivel").value;
        const div = document.getElementById("msj_idNivel");
        if (!/^[1-9]\d*$/.test(idNivel)) {
            div.classList.remove("d-none");
            div.innerText = "Este campo es obligatorio";

            validacion1.idNivel = false;
        } else {
            div.classList.add("d-none");
            div.innerText = "";

            validacion1.idNivel = true;
        }

    });

    // Validar Mentor

    $("#idMentor").on("change", function (event) {
        const idMentor = document.getElementById("idMentor").value;
        const div = document.getElementById("msj_idMentor");
        if (!/^[1-9]\d*$/.test(idMentor)) {
            div.classList.remove("d-none");
            div.innerText = "Este campo es obligatorio";

            validacion1.idMentor = false;
        } else {
            div.classList.add("d-none");
            div.innerText = "";

            validacion1.idMentor = true;
        }

    });


    const form = document.getElementById("formulario");
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validacion1).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Grupos/Index",
                data: {
                    registrar_editar: 'registrar_editar',
                    accion: document.getElementById("accion").textContent,
                    idGrupo: document.getElementById("Grupo").textContent,
                    idNivel: document.getElementById("idNivel").value,
                    idMentor: document.getElementById("idMentor").value,

                },
                success: function (response) {


                    console.log(response);
                    datatables.ajax.reload();
                    $('#modal_registrar').modal('hide');
                    for (const key in validacion1) {
                        validacion1[key] = false;
                    }

                    let data = JSON.parse(response);
                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 3000,
                    });


                    if (choices1) {
                        choices1.destroy();
                    }
                    if (choices2) {
                        choices2.destroy();
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
                title: 'Formulario invalido. Verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });


    $('#cerrarRegistrar').on('click', function () {

        document.getElementById('formulario').reset();

        $("#msj_idNivel").addClass("d-none");
        $("#msj_idMentor").addClass("d-none");


        for (const key in validacion1) {
            validacion1[key] = false;
        }

        if (choices1) {
            choices1.destroy();
        }
        if (choices2) {
            choices2.destroy();
        }

        $('#modal_registrar').modal('hide');



    });


    // // // // // // // //   REGISTRAR MATRICULA



    $("#cerrarmatricula").on("click", function (event) {


        $("#cedula").removeClass("is-valid");
        $("#cedula").removeClass("is-invalid");
        validcedula = false;

        $('#modal_registroMatricula').modal('hide');
        
        $("#EstudianteNAV").addClass("d-none");
        $("#EstudianteNAV").removeClass("active");
        $("#tab-Estudiante").removeClass("active");
        $("#MatriculaNAV").addClass("active");
        $("#tab-Matricula").addClass("active");

        document.getElementById('formulario1').reset();

    })


    $("#cerrarClases").on("click", function (event) {

        document.getElementById('formulario2').reset();

        for (const key in validClase) {
            validClase[key] = false;
        }
        $("#titulo").removeClass("is-valid");
        $("#Objetivo").removeClass("is-valid");
        $("#ponderacion").removeClass("is-valid");
        $("#titulo").removeClass("is-invalid");
        $("#Objetivo").removeClass("is-invalid");
        $("#ponderacion").removeClass("is-invalid");


        $('#modalClases').modal('hide');

        $("#infoNAV").removeClass("active");
        $("#notasNAV").removeClass("active");

        $("#infoNAV").addClass("d-none");
        $("#notasNAV").addClass("d-none");

        $("#tab-info").removeClass("active");
        $("#tab-notas").removeClass("active");


        $("#clasesNAV").addClass("active");
        $("#tab-clases").addClass("active");


    })




    $('#registrarEstudiante').on('click', '#registrarEst', function (e) {

        if (validcedula === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Grupos/Index",
                data: {

                    registroEstudiante: 'registroEstudiante',
                    cedula: document.getElementById("cedula").value,
                    idGrupo: $("#GrupoMatricula").text()
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    datatables.ajax.reload();
                    datatables1.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                    });

                    $("#cedula").removeClass("is-valid");
                    validcedula = false;
                    document.getElementById('formulario1').reset();


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
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


    function listarClases(idGrupo) {

        if (datatables2) {
            datatables2.destroy();
        }

        datatables2 = $('#ClaseDatatables').DataTable({

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
                url: '/AppwebMVC/Grupos/Clase',
                data: {
                    cargarClase: 'cargarClase',
                    idGrupo: idGrupo
                }
            },
            columns: [
                { data: 'titulo' },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let ponderacion = data.ponderacion > 0 ? data.ponderacion : 'No tiene';
                        return ponderacion;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {


                        let info = permisos.actualizar ? `<a role="button" id="infoClaseACT" title="Informacion" ><i class="fa-solid fa-circle-info"></i></a>` : '';

                        let botonEditar = permisos.actualizar ? `<a role="button" id="editarClase" title="Editar Clase"><i class="fa-solid fa-pen" ></i></a>` : '';

                        let botonEliminar = permisos.eliminar ? `<a role="button"  id="eliminarClase" title="Eliminar Clase"><i class="fa-solid fa-trash" ></i></a>` : '';

                        let contenido = `<a role="button" id="contenidoBoton" title="Contenido"><i class="fa-solid fa-book" data-bs-toggle="modal" href="#contenidoModal"></i></a>`

                        let editarNota;

                        let div = '';

                        if (data.ponderacion > 0) {
                            editarNota = permisos.registrar ? `<a role="button" id="notasACT" title="Actualizar Notas"><i class="fa-regular fa-clipboard"></i></a>` : '';
                        } else {
                            editarNota = '';
                        }


                        div = `
              <div class="acciones">
                        ${info}
                        ${botonEditar}    
                        ${botonEliminar}
                        ${editarNota}
                        ${contenido}
              </div>
              `
                        return div;
                    }
                },
            ],
        });

    };

    $("#ClaseDatatables tbody").on('click', '#editarClase', function (event) {
        const datos = datatables2.row($(this).parents()).data();

        document.getElementById('idClase').textContent = datos.id;
        document.getElementById('titulo').value = datos.titulo;
        document.getElementById('ponderacion').value = datos.ponderacion;
        document.getElementById('Objetivo').value = datos.objetivo;

        $("#registrarClase").addClass("d-none");
        $("#editarClase").removeClass("d-none");
        $("#cancelar4").removeClass("d-none");

        for (const key in validClase) {
            validClase[key] = true;
        }

    });

    $("#cancelar4").on("click", function (event) {

        document.getElementById('formulario2').reset();

        for (const key in validClase) {
            validClase[key] = false;
        }
        $("#titulo").removeClass("is-valid");
        $("#Objetivo").removeClass("is-valid");
        $("#ponderacion").removeClass("is-valid");
        $("#titulo").removeClass("is-invalid");
        $("#Objetivo").removeClass("is-invalid");
        $("#ponderacion").removeClass("is-invalid");


        $("#registrarClase").removeClass("d-none");
        $("#editarClase").addClass("d-none");
        $("#cancelar4").addClass("d-none");


    })

    $('#ClaseDatatables tbody').on('click', '#eliminarClase', function () {
        const datos = datatables2.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a esta Clase otra vez",
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
                    url: "/AppwebMVC/Grupos/Clase",
                    data: {

                        eliminar: 'eliminar',
                        id: datos.id,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables2.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: data.msj,

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

    $('#ClaseDatatables tbody').on('click', '#infoClaseACT', function () {
        const datos = datatables2.row($(this).parents()).data();

        $("#infoNAV").removeClass("d-none");
        $("#clasesNAV").removeClass("active");
        $("#tab-info").addClass("active");
        $("#tab-clases").removeClass("active");
        $("#infoNAV").addClass("active");

        document.getElementById('inf_ponderacion').textContent = datos.ponderacion;
        document.getElementById('inf_objetivo').textContent = datos.objetivo;

        let text = `Clase: ${datos.titulo}`;
        $('#cartaClases').text(text);

    });




    $('#Matricula tbody').on('click', '#verNotasEstudiante', function () {
        const datos = datatables1.row($(this).parents()).data();

        $("#EstudianteNAV").removeClass("d-none");
        $("#MatriculaNAV").removeClass("active");
        $("#tab-Estudiante").addClass("active");
        $("#tab-Matricula").removeClass("active");
        $("#EstudianteNAV").addClass("active");


        $('#EstudianteNAV').text('Notas');

        let text = `<strong>Estudiante: ${datos.nombres} C.I: ${datos.cedula}.</strong>`;
        $('#nombreEstudiante2').html(text);
        let text2;

        if(datos.notaTotal > '0'){

            text2 = `Nota Total: ${datos.notaTotal}%`;

        }else{

            text2 = `Nota acumulada: ${datos.notaAcumulada}%`;
        }

        
        $('#notaAcumuladaEstudiante').html(text2);


        NotasEstudiantes(datos.id, datos.idGrupo);

    });


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

    $('#ClaseDatatables tbody').on('click', '#notasACT', function () {
        const datos = datatables2.row($(this).parents()).data();

        $("#notasNAV").removeClass("d-none");
        $("#clasesNAV").removeClass("active");
        $("#tab-notas").addClass("active");
        $("#tab-clases").removeClass("active");
        $("#notasNAV").addClass("active");

        let text = `Clase: ${datos.titulo}`;
        $('#infoClase2').text(text);

        $('#idClase1').text(datos.id);

        let text2 = `<strong>Ponderacion: ${datos.ponderacion}%</strong>`;
        $('#tituloNotas').html(text2);
        $('#ponderacionClases').val(datos.ponderacion);

        listarNotasEstudiantes(datos.id);

    });

    $("#clasesNAV").on("click", function (event) {

        $("#infoNAV").removeClass("active");
        $("#notasNAV").removeClass("active");

        $("#infoNAV").addClass("d-none");
        $("#notasNAV").addClass("d-none");

        $("#tab-info").removeClass("active");
        $("#tab-notas").removeClass("active");


        $("#clasesNAV").addClass("active");
        $("#tab-clases").addClass("active");

        datatables3.ajax.reload();


    })

    $("#MatriculaNAV").on("click", function (event) {

        $("#EstudianteNAV").removeClass("active");

        $("#EstudianteNAV").addClass("d-none");

        $("#tab-Estudiante").removeClass("active");


        $("#MatriculaNAV").addClass("active");
        $("#tab-Matricula").addClass("active");




    })


    function listarNotasEstudiantes(idClase) {


        if (datatables3) {
            datatables3.destroy();
        }

        datatables3 = $('#notasdatatble').DataTable({

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
                url: '/AppwebMVC/Grupos/Nota',
                data: {
                    cargarNotas: 'cargarNotas',
                    idClase: idClase
                }
            },
            columns: [
                { data: 'cedula' },
                { data: 'nombres' },
                {
                    data: null,
                    render: function (data, type, row, meta) {

                        let id = data.cedula;
                        let nota = data.calificacion;
                        let notaInput = `<input type="number" class="form-control" value="${nota}" id="notas${id}"step="0.01" min="0" aria-describedby="msj_notaACT${id}">
                        <div id="msj_notaACT${id}" class="invalid-feedback"></div>`;


                        let guardar = permisos.registrar ? `<button type="button" id="editarNotas${id}" title="Guardar Cambios" class="btn btn-primary d-none"><i class="fa-solid fa-floppy-disk"></i></button>` : '';

                        div = ` <div class="buttons" style="width: 100%;" id="guia">
                        ${notaInput}
                        ${guardar}
                        </div>
                           `
                        return div;
                    }
                },

            ],
        });

    };

    $('#notasdatatble').on('keyup', '#guia', function (e) {
        const datos = datatables3.row($(this).parents()).data();
        let id = datos.cedula;
        $(`#notasdatatble`).on(`keyup`, `#notas${id}`, function (e) {

            const input = $('#notasdatatble tbody').find(`#notas${id}`);
            const boton = $('#notasdatatble tbody').find(`#editarNotas${id}`);


            if (input.val() != datos.calificacion) {
                boton.removeClass("d-none");

                boton.on('click', function (e) {
                    let nota = '';
                    let validacion = false;
                    let msj = $('#notasdatatble tbody').find(`#msj_notaACT${id}`)
                    const ponderacion = $('#ponderacionClases').val();

                    if (/^\s*$/.test(input.val())) {
                        nota = '0';
                        input.removeClass("is-invalid");
                        msj.text('');
                        validacion = true;

                        NotaACT(datos.idUsuario, datos.nombres, nota, boton);
                    } else {

                        if (input.val() <= ponderacion) {

                            if (/^([0-9])+(\.[0-9]{2})$/.test(input.val())) {
                                input.removeClass("is-invalid");
                                msj.text('');
                                validacion = true;
                                nota = input.val();
                                NotaACT(datos.idUsuario, datos.nombres, nota, boton);
                            } else if (/^[0-9]+$/.test(input.val())) {

                                input.removeClass("is-invalid");
                                msj.text('');
                                validacion = true;
                                nota = input.val();
                                NotaACT(datos.idUsuario, datos.nombres, nota, boton);
                            } else {
                                input.addClass("is-invalid");
                                msj.text('formato incorrecto');
                                validacion = false;

                            }
                        } else {

                            input.addClass("is-invalid");
                            msj.text('Excede la ponderación');
                            validacion = false;

                        }
                    }


                });

            } else {
                boton.addClass("d-none");
                input.removeClass("is-invalid");
            }

        });
    });


    function NotaACT(idUsuario, nombres, nota, boton) {

        Swal.fire({
            title: '¿Estas Seguro?',
            text: 'Se asignara ' + nota + '% de la calificacion a el estudiante: ' + nombres,
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
                    url: "/AppwebMVC/Grupos/Nota",
                    data: {

                        actualizarNota: 'actualizarNota',
                        idClase: $("#idClase1").text(),
                        idEstudiante: idUsuario,
                        calificacion: nota,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);

                        boton.addClass('d-none')

                        Swal.fire({
                            icon: 'success',
                            title: 'Los cambios fueron guardados',
                            text: data.msj,

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
    }




    ///////////// APARTADO DE CONTENIDO ///////////////

    let idClase
    let idContenido
    let contenido

    $('#ClaseDatatables tbody').on('click', '#contenidoBoton', function () {
        const datos = datatables2.row($(this).parents()).data();

        idClase = datos.id
        cargarContenido(idClase);
    });

    // Función para crear un nuevo editor Quill y añadirlo al contenedor
    function agregar_quillEditor(datos) {
        // Crea un nuevo elemento <div> para el editor
        var editorElement = document.createElement('div');
        // Asigna un ID único al elemento del editor
        var editorId = 'editor-contenido';
        editorElement.setAttribute('id', editorId);
        // Agrega el editor al contenedor sobrescribiendo el contenido existente
        document.getElementById('contenido').innerHTML = '';
        document.getElementById('contenido').appendChild(editorElement)

        // Inicializa el editor Quill en el nuevo elemento
        editorQuill = new Quill('#' + editorId, {
            theme: 'snow'
        });

        if (datos != '') {
            editorQuill.clipboard.dangerouslyPasteHTML(0, datos)
        }
    }


    function cargarContenido(idClase) {
        $.ajax({
            type: "GET",
            url: "/AppwebMVC/Grupos/Clase",
            data: {
                cargarContenido: 'cargarContenido',
                idClase: idClase
            },
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);
    
                document.getElementById('contenido').innerHTML = '';

                if (data.length !== 0) {
                    document.getElementById('contenido').innerHTML = data['contenido'];

                    idContenido = data.id
                    contenido = data.contenido
                }else{
                    let texto = '<p>No existe contenido actualmente</p>'
                    document.getElementById('contenido').innerHTML = texto;
                    contenido = texto
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
                        });
                    } else {
                        const respuesta = JSON.stringify(jsonResponse, null, 2);
                        Swal.fire({
                            background: 'red',
                            color: '#fff',
                            title: respuesta,
                            showConfirmButton: true,
                        });
                    }
                } else {
                    alert('Error desconocido: ' + textStatus);
                }
            }
        });
    }


    $('#agregarContenido').on('click', function () {
        agregar_quillEditor('')
    })

    $('#guardarContenido').on('click', function () {
        console.log(editorQuill.root.innerHTML)
    })

    $('#editarContenido').on('click', function () {
        agregar_quillEditor(contenido)
    })

    $('#cancelarContenido').on('click', function () {
        document.getElementById('contenido').innerHTML = '';
        document.getElementById('contenido').innerHTML = contenido;
    })

});
