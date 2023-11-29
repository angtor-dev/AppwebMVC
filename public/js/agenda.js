document.addEventListener('DOMContentLoaded', function() {
    var calendarDiv = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarDiv, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      selectable: true,
      selectHelper: false,
      dayGrid: {
        dayNumbersColor: '#FF0000',
      },

      
    dateClick: function(info) {
      

      $('#exampleModal').modal('show');
     
      // change the day's background color just for fun
      info.dayEl.style.backgroundColor = 'red';
    },
      
      events: [
        
      ]
   

     });
    calendar.render();
})