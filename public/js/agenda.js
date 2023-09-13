document.addEventListener("DOMContentLoaded", () => {
    $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek,agendaDay"
        },

        locale: 'es',

        defaultView: "month",
        navLinks: true,
        editable: true,
        eventLimit: true,
        selectable: true,
        selectHelper: false,

        //Nuevo Evento
        select: function(start, end) {
            $("#exampleModal").modal();
            $("input[name=fecha_inicio]").val(start.format('DD-MM-YYYY'));

            var valorFechaFin = end.format("DD-MM-YYYY");
            var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY'); //Le resto 1 dia
            $('input[name=fecha_fin').val(F_final);

        },

        events: [],


        //Eliminar Evento
        eventRender: function(event, element) {
            element
                .find(".fc-content")
                .prepend("<span id='btnCerrar'; class='closeon material-icons'>&#xe5cd;</span>");

            //Eliminar evento
            element.find(".closeon").on("click", function() {

                var pregunta = confirm("Deseas Borrar este Evento?");
                if (pregunta) {

                    $("#calendar").fullCalendar("removeEvents", event._id);

                    $.ajax({
                        type: "POST",
                        url: 'deleteEvento.php',
                        data: {
                            id: event._id
                        },
                        success: function(datos) {
                            $(".alert-danger").show();

                            setTimeout(function() {
                                $(".alert-danger").slideUp(500);
                            }, 3000);

                        }
                    });
                }
            });
        },


        //Moviendo Evento Drag - Drop
        eventDrop: function(event, delta) {
            var idEvento = event._id;
            var start = (event.start.format('DD-MM-YYYY'));
            var end = (event.end.format("DD-MM-YYYY"));

            $.ajax({
                url: 'drag_drop_evento.php',
                data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
                type: "POST",
                success: function(response) {
                    // $("#respuesta").html(response);
                }
            });
        },

        //Modificar Evento del Calendario 
        eventClick: function(event) {
            var idEvento = event._id;
            $('input[name=idEvento').val(idEvento);
            $('input[name=evento').val(event.title);
            $('input[name=fecha_inicio').val(event.start.format('DD-MM-YYYY'));
            $('input[name=fecha_fin').val(event.end.format("DD-MM-YYYY"));

            $("#modalUpdateEvento").modal();
        },


    });
})