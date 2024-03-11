$(document).ready(function () {

    let choices1;
    let choices2;
    let choices3;
    let datatables1;
    let datatables2;
    let datatables3;
    let choices4;
    let choices5;
    let choices6;
    let datatables4;
    let nombreV3;
    let datatables5;
    let nombreV4;

    const dataTable = $('#eidDatatables').DataTable({
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
            url: '/AppwebMVC/Escuela/Index',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre' },
            { data: 'modulos' },
            {
                data: null,
                render: function (data, type, row, meta) {


                    let botonEditar = permisos.actualizar ? `<a role="button" id="editar" data-bs-toggle="modal" title="Editar Nombre" data-bs-target="#modal_editar" ><i class="fa-solid fa-pen" ></i></a>` : '';

                    let botonRequisitos = permisos.actualizar ? `<a role="button" id="requisitos" data-bs-toggle="modal" title="Editar Requisitos" data-bs-target="#modal_requisitos" ><i class="fa-solid fa-key" ></i></a>` : '';

                    let botonModulo = permisos.registrar ? `<a role="button" id="Modulo" data-bs-toggle="modal" title="Gestionar Modulos" data-bs-target="#modal_modulos" ><i class="fa-solid fa-users"></i></a>` : '';

                    let botonEliminar = permisos.eliminar ? `<a role="button"  id=eliminar title="Eliminar"><i class="fa-solid fa-trash" ></i></a>` : '';

                    let div = `
          <div class="acciones">
                   
                    ${botonEditar}
                    ${botonRequisitos}
                    ${botonModulo}
                    ${botonEliminar}
          </div>
          `
                    return div;
                }
            },
        ],
    });


    $("#eidDatatables tbody").on('click', '#Modulo', function (event) {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idEid4').textContent = datos.id;
        
        nombreV3 = false;

        let text = `${datos.codigo} ${datos.nombre}`;
        document.getElementById('cartaEid').textContent = text;
        listarModulosV(datos.id);
    });

    $("#eidDatatables tbody").on('click', '#requisitos', function (event) {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idEid2').textContent = datos.id;

        listarEidV(datos.id);
        listarRolRA(datos.id);
        listarRolRV(datos.id);

        const array = ['eid', 'rolR', 'rolA'];

        for (const valor of array) {
            ListarControlEidSV(datos.id, valor);
        }


    });

    $("#aja").on("click", function (event) {


        $("#aja1").removeClass("active");
        $("#tab-eidlist").removeClass("active");


        $("#tab-niveles").addClass("active");
        $("#aja2").removeClass("d-none");
        $("#aja2").addClass("active");



    });

    $("#aja1").on("click", function (event) {


        $("#aja2").removeClass("active");
        $("#tab-niveles").removeClass("active");

        $("#aja2").addClass("d-none");
        $("#tab-eidlist").addClass("active");
        $("#aja1").addClass("active");



    });

    $('#search').keyup(function () {
        dataTable.search($(this).val()).draw();
    });



    $('#registrar').on('click', function () {

        ListarEid();
        ListarRoles();

    });

    $('#eidDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idEid3').textContent = datos.id;
        document.getElementById('nombre2').value = datos.nombre;

    });


    $('#eidDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a esta EID otra vez!",
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
                    url: "/AppwebMVC/Escuela/Index",
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
                            text: 'EID borrada con Exito',
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
                                    title: 'DENEGADO',
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




    function ListarEid() {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/Escuela/Index",
            data: {

                listarEid: 'listarEid',

            },
            success: function (response) {

                console.log(response);
                let data = JSON.parse(response);

                let selector = document.getElementById('idEid');
                // Destruir la instancia existente si la hay
                if (choices1) {
                    choices1.destroy();
                }

                // Limpiar el select
                selector.innerHTML = "";

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.nombre}`;
                    selector.appendChild(option);

                });

                choices1 = new Choices(selector, {
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

    function ListarRoles() {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/Escuela/Index",
            data: {

                listarRoles: 'listarRoles',

            },
            success: function (response) {

                console.log(response);
                let data = JSON.parse(response);

                let selector = document.getElementById('idRolR');
                let selector2 = document.getElementById('idRolA');
                // Destruir la instancia existente si la hay
                if (choices2) {
                    choices2.destroy();
                }

                if (choices3) {
                    choices3.destroy();
                }

                // Limpiar el select
                selector.innerHTML = "";
                selector2.innerHTML = "";

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.nombre}`;
                    selector.appendChild(option);

                });

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.nombre}`;
                    selector2.appendChild(option);

                });

                choices2 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices3 = new Choices(selector2, {
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







    ////////////////////////////////////// REGISTRAR DATOS EID ///////////////////////////////////////


    let nombreV = false;


    const exp = {
        nombre: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
        selectores: /^[0-9]\d*$/
    }


    // Validación del nombre de la escuela
    $("#nombre").on("keyup", function (event) {
        const nombre = document.getElementById("nombre").value;

        if (exp.nombre.test(nombre)) {
            nombreV = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");
            document.getElementById('msj_nombre').textContent = '';

        } else {
            nombreV = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");
            document.getElementById('msj_nombre').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';

        }
    });


    const validacionselect = {
        eid: false,
        rolR: false,
        rolA: false,
    };


    let selectedEid;
    $("#idEid").on("change", function (event) {
        const eid = document.querySelector("#idEid");
        selectedEid = Array.from(eid.selectedOptions).map(option => option.value);
        if (selectedEid.length === 0) {

            validacionselect.eid = false;
        } else {

            validacionselect.eid = true;
        }
    });

    let selectedRolR;
    $("#idRolR").on("change", function (event) {
        const rolR = document.querySelector("#idRolR");
        selectedRolR = Array.from(rolR.selectedOptions).map(option => option.value);
        if (selectedRolR.length === 0) {

            validacionselect.rolR = false;
        } else {

            validacionselect.rolR = true;
        }
    });

    let selectedRolA;
    $("#idRolA").on("change", function (event) {
        const rolA = document.querySelector("#idRolA");
        selectedRolA = Array.from(rolA.selectedOptions).map(option => option.value);
        if (selectedRolA.length === 0) {

            validacionselect.rolA = false;
        } else {

            validacionselect.rolA = true;
        }
    });

    $("#formulario").submit(function (event) {

        event.preventDefault();


        if (nombreV === true) {
            if (Object.values(validacionselect).every(status => status === true)) {
                registrarEid();
            } else {
                if (Object.values(validacionselect).every(status => status === false)) {

                    Swal.fire({
                        title: '¿Estas seguro de resgistrar la EID sin requisitos?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        confirmButtonColor: '#007bff',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            registrarEid();
                        }
                    });
                } else {

                    Swal.fire({
                        title: '¿Estas seguro de resgistrar la EID con estos requisitos?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        confirmButtonColor: '#007bff',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            registrarEid();
                        }
                    });
                }
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Formulario invalido. Verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });


    function registrarEid() {
        $.ajax({
            type: "POST",
            url: "/AppwebMVC/Escuela/Index",
            data: {

                registrar: 'registrar',
                nombre: document.getElementById("nombre").value,
                selectedEid: selectedEid,
                selectedRolR: selectedRolR,
                selectedRolA: selectedRolA
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
                });

                $('#agregar').modal('hide');
                document.getElementById('formulario').reset();

                for (const key in validacionselect) {
                    validacionselect[key] = false;
                }
                nombreV = false;
                $("#nombre").removeClass("is-valid");
                ListarEid();
                ListarRoles();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseText) {
                    let jsonResponse = JSON.parse(jqXHR.responseText);

                    if (jsonResponse.msj) {
                        Swal.fire({
                            icon: 'error',
                            title: 'DENEGADO',
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


    }

    ////////////////////////////////////// EDITAR DATOS EID ///////////////////////////////////////


    let nombreV2 = true;



    // Validación del nombre de la escuela
    $("#nombre2").on("keyup", function (event) {
        const nombre2 = document.getElementById("nombre2").value;
        if (exp.nombre.test(nombre2)) {
            nombreV2 = true;
            $("#nombre2").removeClass("is-invalid");
            $("#nombre2").addClass("is-valid");
            document.getElementById('msj_nombre2').textContent = '';

        } else {
            nombreV2 = false;
            $("#nombre2").removeClass("is-valid");
            $("#nombre2").addClass("is-invalid");
            document.getElementById('msj_nombre2').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';

        }
    });



    $("#formulario2").submit(function (event) {
        if (nombreV2 === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Escuela/Index",
                data: {

                    editar: 'editar',
                    id: document.getElementById("idEid3").textContent,
                    nombre: document.getElementById("nombre2").value
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
                    });


                    $("#nombre2").removeClass("is-valid");

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseText) {
                        let jsonResponse = JSON.parse(jqXHR.responseText);

                        if (jsonResponse.msj) {
                            Swal.fire({
                                icon: 'error',
                                title: 'DENEGADO',
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


    function listarEidV(id) {

        if (datatables1) {
            datatables1.destroy();
        }

        datatables1 = $('#eidVDatatables').DataTable({
            language: {
                url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
            },
            searching: false,
            responsive: true,
            scrollCollapse: true,
            scrollY: '100px',
            info: false,
            ordering: false,
            paging: false,
            ajax: {
                method: "POST",
                url: '/AppwebMVC/Escuela/Index',
                data: {
                    cargarControlEid: 'cargarControlEid',
                    id: id,
                    tipo: 'eid'
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'nombre' },

                {
                    defaultContent: `

          <div class="d-flex justify-content-end gap-1" >
            <button type="button" id="eliminarEidV" class="btn btn-danger" title="Eliminar"><i class="fa-solid fa-trash" ></i></button>
            </div>
            `}
            ],
        })



    }

    function listarRolRV(id) {

        if (datatables2) {
            datatables2.destroy();
        }

        datatables2 = $('#rolesRDatatables').DataTable({
            language: {
                url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
            },
            searching: false,
            responsive: true,
            scrollCollapse: true,
            scrollY: '100px',
            info: false,
            ordering: false,
            paging: false,
            ajax: {
                method: "POST",
                url: '/AppwebMVC/Escuela/Index',
                data: {
                    cargarControlEid: 'cargarControlEid',
                    id: id,
                    tipo: 'rolR'
                }
            },
            columns: [
                { data: 'nombre' },

                {
                    defaultContent: `

          <div class="d-flex justify-content-end gap-1" >
            <button type="button" id="eliminarRolR" class="btn btn-danger" title="Eliminar"><i class="fa-solid fa-trash" ></i></button>
            </div>
            `}
            ],
        })



    }

    function listarRolRA(id) {

        if (datatables3) {
            datatables3.destroy();
        }

        datatables3 = $('#rolesADatatables').DataTable({
            language: {
                url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
            },
            searching: false,
            responsive: true,
            scrollCollapse: true,
            scrollY: '100px',
            info: false,
            ordering: false,
            paging: false,
            ajax: {
                method: "POST",
                url: '/AppwebMVC/Escuela/Index',
                data: {
                    cargarControlEid: 'cargarControlEid',
                    id: id,
                    tipo: 'rolA'
                }
            },
            columns: [
                { data: 'nombre' },

                {
                    defaultContent: `

          <div class="d-flex justify-content-end gap-1" >
            <button type="button" id="eliminarRolA" class="btn btn-danger" title="Eliminar"><i class="fa-solid fa-trash" ></i></button>
            </div>
            `}
            ],
        })



    }

    $("#eidVDatatables tbody").on('click', '#eliminarEidV', function (event) {
        const datos = datatables1.row($(this).parents()).data();

        const idEid = document.getElementById("idEid2").textContent;
        const tipo = 'eid';

        eliminarControlEid(idEid, tipo, datos.id);


    });


    $("#rolesRDatatables tbody").on('click', '#eliminarRolR', function (event) {
        const datos = datatables2.row($(this).parents()).data();

        const idEid = document.getElementById("idEid2").textContent;
        const tipo = 'rolR';


        eliminarControlEid(idEid, tipo, datos.id);


    });

    $("#rolesADatatables tbody").on('click', '#eliminarRolA', function (event) {
        const datos = datatables3.row($(this).parents()).data();

        const idEid = document.getElementById("idEid2").textContent;
        const tipo = 'rolA';

        eliminarControlEid(idEid, tipo, datos.id);


    });

    function eliminarControlEid(idEid, tipo, idRequisito) {

        Swal.fire({
            title: '¿Estas Seguro de eliminar este requisito?',

            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            confirmButtonColor: '#007bff',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "POST",
                    url: "/AppwebMVC/Escuela/Index",
                    data: {

                        eliminarControlEid: 'eliminarControlEid',
                        idRequisito: idRequisito,
                        idEid: idEid,
                        tipo: tipo
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables3.ajax.reload();
                        datatables2.ajax.reload();
                        datatables1.ajax.reload();

                        const array2 = ['eid', 'rolR', 'rolA'];

                        for (const valor of array2) {
                            ListarControlEidSV(idEid, valor);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'EID borrada con Exito',
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
                                    title: 'DENEGADO',
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


    function ListarControlEidSV(id, tipo) {

        $.ajax({
            type: "POST",
            url: "/AppwebMVC/Escuela/Index",
            data: {
                listadoSV: 'listadoSV',
                id: id,
                tipo: tipo
            },
            success: function (response) {

                let data = JSON.parse(response);

                if (tipo == 'eid') {
                    if (choices4) {
                        choices4.destroy();
                    }
                    let selectorEid = document.getElementById('eid_sin_agregar');

                    selectorEid.innerHTML = '';

                    data.forEach(item => {

                        const option = document.createElement('option');
                        option.value = item.id;
                        option.text = `${item.codigo} ${item.nombreeid}`;
                        selectorEid.appendChild(option);

                    });

                    choices4 = new Choices(selectorEid, {
                        allowHTML: true,
                        searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                        removeItemButton: true,  // Habilita la posibilidad de remover items
                        placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                    });
                }

                if (tipo == 'rolR') {

                    let selectorRolR = document.getElementById('rolesR_sin_agregar');

                    if (choices5) {
                        choices5.destroy();
                    }

                    selectorRolR.innerHTML = '';

                    data.forEach(item => {

                        const option = document.createElement('option');
                        option.value = item.id;
                        option.text = `${item.nombrerolR}`;
                        selectorRolR.appendChild(option);
                    });
                    choices5 = new Choices(selectorRolR, {
                        allowHTML: true,
                        searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                        removeItemButton: true,  // Habilita la posibilidad de remover items
                        placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                    });
                }

                if (tipo == 'rolA') {
                    let selectorRolA = document.getElementById('rolesA_sin_agregar');

                    if (choices6) {
                        choices6.destroy();
                    }

                    selectorRolA.innerHTML = '';


                    data.forEach(item => {

                        const option = document.createElement('option');
                        option.value = item.id;
                        option.text = `${item.nombrerolA}`;
                        selectorRolA.appendChild(option);

                    });

                    choices6 = new Choices(selectorRolA, {
                        allowHTML: true,
                        searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                        removeItemButton: true,  // Habilita la posibilidad de remover items
                        placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                    });

                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }


    $("#agregareids").on("click", function (event) {

        const id = document.getElementById("idEid2").textContent;
        const array = $("#eid_sin_agregar").val();
        const tipo = 'eid';

        agregarRSV(id, array, tipo);

    });

    $("#agregarrolesR").on("click", function (event) {

        const id = document.getElementById("idEid2").textContent;
        const array = $("#rolesR_sin_agregar").val();
        const tipo = 'rolR';

        agregarRSV(id, array, tipo);

    });

    $("#agregarrolesA").on("click", function (event) {

        const id = document.getElementById("idEid2").textContent;
        const array = $("#rolesA_sin_agregar").val();
        const tipo = 'rolA';

        agregarRSV(id, array, tipo);

    });


    function agregarRSV(id, array, tipo) {


        $.ajax({
            type: "POST",
            url: "/AppwebMVC/Escuela/Index",
            data: {

                registrarControlEid: 'registrarControlEid',
                id: id,
                array: array,
                tipo: tipo
            },
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);
                datatables3.ajax.reload();
                datatables2.ajax.reload();
                datatables1.ajax.reload();

                const array2 = ['eid', 'rolR', 'rolA'];

                for (const valor of array2) {
                    ListarControlEidSV(id, valor);
                }

                Swal.fire({
                    icon: 'success',
                    title: '¡Regsitrados!',
                    text: 'Se registraron con exito los requisitos',
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
                            title: 'DENEGADO',
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

    $('#cerrarRegistrar').on('click', function () {

        document.getElementById('formulario').reset();

        $("#nombre").removeClass("is-valid");
        $("#nombre").removeClass("is-invalid");

        $('#modal_registrar').modal('hide');

    });

    $('#cerrarEditar').on('click', function () {

        document.getElementById('formulario').reset();

        $("#nombre2").removeClass("is-valid");
        $("#nombre2").removeClass("is-invalid");

        $('#modal_editar').modal('hide');

    });

    function listarModulosV(idEid) {

        if (datatables4) {
            datatables4.destroy();
        }

        datatables4 = $('#moduloDatatables').DataTable({
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
                url: '/AppwebMVC/Escuela/Moduloeid',
                data: {
                    cargar_data: 'cargar_data',
                    idEid: idEid
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'nombre' },
                { data: 'niveles' },
                {
                    data: null,
                    render: function (data, type, row, meta) {


                        let botonEditar = permisos.actualizar ? `<a role="button" id="editarModulo" title="Editar Nombre" ><i class="fa-solid fa-pen" ></i></a>` : '';

                        let botonNiveles = permisos.registrar ? `<a role="button" id="niveles" title="Gestionar Niveles"><i class="fa-solid fa-users"></i></a>` : '';

                        let botonEliminar = permisos.eliminar ? `<a role="button"  id="eliminarModulo" title="Eliminar"><i class="fa-solid fa-trash" ></i></a>` : '';

                        let div = `
              <div class="acciones">

                        ${botonEditar}
                        ${botonNiveles}
                        ${botonEliminar}
              </div>
              `
                        return div;
                    }
                },
            ],
        });

    }

    $("#moduloDatatables tbody").on('click', '#niveles', function (event) {
        const datos = datatables4.row($(this).parents()).data();

        document.getElementById('idmodulo1').textContent = datos.id;
        
        nombreV4 = false;

        let text = `${datos.codigo} ${datos.nombre}`;
        document.getElementById('cartaNiveles').textContent = text;
        listarNivelesV(datos.id);
    });

    $('#searchModulo').keyup(function () {
        datatables4.search($(this).val()).draw();
    });
    
    $('#moduloDatatables tbody').on('click', '#eliminarModulo', function () {
        const datos = datatables4.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a este modulo otra vez",
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
                    url: "/AppwebMVC/Escuela/Moduloeid",
                    data: {
                        eliminar: 'eliminar',
                        id: datos.id,
                        idEid : document.getElementById("idEid4").textContent
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables4.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'Modulo borrado con Exito',
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
                                    title: 'DENEGADO',
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

    $("#moduloDatatables tbody").on('click', '#editarModulo', function (event) {
        const datos = datatables4.row($(this).parents()).data();

        document.getElementById('idmodulo').textContent = datos.id;
        document.getElementById('nombre3').value = datos.nombre;

        $("#registrarmodulo").addClass("d-none");
        $("#editarmodulo").removeClass("d-none");
        $("#cancelar").removeClass("d-none");

        nombreV3 = true;


    });

    $("#moduloDatatables tbody").on('click', '#niveles', function (event) {


        $("#aja1").removeClass("active");
        $("#tab-eidlist").removeClass("active");


        $("#tab-niveles").addClass("active");
        $("#aja2").removeClass("d-none");
        $("#aja2").addClass("active");


    });

    $("#cancelar").on("click", function (event) {


        $("#nombre3").removeClass("is-valid");
        nombreV3 = false;
        document.getElementById('formulario3').reset();
        $("#editarmodulo").addClass("d-none");
        $("#registrarmodulo").removeClass("d-none");
        $("#cancelar").addClass("d-none");


    });


    // Validación del nombre de el modulo
    $("#nombre3").on("keyup", function (event) {
        const nombre3 = document.getElementById("nombre3").value;
        if (exp.nombre.test(nombre3)) {
            nombreV3 = true;
            $("#nombre3").removeClass("is-invalid");
            $("#nombre3").addClass("is-valid");
            document.getElementById('msj_nombre3').textContent = '';

        } else {
            nombreV3 = false;
            $("#nombre3").removeClass("is-valid");
            $("#nombre3").addClass("is-invalid");
            document.getElementById('msj_nombre3').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';

        }
    });

    $("#registrarmodulo").on("click", function (event) {


        if (nombreV3 === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Escuela/Moduloeid",
                data: {

                    registrar: 'registrar',
                    idEid: document.getElementById("idEid4").textContent,
                    nombre: document.getElementById("nombre3").value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    


                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    });


                    $("#nombre3").removeClass("is-valid");
                    nombreV3 = false;
                    document.getElementById('formulario3').reset();
                    datatables4.ajax.reload();

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseText) {
                        let jsonResponse = JSON.parse(jqXHR.responseText);

                        if (jsonResponse.msj) {
                            Swal.fire({
                                icon: 'error',
                                title: 'DENEGADO',
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

    $("#editarmodulo").on("click", function (event) {


        if (nombreV3 === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Escuela/Moduloeid",
                data: {

                    editar: 'editar',
                    idModulo: document.getElementById("idmodulo").textContent,
                    nombre: document.getElementById("nombre3").value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    datatables4.ajax.reload();


                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    });


                    $("#nombre3").removeClass("is-valid");
                    nombreV3 = false;
                    document.getElementById('formulario3').reset();
                    $("#editarmodulo").addClass("d-none");
                    $("#registrarmodulo").removeClass("d-none");
                    $("#cancelar").addClass("d-none");


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseText) {
                        let jsonResponse = JSON.parse(jqXHR.responseText);

                        if (jsonResponse.msj) {
                            Swal.fire({
                                icon: 'error',
                                title: 'DENEGADO',
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

    function listarNivelesV(idModulo) {

        if (datatables5) {
            datatables5.destroy();
        }

        datatables5 = $('#nivelesDatatables').DataTable({
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
                url: '/AppwebMVC/Escuela/Nivel',
                data: {
                    cargar_data: 'cargar_data',
                    idModulo: idModulo
                }
            },
            columns: [
                { data: 'codigo' },
                { data: 'nombre' },
                {
                    data: null,
                    render: function (data, type, row, meta) {


                        let botonEditar = permisos.actualizar ? `<a role="button" id="editarNivel" title="Editar Nombre" ><i class="fa-solid fa-pen" ></i></a>` : '';

                        let botonEliminar = permisos.eliminar ? `<a role="button"  id="eliminarNivel" title="Eliminar"><i class="fa-solid fa-trash" ></i></a>` : '';

                        let div = `
              <div class="acciones">

                        ${botonEditar}                
                        ${botonEliminar}
              </div>
              `
                        return div;
                    }
                },
            ],
        });

    }



    $('#searchNivel').keyup(function () {
        datatables5.search($(this).val()).draw();
    });
    
    $('#nivelesDatatables tbody').on('click', '#eliminarNivel', function () {
        const datos = datatables5.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a este Nivel otra vez",
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
                    url: "/AppwebMVC/Escuela/Nivel",
                    data: {
                        eliminar: 'eliminar',
                        id: datos.id,
                        idModulo : document.getElementById("idmodulo1").textContent
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        datatables5.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'Nivel borrada con Exito',
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
                                    title: 'DENEGADO',
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

    $("#nivelesDatatables tbody").on('click', '#editarNivel', function (event) {
        const datos = datatables5.row($(this).parents()).data();

        document.getElementById('idnivel').textContent = datos.id;
        document.getElementById('nombre4').value = datos.nombre;

        $("#registrarnivel").addClass("d-none");
        $("#editarnivel").removeClass("d-none");
        $("#cancelar2").removeClass("d-none");

        nombreV4 = true;


    });

   

    $("#cancelar2").on("click", function (event) {


        $("#nombre4").removeClass("is-valid");
        nombreV4 = false;
        document.getElementById('formulario4').reset();
        $("#editarnivel").addClass("d-none");
        $("#registrarnivel").removeClass("d-none");
        $("#cancelar2").addClass("d-none");


    });


    // Validación del nombre de el modulo
    $("#nombre4").on("keyup", function (event) {
        const nombre4 = document.getElementById("nombre4").value;
        if (exp.nombre.test(nombre4)) {
            nombreV4 = true;
            $("#nombre4").removeClass("is-invalid");
            $("#nombre4").addClass("is-valid");
            document.getElementById('msj_nombre4').textContent = '';

        } else {
            nombreV4 = false;
            $("#nombre4").removeClass("is-valid");
            $("#nombre4").addClass("is-invalid");
            document.getElementById('msj_nombre4').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';

        }
    });

    $("#registrarnivel").on("click", function (event) {


        if (nombreV4 === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Escuela/Nivel",
                data: {

                    registrar: 'registrar',
                    idModulo: document.getElementById("idmodulo1").textContent,
                    nombre: document.getElementById("nombre4").value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    


                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    });


                    $("#nombre4").removeClass("is-valid");
                    nombreV4 = false;
                    document.getElementById('formulario4').reset();
                    datatables5.ajax.reload();

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseText) {
                        let jsonResponse = JSON.parse(jqXHR.responseText);

                        if (jsonResponse.msj) {
                            Swal.fire({
                                icon: 'error',
                                title: 'DENEGADO',
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

    $("#editarnivel").on("click", function (event) {


        if (nombreV4 === true) {

            $.ajax({
                type: "POST",
                url: "/AppwebMVC/Escuela/Nivel",
                data: {

                    editar: 'editar',
                    idNivel: document.getElementById("idnivel").textContent,
                    nombre: document.getElementById("nombre4").value
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    datatables5.ajax.reload();


                    Swal.fire({
                        icon: 'success',
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    });


                    $("#nombre4").removeClass("is-valid");
                    nombreV4 = false;
                    document.getElementById('formulario4').reset();
                    $("#editarnivel").addClass("d-none");
                    $("#registrarnivel").removeClass("d-none");
                    $("#cancelar2").addClass("d-none");


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseText) {
                        let jsonResponse = JSON.parse(jqXHR.responseText);

                        if (jsonResponse.msj) {
                            Swal.fire({
                                icon: 'error',
                                title: 'DENEGADO',
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


    $("#cerrarmodulos").on("click", function (event) {


        $("#tab-niveles").removeClass("active");
        $("#tab-eidlist").addClass("active");


        
        $("#aja2").addClass("d-none");
        $("#aja2").removeClass("active")
        $("#aja1").addClass("active");



    });

});

