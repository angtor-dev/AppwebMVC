document.addEventListener('DOMContentLoaded', function() {
    

    let choices1;
   var calendarDiv = document.getElementById('calendar');
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

    eventClick: function(info) {
   
      $('#').html(info.event.extendedProps.descripcion);
      
    },
    })

    




function Listar_Sedes() {

  $.ajax({
      type: "GET",
      url: "http://localhost/AppwebMVC/Agenda/Index",
      data: {

          listaSedes: 'listaSedes',

      },
      success: function (response) {
 
          console.log(response);

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



//registrar


const validacion1 = {

  titulo: false,
  fechaInicio: false,
  fechaCierre: false,
  descripcion: false

}


const form = document.getElementById("formulario1");
console.log(form);

    form.addEventListener("submit", (e) => {
        e.preventDefault();
      const datos = {
         registroEventos: 'registroEventos',
             titulo: document.getElementById("titulo").value,
          fechaInicio: document.getElementById("fechaInicio").value,
       fechaFinal: document.getElementById("fechaFinal").value,
       descripcion:  document.getElementById("descripcion").value,
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
              }}
          
      

  }); 
}); 
      
  
calendar.render();
});
