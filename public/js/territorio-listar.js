$(document).ready(function () {

  let choices1;
  let choices2;
  let choices3;
  let choices4;

  const dataTable = $('#territorioDatatables').DataTable({
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
      url: '/AppwebMVC/Territorios/Listar',
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
  });

  $('#search').keyup(function () {
    dataTable.search($(this).val()).draw();
  });

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
    Listar_SedesEditar(datos.idSede);

  })


  $('#registrar').on('click', function () {

    Listar_SedesRegistrar();
    Listar_LideresRegistrar();


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
          url: "/AppwebMVC/Territorios/Listar",
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




  function Listar_LideresRegistrar() {

    $.ajax({
      type: "GET",
      url: "/AppwebMVC/Territorios/Listar",
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
      url: "/AppwebMVC/Territorios/Listar",
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
      url: "/AppwebMVC/Territorios/Listar",
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
      url: "/AppwebMVC/Territorios/Listar",
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







  ////////////////////////////////////// REGISTRAR DATOS DE TERRITORIO ///////////////////////////////////////


  let validaciones = {
    idSede: false,
    nombre: false,
    idLider: false,
    detalles: false
  };

  const expresiones = {
    id: /^\d{1,9}$/, // Números enteros mayores a 0
    nombre: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
    detalles: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
  }

  // Validación del ID de la sede

  $("#idSede").on("change", function (event) {
    const idSede = document.getElementById('idSede').value;
    const nombre = document.getElementById("nombre").value;
    if (expresiones.id.test(idSede)) {
      validaciones.idSede = true;
      $("#msj_idSede").addClass("d-none");
    } else {
      validaciones.idSede = false;
      $("#msj_idSede").removeClass("d-none");
    }
    validar_nombre_registrar(idSede, nombre);
  })

  // Validación del nombre del Territorio
  $("#nombre").on("keyup", function (event) {
    const idSede = document.getElementById("idSede").value;
    const nombre = document.getElementById("nombre").value;
    validar_nombre_registrar(idSede, nombre);
  });

  function validar_nombre_registrar(idSede, nombre) {

    if (nombre !== '') {
      $.ajax({
        type: "POST",
        url: "/AppwebMVC/Territorios/Listar",
        data: {
          coincidencias: 'coincidencias',
          nombre: nombre,
          idSede: idSede
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
            document.getElementById('msj_nombre').textContent = 'Ya existe un Territorio con este nombre registrado en esta Sede';

          }

        },
        error: function (jqXHR, textStatus, errorThrown) {
          // Aquí puedes manejar errores, por ejemplo:
          console.error("Error al enviar:", textStatus, errorThrown);
        }
      })

    }
  }


  // Validación del ID del Lider

  $("#idLider").on("change", function (event) {
    const idLider = document.getElementById('idLider');
    if (expresiones.id.test(idLider.value)) {
      validaciones.idLider = true;
      $("#msj_idLider").addClass("d-none");
    } else {
      validaciones.idLider = false;
      $("#msj_idLider").removeClass("d-none");
    }
  })

  // Validación de la detalles

  $("#detalles").on("keyup", function (event) {
    const detalles = document.getElementById('detalles');
    if (expresiones.detalles.test(detalles.value)) {
      validaciones.detalles = true;
      $("#detalles").removeClass("is-invalid");
      $("#detalles").addClass("is-valid");
      document.getElementById('msj_detalles').textContent = '';
    } else {
      validaciones.detalles = false;
      $("#detalles").removeClass("is-valid");
      $("#detalles").addClass("is-invalid");
      document.getElementById('msj_detalles').textContent = 'Este campo debe poseer mas de 5 caracteres';
    }
  });





  $("#formulario").submit(function (event) {

    event.preventDefault();


    if (Object.values(validaciones).every(status => status === true)) {

      $.ajax({
        type: "POST",
        url: "/AppwebMVC/Territorios/Listar",
        data: {

          registrar: 'registrar',
          idSede: document.getElementById("idSede").value,
          nombre: document.getElementById("nombre").value,
          idLider: document.getElementById("idLider").value,
          detalles: document.getElementById("detalles").value
        },
        success: function (response) {
          console.log(response);
          let data = JSON.parse(response);
          dataTable.ajax.reload();

          $("#msj_idSede").addClass("d-none");
          $("#msj_idLider").addClass("d-none");
          $("#detalles").removeClass("is-valid");
          $("#detalles").removeClass("is-invalid");
          $("#nombre").removeClass("is-valid");
          $("#nombre").removeClass("is-invalid");
          $('#modal_registrar').modal('hide');

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
          $("#detalles").removeClass("is-valid");
          Listar_LideresRegistrar();
          Listar_SedesRegistrar();

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





  ////////////////////////////////////// ACTUALIZAR DATOS DE TETORRIO ///////////////////////////////////////

  let validaciones2 = {
    idSede: true,
    nombre: true,
    idLider: true,
    detalles: true
  };


  // Validación del ID de la Sede
  $("#idSede2").on("change", function (event) {
    const idSede = document.getElementById('idSede2').value;
    const nombre = document.getElementById("nombre2").value;
    if (expresiones.id.test(idSede)) {
      validaciones2.idSede = true;
      $("#msj_idSede2").addClass("d-none");
    } else {
      validaciones2.idSede = false;
      $("#msj_idSede2").removeClass("d-none");
    }
    validar_nombre_editar(idSede, nombre);
  })


  $("#nombre2").on("keyup", function (event) {
    const idSede = document.getElementById("idSede2").value;
    const nombre = document.getElementById("nombre2").value;
    validar_nombre_editar(idSede, nombre);
  });

  // Validación nombre de Territorio
  function validar_nombre_editar(idSede, nombre) {
    if (nombre !== '') {
      $.ajax({
        type: "POST",
        url: "/AppwebMVC/Territorios/Listar",
        data: {
          coincidencias: 'coincidencias',
          nombre: nombre,
          id: document.getElementById("idTerritorio").textContent,
          idSede: idSede
        },
        success: function (response) {

          let data = JSON.parse(response);

          if (data != true) {
            validaciones2.nombre = true;
            $("#nombre2").removeClass("is-invalid");
            $("#nombre2").addClass("is-valid");
            document.getElementById('msj_nombre2').textContent = '';
            if (expresiones.nombre.test(nombre)) {
              validaciones2.nombre = true;
              $("#nombre2").removeClass("is-invalid");
              $("#nombre2").addClass("is-valid");
              document.getElementById('msj_nombre2').textContent = '';

            } else {
              validaciones2.nombre = false;
              $("#nombre2").removeClass("is-valid");
              $("#nombre2").addClass("is-invalid");
              document.getElementById('msj_nombre2').textContent = 'El nombre es obligatorio y debe poseer mas de 5 caracteres';

            }
          } else {
            validaciones2.nombre = false;
            $("#nombre2").removeClass("is-valid");
            $("#nombre2").addClass("is-invalid");
            document.getElementById('msj_nombre2').textContent = 'Ya existe un Territorio con este nombre registrado en esta Sede';

          }

        },
        error: function (jqXHR, textStatus, errorThrown) {
          // Aquí puedes manejar errores, por ejemplo:
          console.error("Error al enviar:", textStatus, errorThrown);
        }
      })
    }
  }





  // Validación del ID del Lider

  $("#idLider2").on("change", function (event) {
    const idLider = document.getElementById('idLider2');
    if (expresiones.id.test(idLider.value)) {
      validaciones2.idLider = true;
      $("#msj_idLider2").addClass("d-none");
    } else {
      validaciones2.idLider = false;
      $("#msj_idLider2").removeClass("d-none");
    }
  });

  // Validación de la detalles

  $("#detalles2").on("keyup", function (event) {
    const detalles = document.getElementById('detalles2');
    if (expresiones.detalles.test(detalles.value)) {
      validaciones2.detalles = true;
      $("#detalles2").removeClass("is-invalid");
      $("#detalles2").addClass("is-valid");
      document.getElementById('msj_detalles2').textContent = '';
    } else {
      validaciones2.detalles = false;
      $("#detalles2").removeClass("is-valid");
      $("#detalles2").addClass("is-invalid");
      document.getElementById('msj_detalles2').textContent = 'Este campo debe poseer mas de 5 caracteres';
    }
  });


  $("#formulario2").submit(function (event) {

    event.preventDefault();

    if (Object.values(validaciones2).every(val => val === true)) {

      $.ajax({
        type: "POST",
        url: "/AppwebMVC/Territorios/Listar",
        data: {

          editar: 'editar',
          id: document.getElementById("idTerritorio").textContent,
          idSede: document.getElementById("idSede2").value,
          nombre: document.getElementById("nombre2").value,
          idLider: document.getElementById("idLider2").value,
          detalles: document.getElementById("detalles2").value
        },
        success: function (response) {
          console.log(response);

          dataTable.ajax.reload();

          $("#msj_idSede2").addClass("d-none");
          $("#msj_idLider2").addClass("d-none");
          $("#detalles2").removeClass("is-valid");
          $("#detalles2").removeClass("is-invalid");
          $("#nombre2").removeClass("is-valid");
          $("#nombre2").removeClass("is-invalid");
          $('#modal_registrar').modal('hide');
          $('#modal_editarInfo').modal('hide');

          Swal.fire({
            icon: 'success',
            title: 'Actualizado correctamente',
            showConfirmButton: false,
            timer: 2000,
          });

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


  $('#cerrarRegistrar').on('click', function () {

    document.getElementById('formulario').reset();


    $("#msj_idSede").addClass("d-none");
    $("#msj_idLider").addClass("d-none");
    $("#detalles").removeClass("is-valid");
    $("#detalles").removeClass("is-invalid");
    $("#nombre").removeClass("is-valid");
    $("#nombre").removeClass("is-invalid");
    $('#modal_registrar').modal('hide');

  });

  $('#cerrarEditar').on('click', function () {


    $("#msj_idSede2").addClass("d-none");
    $("#msj_idLider2").addClass("d-none");
    $("#detalles2").removeClass("is-valid");
    $("#detalles2").removeClass("is-invalid");
    $("#nombre2").removeClass("is-valid");
    $("#nombre2").removeClass("is-invalid");
    $('#modal_registrar').modal('hide');
    $('#modal_editarInfo').modal('hide');

  });


});

