$(document).ready(function () {

  let choices;
  let choices2;

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
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-secondary">Info</button>
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
    document.getElementById('nombre2').value = datos.nombre;
    document.getElementById('direccion2').value = datos.direccion;
    document.getElementById('estado2').value = datos.estadoCodigo;

    Listar_PastoresEditar(datos.idPastor);


  })

  $('#registrar').on('click', function () {

    Listar_PastoresRegistrar();

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



  function Listar_PastoresRegistrar() {

    $.ajax({
      type: "GET",
      url: "http://localhost/AppwebMVC/Sedes/Listar",
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
      url: "http://localhost/AppwebMVC/Sedes/Listar",
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
    nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/,
    id: /^\d{1,9}$/,
    texto: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/,
    estado: ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
      "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TÁCH", "TRU", "VAR", "YAR", "ZUL"]
  }

  // Validación del ID del pastor
  const idPastor = document.getElementById('idPastor');
  idPastor.addEventListener('change', (e) => {
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
  const nombre = document.getElementById('nombre');
  nombre.addEventListener('keyup', (e) => {
    if (expresiones.nombre.test(nombre.value)) {
      validaciones.nombre = true;
      $("#nombre").removeClass("is-invalid");
      $("#msj_nombre").addClass("d-none");
    } else {
      validaciones.nombre = false;
      $("#nombre").addClass("is-invalid");
      $("#msj_nombre").removeClass("d-none");
    }
  })


  // Validación de la dirección
  const direccion = document.getElementById('direccion');
  direccion.addEventListener('keyup', (e) => {
    if (expresiones.texto.test(direccion.value)) {
      validaciones.direccion = true;
      $("#direccion").removeClass("is-invalid");
      $("#msj_direccion").addClass("d-none");
    } else {
      validaciones.direccion = false;
      $("#direccion").addClass("is-invalid");
      $("#msj_direccion").removeClass("d-none");
    }
  })


  // Validación del estado
  const estado = document.getElementById('estado');
  estado.addEventListener('change', (e) => {
    if (expresiones.estado.includes(estado.value)) {
      validaciones.estado = true;
      $("#estado").removeClass("is-invalid");
      $("#msj_estado").addClass("d-none");
    } else {
      validaciones.estado = false;
      $("#estado").addClass("is-invalid");
      $("#msj_estado").removeClass("d-none");
    }
  })



  $("#formulario").submit(function (event) {
    // Previene el comportamiento predeterminado del formulario
    event.preventDefault();

    // Verificar si todas las validaciones son correctas
    if (Object.values(validaciones).every(val => val)) {
      // Si todas las validaciones son correctas, realiza la petición AJAX
      // ... (tu código AJAX va aquí)
      $.ajax({
        type: "POST",
        url: "http://localhost/AppwebMVC/Sedes/Listar",
        data: {

          registrar: 'registrar',
          idPastor: idPastor.value,
          nombre: nombre.value,
          direccion: direccion.value,
          estado: estado.value
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
          })

          document.getElementById('nombre').value = ''
          document.getElementById('direccion').value = ''
          document.getElementById('estado').value = ''
          choices.setChoiceByValue('')
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
    nombre2: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/,
    id2: /^\d{1,9}$/,
    texto2: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/,
    estado2: ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
      "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TÁCH", "TRU", "VAR", "YAR", "ZUL"]
  }

  // Validación del ID del pastor
  const idPastor2 = document.getElementById('idPastor2');
  idPastor2.addEventListener('change', (e) => {
    if (expresiones2.id.test(idPastor2.value)) {
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
  const nombre2 = document.getElementById('nombre2');
  nombre2.addEventListener('keyup', (e) => {
    if (expresiones2.nombre2.test(nombre2.value)) {
      validaciones2.nombre2 = true;
      $("#nombre2").removeClass("is-invalid");
      $("#msj_nombre2").addClass("d-none");
    } else {
      validaciones2.nombre2 = false;
      $("#nombre2").addClass("is-invalid");
      $("#msj_nombre2").removeClass("d-none");
    }
  })


  // Validación de la dirección
  const direccion2 = document.getElementById('direccion2');
  direccion2.addEventListener('keyup', (e) => {
    if (expresiones2.texto.test(direccion2.value)) {
      validaciones2.direccion2 = true;
      $("#direccion2").removeClass("is-invalid");
      $("#msj_direccion2").addClass("d-none");
    } else {
      validaciones2.direccion2 = false;
      $("#direccion2").addClass("is-invalid");
      $("#msj_direccion2").removeClass("d-none");
    }
  })


  // Validación del estado
  const estado2 = document.getElementById('estado2');
  estado2.addEventListener('change', (e) => {
    if (expresiones2.estado2.includes(estado2.value)) {
      validaciones2.estado2 = true;
      $("#estado2").removeClass("is-invalid");
      $("#msj_estado2").addClass("d-none");
    } else {
      validaciones2.estado2 = false;
      $("#estado2").addClass("is-invalid");
      $("#msj_estado2").removeClass("d-none");
    }
  })


  $("#formulario2").submit(function (event) {
    // Previene el comportamiento predeterminado del formulario
    event.preventDefault();

    let id2 = document.getElementById('idSede').textContent;

    // Verificar si todas las validaciones son correctas
    if (Object.values(validaciones2).every(val => val)) {
      // Si todas las validaciones son correctas, realiza la petición AJAX
      // ... (tu código AJAX va aquí)
      $.ajax({
        type: "POST",
        url: "http://localhost/AppwebMVC/Sedes/Listar",
        data: {

          editar: 'editar',
          id: id2,
          idPastor: idPastor2.value,
          nombre: nombre2.value,
          direccion: direccion2.value,
          estado: estado2.value
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


