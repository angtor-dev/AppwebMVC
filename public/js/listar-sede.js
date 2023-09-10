$(document).ready(function () {

  let choices;

  const dataTable = $('#sedeDatatables').DataTable({
    responsive: true,
    ajax: {
      method: "GET",
      url: 'http://localhost/AppwebMVC/Sedes/Listar',
      data: { cargar_data: 'cargar_data' }
    },
    columns: [
      { data: 'codigo' },
      { data: 'nombre' },
      { data: 'direccion' },
      {
        defaultContent: `
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-light">Info</button>
            <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            `}

    ],
  })

  $('#sedeDatatables tbody').on('click', '#ver_info', function () {
    const datos = dataTable.row($(this).parents()).data();
    document.getElementById('inf_codigo').textContent = datos.codigo;
    document.getElementById('inf_nombre').textContent = datos.nombre;
    document.getElementById('inf_idPastor').textContent = datos.idPastor;
    document.getElementById('inf_direccion').textContent = datos.direccion;
    document.getElementById('inf_estado').textContent = datos.estado;
  })



  $('#sedeDatatables tbody').on('click', '#editar', function () {
    const datos = dataTable.row($(this).parents()).data();

    document.getElementById('idSede').textContent = datos.id;
    document.getElementById('nombre').value = datos.nombre;
    document.getElementById('direccion').value = datos.direccion;
    document.getElementById('estado').value = datos.estadoCodigo;

    Listar_Pastores(datos.idPastor);
  })

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
          url: "http://localhost/AppwebMVC/Sedes/Listar",
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



  function Listar_Pastores(idPastor) {

    $.ajax({
      type: "GET",
      url: "http://localhost/AppwebMVC/Sedes/Listar",
      data: {

        listaPastores: 'listaPastores',

      },
      success: function (response) {

        let data = JSON.parse(response);

        let selector = document.getElementById('idPastor');
        // Limpiar el selector antes de agregar nuevas opciones
        selector.innerHTML = '';

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
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
        });

        choices.setChoiceByValue(idPastor.toString());

      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
        alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
      }
    })
  }








  ////////////////////////////////////// ACTUALIZAR DATOS DE SEDE ///////////////////////////////////////

  let validaciones = {
    idPastor: false,
    nombre: false,
    direccion: false,
    estado: false
  };


  $("#formulario").submit(function (event) {
    // Previene el comportamiento predeterminado del formulario
    event.preventDefault();

    let id = document.getElementById('idSede').textContent;
    // Validaciones
    // Validación de la cédula del pastor
    let idPastor = $("#idPastor").val();
    if (/^\d{1,8}$/.test(idPastor)) {
      validaciones.idPastor = true;
      $("#idPastor").removeClass("is-invalid");
      $("#msj_idPastor").addClass("d-none");
    } else {
      validaciones.idPastor = false;
      $("#idPastor").addClass("is-invalid");
      $("#msj_idPastor").removeClass("d-none");
    }

    // Validación del nombre de la sede
    let nombre = $("#nombre").val();
    if (/^[a-zA-Z0-9\s.,]{1,50}$/.test(nombre)) {
      validaciones.nombre = true;
      $("#nombre").removeClass("is-invalid");
      $("#msj_nombre").addClass("d-none");
    } else {
      validaciones.nombre = false;
      $("#nombre").addClass("is-invalid");
      $("#msj_nombre").removeClass("d-none");
    }

    // Validación de la dirección
    let direccion = $("#direccion").val();
    if (/^[a-zA-Z0-9\s]{1,100}$/.test(direccion)) {
      validaciones.direccion = true;
      $("#direccion").removeClass("is-invalid");
      $("#msj_direccion").addClass("d-none");
    } else {
      validaciones.direccion = false;
      $("#direccion").addClass("is-invalid");
      $("#msj_direccion").removeClass("d-none");
    }

    // Validación del estado
    let estado = $("#estado").val();
    let estadosPermitidos = ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
      "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TÁCH", "TRU", "VAR", "YAR", "ZUL"];
    if (estadosPermitidos.includes(estado)) {
      validaciones.estado = true;
      $("#estado").removeClass("is-invalid");
      $("#msj_estado").addClass("d-none");
    } else {
      validaciones.estado = false;
      $("#estado").addClass("is-invalid");
      $("#msj_estado").removeClass("d-none");
    }

    // Verificar si todas las validaciones son correctas
    if (Object.values(validaciones).every(val => val)) {
      // Si todas las validaciones son correctas, realiza la petición AJAX
      // ... (tu código AJAX va aquí)
      $.ajax({
        type: "POST",
        url: "http://localhost/AppwebMVC/Sedes/Listar",
        data: {

          editar: 'editar',
          id: id,
          idPastor: idPastor,
          nombre: nombre,
          direccion: direccion,
          estado: estado
        },
        success: function (response) {
          console.log(response);
          dataTable.ajax.reload();

          Swal.fire({
            icon: 'success',
            title: 'Actualizado correctamente',
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


});


