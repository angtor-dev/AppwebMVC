document.addEventListener('DOMContentLoaded', function() {
    var calendarDiv = document.getElementById('calendar');

    

    var calendar = new FullCalendar.Calendar(calendarDiv, {

      

      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'addEventButton dayGridMonth,listMonth',
        
      },

      customButtons: {
        addEventButton: {
          text: 'Agregar Evento',
          click: function() {

            $('#exampleModal').modal('show');

          }
        }
      },
      
      locale: 'es',

      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
     
      dayGrid: {
        dayNumbersColor: '#FF0000',
      },

      events: {
        url: 'http://localhost/AppwebMVC/Agenda/Index',
        method: 'GET',
        extraParams: {
          listarEventos: 'listarEventos',
        },
        success: function(events) {
          // Llenamos el calendario con los eventos
          console.log(events);
          // $('#calendar').calendar.addResource('addEventSource', events);
      },
        failure: function() {
          alert('Ocurrio un error al cargar los eventos');
        },
        color: 'yellow',   // a non-ajax option
        textColor: 'black' // a non-ajax option
      },

   eventClick: function(info) {
   
      $('#hola').html(info.event.extendedProps.descripcion);
      
      $('#exampleModal').modal('show');
      // change the day's background color just for fun
      
    },
      
   

     });

//      function Listar_Eventos() {

//       $.ajax({
//           type: "GET",
//           url: "http://localhost/AppwebMVC/Agenda/Index",
//           data: {

//               listarEventos: 'listarEventos',

//           },
//           success: function (response) {

//                console.log(response);

//               let data = JSON.parse(response);

//               return data;

//           },
//           error: function (jqXHR, textStatus, errorThrown) {
//               // Aquí puedes manejar errores, por ejemplo:
//               console.error("Error al enviar:", textStatus, errorThrown);
//               alert("Hubo un error al obtener los eventos");
//           }
//       })
//   }
// Listar_Eventos()

 

    calendar.render();
})