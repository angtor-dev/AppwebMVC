$(document).ready(function () {

    let choices1;
    let choices2;
    let choices3;
    let choices4;
    let choices5;
    let choices6;


    const dataTable = $('#celulaDatatables').DataTable({
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
            url: '/AppwebMVC/CelulaFamiliar/Index',
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
                data: null,
        render: function (data, type, row, meta) {

            let botonInfo = `<a role="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" title="Ver detalles" ><i class="fa-solid fa-circle-info" ></i></a>`;

            let botonEditar = permisos.actualizar ? `<a role="button" id="editar" data-bs-toggle="modal" title="Actualizar" data-bs-target="#modal_editarInfo" ><i class="fa-solid fa-pen" ></i></a>` : '';
  
            let botonEliminar = permisos.eliminar ? `<a role="button"  id=eliminar title="Eliminar"><i class="fa-solid fa-trash" ></i></a>` : '';
            let botonReunion = `<a role="button" id="reunion" data-bs-toggle="modal" data-bs-target="#modal_registroreunion" title="Réunion"><i class="fa-solid fa-users"></i></a>`;
          

          let div = `
          <div class="acciones">
                    ${botonInfo}
                    ${botonEditar}
                    ${botonEliminar}
                    ${botonReunion}
          </div>
          `
          return div;
        }},
        ],
    });

    $('#search').keyup(function () {
        dataTable.search($(this).val()).draw();
    });

   
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
                    url: "/AppwebMVC/CelulaFamiliar/Index",
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



    function Listar_LideresEditar(idLider, idCoLider) {

        $.ajax({
            type: "GET",
            url: "/AppwebMVC/CelulaFamiliar/Index",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider2');

                let selector2 = document.getElementById('idCoLider2');

                if (permisos.rolLiderCelula){

                    data.forEach(item => {
    
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                        selector.appendChild(option);
    
                    })
                   
                    }else{
    
                        const option = document.createElement('option');
                        option.value = permisos.id;
                        option.text = `${permisos.cedula} ${permisos.nombre}`;
                        selector.appendChild(option);
    
                    }

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
            url: "/AppwebMVC/CelulaFamiliar/Index",
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
            url: "/AppwebMVC/CelulaFamiliar/Index",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider');
                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Seleccione el lider';
                placeholderOption.disabled = true;
                selector.appendChild(placeholderOption);

                let selector2 = document.getElementById('idCoLider');

                const placeholderOption2 = document.createElement('option');
                placeholderOption2.value = '';
                placeholderOption2.text = 'Seleccione el CoLider';
                placeholderOption2.disabled = true;
                selector2.appendChild(placeholderOption2);

                if (permisos.rolLiderCelula){

                    data.forEach(item => {
    
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                        selector.appendChild(option);
    
                    })
                   
                    }else{
    
                        const option = document.createElement('option');
                        option.value = permisos.id;
                        option.text = `${permisos.cedula} ${permisos.nombre}`;
                        selector.appendChild(option);
    
                    }

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

                choices4.setChoiceByValue('')
                choices5.setChoiceByValue('')

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
            url: "/AppwebMVC/CelulaFamiliar/Index",
            data: {

                listaTerritorio: 'listaTerritorio',

            },
            success: function (response) {

                let data = JSON.parse(response);


                let selector = document.getElementById('idTerritorio');

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.text = 'Selecciona el territorio';
                placeholderOption.disabled = true;
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

                choices6.setChoiceByValue('')

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

        nombre: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,50}$/, 
        idLider: /^[1-9]\d*$/, 
        idCoLider: /^[1-9]\d*$/, 
        idTerritorio: /^[1-9]\d*$/, 
    };

    const validationStatus = {
        nombre: false,
        idLider: false,
        idCoLider: false,
        idTerritorio: false
    };



      // Validación del nombre de la Celula
  $("#nombre").on("keyup", function (event) {
      const idTerritorio = document.getElementById("idTerritorio").value;
      const nombre = document.getElementById("nombre").value;  
    validar_nombre_registrar(idTerritorio, nombre);
  });

  function validar_nombre_registrar(idTerritorio, nombre){

    if(nombre !== ''){
    $.ajax({
      type: "POST",
      url: "/AppwebMVC/CelulaFamiliar/Index",
      data: {
        coincidencias: 'coincidencias',
        nombre: nombre,
        idTerritorio: idTerritorio
      },
      success: function (response) {
  
        let data = JSON.parse(response);
  
        if (data != true) {
          validationStatus.nombre = true;
          $("#nombre").removeClass("is-invalid");
          $("#nombre").addClass("is-valid");
          document.getElementById('msj_nombre').textContent = '';
          if (regexObj.nombre.test(nombre)) {
            validationStatus.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");
            document.getElementById('msj_nombre').textContent = '';
  
          } else {
            validationStatus.nombre = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");
            document.getElementById('msj_nombre').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';
  
          }
        } else {
          validationStatus.nombre = false;
          $("#nombre").removeClass("is-valid");
          $("#nombre").addClass("is-invalid");
          document.getElementById('msj_nombre').textContent = 'Ya existe una Celula Familiar con este nombre en este Territorio';
  
        }
  
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
      }
    })

  }}


    // Validacion de idLider 

    
    $("#idLider").on("change", function (event) {
        const idLider = document.getElementById("idLider");
       const idCoLider = document.getElementById("idCoLider");
       const div = document.getElementById("msj_idLider");
       
           if (regexObj.idLider.test(idLider.value)) {
               validationStatus.idLider = true;
               div.classList.add("d-none");
               div.innerText = "";
     
               if(idLider.value === idCoLider.value){
                   validationStatus.idLider = false;
                   div.classList.remove("d-none");
                   div.innerText = "Lider y CoLider no pueden ser la misma persona";
                   } 
           } else {
               validationStatus.idLider = false;
               div.classList.remove("d-none");
             div.innerText = "este campo es obligatorio";
           }
       })
   
       // Validacion  idCoLider
    $("#idCoLider").on("change", function (event) {
        const idLider = document.getElementById("idLider");
       const idCoLider = document.getElementById("idCoLider");
       const div = document.getElementById("msj_idCoLider");
       
           if (regexObj.idCoLider.test(idCoLider.value)) {
               validationStatus.idCoLider = true;
               div.classList.add("d-none");
               div.innerText = "";
     
               if(idLider.value === idCoLider.value){
                   validationStatus.idCoLider = false;
                   div.classList.remove("d-none");
                   div.innerText = "Lider y CoLider no pueden ser la misma persona";
                   }
           } else {
               validationStatus.idCoLider = false;
               div.classList.remove("d-none");
             div.innerText = "este campo es obligatorio";
           }
       })

  


    // Validar idTerritorio
    
    $("#idTerritorio").on("change", function (event) {
        const idTerritorio = document.getElementById("idTerritorio").value;
        const nombre = document.getElementById("nombre").value;
        const div = document.getElementById("msj_idTerritorio");
        if (!regexObj.idTerritorio.test(idTerritorio)) {
            div.classList.remove("d-none");
            div.innerText = "Este campo es obligatorio";
     
            validationStatus.idTerritorio = false;
        } else {
            div.classList.add("d-none");
            div.innerText = "";

            validationStatus.idTerritorio = true;
        }
        validar_nombre_registrar(idTerritorio, nombre);
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
                url: "/AppwebMVC/CelulaFamiliar/Index",
                data: {

                    registrar: 'registrar',
                    nombre: document.getElementById("nombre").value,
                    idLider: document.getElementById("idLider").value,
                    idCoLider: document.getElementById("idCoLider").value,
                    idTerritorio: document.getElementById("idTerritorio").value
                },
                success: function (response) {
                    console.log(response);
                    dataTable.ajax.reload();
                    $('#modal_registrar').modal('hide');
                    document.getElementById('formulario').reset();

                    let data = JSON.parse(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    });

                    nombre.value = '';
                    $("#nombre").removeClass("is-valid");
                    
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










    ////////////////////////////// ACTUALIZAR DATOS DE LA CELULA ///////////////////////////////


    const validationStatus2 = {
        nombre: true,
        idLider: true,
        idCoLider: true,
        idTerritorio: true
    };

    $("#nombre2").on("keyup", function (event) {
        const idTerritorio = document.getElementById("idTerritorio2").value;
        const nombre = document.getElementById("nombre2").value;  
      validar_nombre_editar(idTerritorio, nombre);
    });


    function validar_nombre_editar(idTerritorio, nombre){

        if(nombre !== ''){
        $.ajax({
          type: "POST",
          url: "/AppwebMVC/CelulaFamiliar/Index",
          data: {
            coincidencias: 'coincidencias',
            nombre: nombre,
            idTerritorio: idTerritorio,
            id: document.getElementById('idCelulaFamiliar').textContent
          },
          success: function (response) {
      
            let data = JSON.parse(response);
      
            if (data != true) {
              validationStatus2.nombre = true;
              $("#nombre2").removeClass("is-invalid");
              $("#nombre2").addClass("is-valid");
              document.getElementById('msj_nombre2').textContent = '';
              if (regexObj.nombre.test(nombre)) {
                validationStatus2.nombre = true;
                $("#nombre2").removeClass("is-invalid");
                $("#nombre2").addClass("is-valid");
                document.getElementById('msj_nombre2').textContent = '';
      
              } else {
                validationStatus2.nombre = false;
                $("#nombre2").removeClass("is-valid");
                $("#nombre2").addClass("is-invalid");
                document.getElementById('msj_nombre2').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';
      
              }
            } else {
              validationStatus2.nombre = false;
              $("#nombre2").removeClass("is-valid");
              $("#nombre2").addClass("is-invalid");
              document.getElementById('msj_nombre2').textContent = 'Ya existe una Celula Familiar con este nombre en este Territorio';
      
            }
      
          },
          error: function (jqXHR, textStatus, errorThrown) {
            // Aquí puedes manejar errores, por ejemplo:
            console.error("Error al enviar:", textStatus, errorThrown);
          }
        })
    
      }}
    
    
        // Validacion de idLider 
    
        
        $("#idLider2").on("change", function (event) {
            const idLider = document.getElementById("idLider2");
           const idCoLider = document.getElementById("idCoLider2");
           const div = document.getElementById("msj_idLider2");
           
               if (regexObj.idLider.test(idLider.value)) {
                   validationStatus2.idLider = true;
                   div.classList.add("d-none");
                   div.innerText = "";
         
                   if(idLider.value === idCoLider.value){
                       validationStatus2.idLider = false;
                       div.classList.remove("d-none");
                       div.innerText = "Lider y CoLider no pueden ser la misma persona";
                       } 
               } else {
                   validationStatus2.idLider = false;
                   div.classList.remove("d-none");
                 div.innerText = "este campo es obligatorio";
               }
           })
       
           // Validacion  idCoLider
        $("#idCoLider2").on("change", function (event) {
            const idLider = document.getElementById("idLider2");
           const idCoLider = document.getElementById("idCoLider2");
           const div = document.getElementById("msj_idCoLider2");
           
               if (regexObj.idCoLider.test(idCoLider.value)) {
                   validationStatus2.idCoLider = true;
                   div.classList.add("d-none");
                   div.innerText = "";
         
                   if(idLider.value === idCoLider.value){
                       validationStatus2.idCoLider = false;
                       div.classList.remove("d-none");
                       div.innerText = "Lider y CoLider no pueden ser la misma persona";
                       }
               } else {
                   validationStatus2.idCoLider = false;
                   div.classList.remove("d-none");
                 div.innerText = "este campo es obligatorio";
               }
           })
    
      
    
    
        // Validar idTerritorio
        
        $("#idTerritorio2").on("change", function (event) {
            const idTerritorio = document.getElementById("idTerritorio2").value;
            const nombre = document.getElementById("nombre2").value;
            const div = document.getElementById("msj_idTerritorio2");
            if (!regexObj.idTerritorio.test(idTerritorio)) {
                div.classList.remove("d-none");
                div.innerText = "Este campo es obligatorio";
         
                validationStatus2.idTerritorio = false;
            } else {
                div.classList.add("d-none");
                div.innerText = "";
    
                validationStatus2.idTerritorio = true;
            }
            validar_nombre_editar(idTerritorio, nombre);
        })
    


    const form2 = document.getElementById("formulario2");
    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        const id2 = document.getElementById('idCelulaFamiliar').textContent;

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "/AppwebMVC/CelulaFamiliar/Index",
                data: {

                    editar: 'editar',
                    id2: id2,
                    nombre2: document.getElementById("nombre2").value,
                    idLider2: document.getElementById("idLider2").value,
                    idCoLider2: document.getElementById("idCoLider2").value,
                    idTerritorio2: document.getElementById("idTerritorio2").value
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
                title: 'Verifique bien el formulario antes de enviar',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });








    //////////////////////////// REGISTRO DE REUNION ////////////////////////////////   


    const ex_re = {

        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        fecha: /^.+$/,
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        asistencia: /^[0-9]\d*$/,
        info: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };

    const validationStatus3 = {

        fecha: false,
        tematica: false,
        semana: false,
        generosidad: false,
        infantil: false,
        juvenil: false,
        adulto: false,
        actividad: false,
        observaciones: false
    };


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
            div.innerText = "Este campo es obligatorio, y su formato correcto es 00.00";
     
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
        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus3).every(status => status === true)) {
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "/AppwebMVC/CelulaFamiliar/Index",
                data: {

                    registroreunion: 'registroreunion',
                    idCelula: dato.idCelula.text(),
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
                        title: 'Se registro correctamente la Reunion',
                        showConfirmButton: false,
                        timer: 2000,
                    });


                      for (const key in dato) {
                        const input = dato[key];
                        input.removeClass("is-valid");
                      }

                      for (const key in validationStatus3) {
                        validationStatus3[key] = false;
                      }

                    form3.reset()

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
                title: 'Verifique bien el formulario antes de ser enviado',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });
$('#cerrarRegistrar').on('click', function () {
    
    document.getElementById('formulario').reset();

    $("#nombre").removeClass("is-valid");
    $("#nombre").removeClass("is-invalid");
    $("#msj_idLider").addClass("d-none");
    $("#msj_idCoLider").addClass("d-none");
    $("#msj_idTerritorio").addClass("d-none");


    $('#modal_registrar').modal('hide');
    
  });

  $('#cerrarEditar').on('click', function () {
    
    $("#nombre2").removeClass("is-valid");
    $("#nombre2").removeClass("is-invalid");
    $("#msj_idLider2").addClass("d-none");
    $("#msj_idCoLider2").addClass("d-none");
    $("#msj_idTerritorio2").addClass("d-none");

    $('#modal_editarInfo').modal('hide');

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

    $('#modal_registroreunion').modal('hide');

  });



  
});





