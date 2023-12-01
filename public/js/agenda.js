document.addEventListener('DOMContentLoaded', function () {
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

          $('#exampleModal').modal('show');

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
      textColor: 'black' // a non-ajax option
    },

    eventClick: function (info) {

      $('#hola').html(info.event.extendedProps.descripcion);

      $('#exampleModal').modal('show');
      // change the day's background color just for fun

    },



  });

  

  calendar.render();

  function Listar_Eventos() {

    $.ajax({
      type: "GET",
      url: "http://localhost/AppwebMVC/Agenda/Index",
      data: {

        listarEventos: 'listarEventos',

      },
      success: function (response) {

        console.log(response);
        let data = JSON.parse(response);
        calendar.refetchEvents()
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Aquí puedes manejar errores, por ejemplo:
        console.error("Error al enviar:", textStatus, errorThrown);
        alert("Hubo un error al obtener los eventos");
      }
    })
  }

  document.getElementById('hey').addEventListener('click', function name(params) {
    calendar.destroy()
  })
  document.getElementById('otro').addEventListener('click', function name(params) {
    calendar.getEvents(Listar_Eventos())
  })
})