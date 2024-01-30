$(document).ready(function () {

  let choices;
  let choices2;


  const dataTable = $('#sedeDatatables').DataTable({
   
    info: false,
        lengthChange: false,
        pageLength: 15,
        dom: 'ltipB',
        searching: true,
        language: {
            url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
        },
        // Muestra paginacion solo si hay mas de una pagina
        drawCallback: function (settings) {
            var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
            pagination.toggle(this.api().page.info().pages > 1);
        },
    ajax: {
      method: "GET",
      url: '/AppwebMVC/Sedes/Listar',
      data: { cargar_data: 'cargar_data' }
    },
    columns: [
      { data: 'codigo' },
      { data: 'nombre' },
      { data: 'direccion' },
      {
        data: null,
        render: function (data, type, row, meta) {

          let botonInfo = `<a role="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" title="Ver detalles" ><i class="fa-solid fa-circle-info" ></i></a>`;

          let botonEditar = permisos.actualizar ? `<a role="button" id="editar" data-bs-toggle="modal" title="Actualizar" data-bs-target="#modal_editarInfo" ><i class="fa-solid fa-pen" ></i></a>` : '';

          let botonEliminar = permisos.eliminar ? `<a role="button"  id=eliminar title="Eliminar"><i class="fa-solid fa-trash" ></i></a>` : '';

          let div = `
          <div class="acciones">
                    ${botonInfo}
                    ${botonEditar}
                    ${botonEliminar}
          </div>
          `
          return div;
        }
      },
    ],

    
  })

 
    $('#search').keyup(function () {
        dataTable.search($(this).val()).draw();
    });



  $('#sedeDatatables tbody').on('click', '#ver_info', function () {
    const datos = dataTable.row($(this).parents()).data();

    let text = `${datos.nombrePastor} ${datos.apellido}`;
    
    document.getElementById('inf_codigo').textContent = datos.codigo;
    document.getElementById('inf_nombre').textContent = datos.nombre;
    document.getElementById('inf_idPastor').textContent = text;
    document.getElementById('inf_direccion').textContent = datos.direccion;
    document.getElementById('inf_estado').textContent = datos.estado;
  })



  $('#sedeDatatables tbody').on('click', '#editar', function () {
    const datos = dataTable.row($(this).parents()).data();
 
    document.getElementById('idSede').textContent = datos.id;
    document.getElementById('nombre2').value = datos.nombre;
    document.getElementById('direccion2').value = datos.direccion;
    document.getElementById('estado2').value = datos.estadoCodigo;

    Listar_PastoresEditar(datos.idPastor);


  })

  $('#registrar').on('click', function () {

    Listar_PastoresRegistrar();

  });

  

  $('#sedeDatatables tbody').on('click', '#eliminar', function () {
    const datos = dataTable.row($(this).parents()).data();

    Swal.fire({
      title: '¿Estas Seguro?',
      text: "No podras acceder a esta sede otra vez!",
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
          url: "/AppwebMVC/Sedes/Listar",
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
              text: 'La sede ha sido borrada',
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



  function Listar_PastoresRegistrar() {

    $.ajax({
      type: "GET",
      url: "/AppwebMVC/Sedes/Listar",
      data: {

        listaPastores: 'listaPastores',

      },
      success: function (response) {

        let data = JSON.parse(response);

        let selector = document.getElementById('idPastor');
        selector.innerHTML = '';

        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.text = 'Seleccionar pastor';
        placeholderOption.disabled = true;
        selector.appendChild(placeholderOption);

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
          selector.appendChild(option);

        });

        if (choices) {
          choices.destroy();
        }


        choices = new Choices(selector, {
          allowHTML: true,
          searchEnabled: true,  // Habilita la funcionalidad de búsqueda
          removeItemButton: true,  // Habilita la posibilidad de remover items
          placeholderValue: 'Selecciona una opción',
        });

        choices.setChoiceByValue('');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
        alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
      }
    })
  }




  function Listar_PastoresEditar(idPastor) {

    $.ajax({
      type: "GET",
      url: "/AppwebMVC/Sedes/Listar",
      data: {

        listaPastores: 'listaPastores',


      },
      success: function (response) {

        let data = JSON.parse(response);

        let selector = document.getElementById('idPastor2');
        // Crear y agregar la opción tipo "placeholder"

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
          selector.appendChild(option);

        });


        if (choices2) {
          choices2.destroy();
        }

        choices2 = new Choices(selector, {
          allowHTML: true,
          searchEnabled: true,  // Habilita la funcionalidad de búsqueda
          removeItemButton: true,  // Habilita la posibilidad de remover items

        });

        choices2.setChoiceByValue(idPastor.toString());

      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
        alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
      }

    })
  }




  ////////////////////////////////////// REGISTRAR DATOS DE SEDE ///////////////////////////////////////


  let validaciones = {
    idPastor: false,
    nombre: false,
    direccion: false,
    estado: false
  };

  const expresiones = {
    nombre: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,50}$/,
    id: /^\d{1,9}$/,
    texto: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/,
    estado: ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
      "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TACH", "TRU", "VAR", "YAR", "ZUL"]
  }

  // Validación del ID del pastor
  
  $("#idPastor").on("change", function (event) {
   const idPastor = document.getElementById('idPastor');
    if (expresiones.id.test(idPastor.value)) {
      validaciones.idPastor = true;
      $("#idPastor").removeClass("is-invalid");
      $("#msj_idPastor").addClass("d-none");
    } else {
      validaciones.idPastor = false;
      $("#idPastor").addClass("is-invalid");
      $("#msj_idPastor").removeClass("d-none");
    }
  })

  // Validación del nombre de la sede
  $("#nombre").on("keyup", function (event) {
    const nombre = document.getElementById("nombre").value
    $.ajax({
      type: "POST",
      url: "/AppwebMVC/Sedes/Listar",
      data: {
        coincidencias: 'coincidencias',
        nombre: nombre
      },
      success: function (response) {
  
        let data = JSON.parse(response);
  
        if (data != true) {
          validaciones.nombre = true;
          $("#nombre").removeClass("is-invalid");
          $("#nombre").addClass("is-valid");
          document.getElementById('msj_nombre').textContent = '';
          if (expresiones.nombre.test(nombre)) {
            validaciones.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");
            document.getElementById('msj_nombre').textContent = '';
  
          } else {
            validaciones.nombre = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");
            document.getElementById('msj_nombre').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';
  
          }
        } else {
          validaciones.nombre = false;
          $("#nombre").removeClass("is-valid");
          $("#nombre").addClass("is-invalid");
          document.getElementById('msj_nombre').textContent = 'Ya existe una Sede con este nombre';
  
        }
  
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
      }
    })
  
  
  });
  
  


  // Validación de la dirección
  
  $("#direccion").on("keyup", function (event) {
    const direccion = document.getElementById('direccion');
    if (expresiones.texto.test(direccion.value)) {
      validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
            document.getElementById('msj_direccion').textContent = '';
    } else {
      validaciones.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
            document.getElementById('msj_direccion').textContent = 'Este campo debe poseer mas de 5 caracteres';
    }
  })


  // Validación del estado
  
  $("#estado").on("change", function (event) {
    const estado = document.getElementById('estado');
   if (expresiones.estado.includes(estado.value)) {
      validaciones.estado = true;
   
      $("#msj_estado").addClass("d-none");
    } else {
      validaciones.estado = false;
      
      $("#msj_estado").removeClass("d-none");
    }
  })



  $("#formulario").submit(function (event) {
    // Previene el comportamiento predeterminado del formulario
    event.preventDefault();

    // Verificar si todas las validaciones son correctas
    if (Object.values(validaciones).every(status => status === true)) {
      // Si todas las validaciones son correctas, realiza la petición AJAX
      // ... (tu código AJAX va aquí)
      $.ajax({
        type: "POST",
        url: "/AppwebMVC/Sedes/Listar",
        data: {

          registrar: 'registrar',
          idPastor: document.getElementById("idPastor").value,
          nombre: document.getElementById("nombre").value,
          direccion: document.getElementById("direccion").value,
          estado: $('#estado').val()
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

        for (const key in validaciones) {
          validaciones[key] = false;
        }
        $("#nombre").removeClass("is-valid");
        $("#direccion").removeClass("is-valid");
        Listar_PastoresRegistrar();
          
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





  ////////////////////////////////////// ACTUALIZAR DATOS DE SEDE ///////////////////////////////////////

  let validaciones2 = {
    idPastor2: true,
    nombre2: true,
    direccion2: true,
    estado2: true
  };

  const expresiones2 = {
    nombre2: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,50}$/,
    id2: /^\d{1,9}$/,
    texto2: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/,
    estado2: ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
      "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TACH", "TRU", "VAR", "YAR", "ZUL"]
  }

  // Validación del ID del pastor
  
  $("#idPastor2").on("change", function (event) {
    const idPastor2 = document.getElementById('idPastor2');
    if (expresiones2.id2.test(idPastor2.value)) {
      validaciones2.idPastor2 = true;
      $("#idPastor2").removeClass("is-invalid");
      $("#msj_idPastor2").addClass("d-none");
    } else {
      validaciones2.idPastor2 = false;
      $("#idPastor2").addClass("is-invalid");
      $("#msj_idPastor2").removeClass("d-none");
    }
  })

 
   // Validación del nombre de la sede
   $("#nombre2").on("keyup", function (event) {
    const nombre2 = document.getElementById("nombre2").value
    $.ajax({
      type: "POST",
      url: "/AppwebMVC/Sedes/Listar",
      data: {
        coincidencias: 'coincidencias',
        nombre: nombre2,
        id: document.getElementById('idSede').textContent

      },
      success: function (response) {
  
        let data = JSON.parse(response);
  
        if (data != true) {
          validaciones2.nombre2 = true;
          $("#nombre2").removeClass("is-invalid");
          $("#nombre2").addClass("is-valid");
          document.getElementById('msj_nombre2').textContent = '';
          if (expresiones2.nombre2.test(nombre2)) {
            validaciones2.nombre2 = true;
            $("#nombre2").removeClass("is-invalid");
            $("#nombre2").addClass("is-valid");
            document.getElementById('msj_nombre2').textContent = '';
  
          } else {
            validaciones2.nombre2 = false;
            $("#nombre2").removeClass("is-valid");
            $("#nombre2").addClass("is-invalid");
            document.getElementById('msj_nombre2').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';
  
          }
        } else {
          validaciones2.nombre2 = false;
          $("#nombre2").removeClass("is-valid");
          $("#nombre2").addClass("is-invalid");
          document.getElementById('msj_nombre2').textContent = 'Ya existe una Sede con este nombre';
  
        }
  
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
      }
    })
  
  
  });
  
  


  // Validación de la dirección
  
  $("#direccion2").on("keyup", function (event) {
    const direccion2 = document.getElementById('direccion2');
    if (expresiones2.texto2.test(direccion2.value)) {
      validaciones2.direccion2 = true;
            $("#direccion2").removeClass("is-invalid");
            $("#direccion2").addClass("is-valid");
            document.getElementById('msj_direccion2').textContent = '';
    } else {
      validaciones2.direccion2 = false;
            $("#direccion2").removeClass("is-valid");
            $("#direccion2").addClass("is-invalid");
            document.getElementById('msj_direccion2').textContent = 'Este campo debe poseer mas de 5 caracteres';
    }
  })



  // Validación del estado
  
  $("#estado2").on("keyup", function (event) {
    const estado2 = document.getElementById('estado2');
    if (expresiones2.estado2.includes(estado2.value)) {
      validaciones2.estado2 = true;
  
      $("#msj_estado2").addClass("d-none");
    } else {
      validaciones2.estado2 = false;
      
      $("#msj_estado2").removeClass("d-none");
    }
  })


  $("#formulario2").submit(function (event) {
    // Previene el comportamiento predeterminado del formulario
    event.preventDefault();

    

    // Verificar si todas las validaciones son correctas
    if (Object.values(validaciones2).every(val => val === true)) {
      // Si todas las validaciones son correctas, realiza la petición AJAX
      // ... (tu código AJAX va aquí)
      $.ajax({
        type: "POST",
        url: "/AppwebMVC/Sedes/Listar",
        data: {

          editar: 'editar',
          id: document.getElementById('idSede').textContent,
          idPastor: document.getElementById("idPastor2").value,
          nombre: document.getElementById("nombre2").value,
          direccion: document.getElementById("direccion2").value,
          estado: document.getElementById("estado2").value
        },
        success: function (response) {
          console.log(response);

          dataTable.ajax.reload();

          Swal.fire({
            icon: 'success',
            title: 'Actualizado correctamente',
            showConfirmButton: false,
            timer: 2000,
          });
    
        $("#nombre2").removeClass("is-valid");
        $("#direccion2").removeClass("is-valid");
 

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

    
    $("#msj_estado").addClass("d-none");
    $("#msj_idPastor").addClass("d-none");
    $("#direccion").removeClass("is-valid");
    $("#direccion").removeClass("is-invalid");
    $("#nombre").removeClass("is-valid");
    $("#nombre").removeClass("is-invalid");
    $('#modal_registrar').modal('hide');

  });

  $('#cerrarEditar').on('click', function () {
    
    $("#msj_estado2").addClass("d-none");
    $("#msj_idPastor2").addClass("d-none");
    $("#direccion2").removeClass("is-valid");
    $("#direccion2").removeClass("is-invalid");
    $("#nombre2").removeClass("is-valid");
    $("#nombre2").removeClass("is-invalid");
    $('#modal_editarInfo').modal('hide');

  });

});


