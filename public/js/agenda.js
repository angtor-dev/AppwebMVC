document.addEventListener('DOMContentLoaded', function () {

  let datatables;
  let choices1;
  let choices_sinSedes;

  var calendarDiv = document.getElementById('calendar');
  const right = permisos.registrar ? 'addEventButton dayGridMonth,listMonth' : 'dayGridMonth,listMonth'

  var calendar = new FullCalendar.Calendar(calendarDiv, {

    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: right
    },

    customButtons: {
      addEventButton: {
        text: 'Agregar Evento',
        click: function () {
          $('#agregar').modal('show');
          Listar_Sedes();
        }
      },
    },

    locale: 'es',
    navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    editable: false,

    events: {
      url: 'http://localhost/AppwebMVC/Agenda/Index',
      method: 'GET',
      extraParams: {
        listarEventos: 'listarEventos',
      },
      color: '#FFD28E',   // a non-ajax option
      textColor: 'black'
    },

    eventClick: function (info) {
      if (permisos.actualizar) {

        $('#editarEvento').modal('show');
        document.getElementById('nombreEditar').value = info.event.title
        document.getElementById('editarfechaInicio').value = info.event.startStr
        document.getElementById('editarfechaFinal').value = info.event.endStr
        document.getElementById('editarDescripcion').value = info.event.extendedProps.descripcion
        listar_sedes_evento(info.event.id)
        sedes_sin_agregar(info.event.id)

      } else if (permisos.registrarComentario) {

        $('#verEventoPastor').modal('show')
        document.getElementById('nombre2').textContent = info.event.title 
        document.getElementById('fechaInicio2').textContent =  info.event.startStr
        document.getElementById('fechaCierre2').textContent =  info.event.endStr
        document.getElementById('descripcion2').textContent =  info.event.extendedProps.descripcion
        document.getElementById('comentarioPastor').textContent = info.event.extendedProps.comentario

      } else if (permisos.consultarUsuario) {
        
        $('#verEventoUsuario').modal('show')
        document.getElementById('nombre3').textContent = info.event.title 
        document.getElementById('fechaInicio3').textContent =  info.event.startStr
        document.getElementById('fechaCierre3').textContent =  info.event.endStr
        document.getElementById('descripcion3').textContent =  info.event.extendedProps.descripcion
        
      }

    },
  })




  function listar_sedes_evento(idEvento) {

    if (datatables) {
      datatables.destroy();
    }

    datatables = $('#sedesDatatables').DataTable({
      language: {
        info: "",         // para ocultar "Showing x to y of z entries"
        infoEmpty: ""     // para ocultar "Showing 0 to 0 of 0 entries"
      },
      paging: false,
      searching: false,
      responsive: true,
      ajax: {
        method: "POST",
        url: 'http://localhost/AppwebMVC/Agenda/Index',
        data: {
          cargar_data_sedes: 'cargar_data_sedes',
          idEvento: idEvento
        }
      },
      columns: [
        { data: 'nombre' },
        
        {
          defaultContent: `
          <div class="d-flex justify-content-end gap-2">
            <button type="button" id="verComentarioSede" class="btn btn-secondary">
            Ver
            </button>
            <button type="button" id="eliminarSede" class="btn btn-danger delete-btn">Eliminar</button>
            </div>
            `}
      ],
    })


    
  }


  $('#sedesDatatables tbody').on('click', '#verComentarioSede', function () {
    const datos = datatables.row($(this).parents()).data();

    Swal.fire({
      title: "Comentario de la sede",
      text: datos.comentario,
      showConfirmButton: true,
      confirmButtonColor: 'grey',
      confirmButtonText: 'Cerrar'
    });
  })

  

  function Listar_Sedes() {

    $.ajax({
      type: "GET",
      url: "http://localhost/AppwebMVC/Agenda/Index",
      data: {
        listaSedes: 'listaSedes',
      },
      success: function (response) {

        let data = JSON.parse(response);

        let selector = document.getElementById('sedes');

        // Destruir la instancia existente si la hay
        if (choices1) {
          choices1.destroy();
        }


        selector.innerHTML = '';

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.codigo} ${item.nombre}`;
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
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
        alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
      }
    })
  }


  function sedes_sin_agregar(idEvento) {

    $.ajax({
      type: "POST",
      url: "http://localhost/AppwebMVC/Agenda/Index",
      data: {
        sedes_sin_agregar: 'sedes_sin_agregar',
        idEvento: idEvento
      },
      success: function (response) {
        
        let data = JSON.parse(response);

        let selector = document.getElementById('sedes_sin_agregar');

        // Destruir la instancia existente si la hay
        if (choices_sinSedes) {
          choices_sinSedes.destroy();
        }


        selector.innerHTML = '';

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.codigo} ${item.nombre}`;
          selector.appendChild(option);

        });


        choices_sinSedes = new Choices(selector, {
          allowHTML: true,
          searchEnabled: true,  // Habilita la funcionalidad de búsqueda
          removeItemButton: true,  // Habilita la posibilidad de remover items
          placeholderValue: 'Selecciona una opción',  // Texto del placeholder
        });


      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
        alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
      }
    })
  }




  //////////////////////// Registrar

  const validacion1 = {

    titulo: false,
    fechaInicio: false,
    fechaCierre: false,
    descripcion: false

  }


  const form = document.getElementById("formulario1");

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const datos = {
      registroEventos: 'registroEventos',
      titulo: document.getElementById("titulo").value,
      fechaInicio: document.getElementById("fechaInicio").value,
      fechaFinal: document.getElementById("fechaFinal").value,
      descripcion: document.getElementById("descripcion").value,
      sedes: $('#sedes').val()
    }


    $.ajax({

      type: "POST",
      url: "http://localhost/AppwebMVC/Agenda/Index",
      data: datos,
      success: function (response) {
        console.log(response);
        let data = JSON.parse(response);


        // Aquí puedes manejar una respuesta exitosa, por ejemplo:
        console.log("Respuesta del servidor:", data);
        Swal.fire({
          icon: 'success',
          title: data.msj,
          showConfirmButton: false,
          timer: 2000,
        })
        calendar.refetchEvents();
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
  });


  calendar.render();
});
