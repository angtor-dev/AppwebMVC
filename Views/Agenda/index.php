<?php
/** @var Evento[] $eventos */
$_layout = "Agenda";
?>

<div id="calendar"></div>

<div class="modal" id="exampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="formEvento" id="formEvento" action="/AppwebMVC/Agenda/Registrar" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label for="evento" class="col-sm-12 control-label">Nombre del Evento</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="evento" id="evento" placeholder="Nombre del Evento" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio" class="col-sm-12 control-label">Fecha Inicio</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha Inicio">
                    </div>
                </div>
                <div class="form-group">
                    <label for="fecha_fin" class="col-sm-12 control-label">Fecha Final</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fecha_fin" id="fecha_fin" placeholder="Fecha Final">
                    </div>
                </div>

                <div class="col-md-12" id="grupoRadio">

                    <input type="radio" name="color_evento" id="orange" value="#FF5722" checked>
                    <label for="orange" class="circu" style="background-color: #FF5722;"> </label>

                    <input type="radio" name="color_evento" id="amber" value="#FFC107">
                    <label for="amber" class="circu" style="background-color: #FFC107;"> </label>

                    <input type="radio" name="color_evento" id="lime" value="#8BC34A">
                    <label for="lime" class="circu" style="background-color: #8BC34A;"> </label>

                    <input type="radio" name="color_evento" id="teal" value="#009688">
                    <label for="teal" class="circu" style="background-color: #009688;"> </label>

                    <input type="radio" name="color_evento" id="blue" value="#2196F3">
                    <label for="blue" class="circu" style="background-color: #2196F3;"> </label>

                    <input type="radio" name="color_evento" id="indigo" value="#9c27b0">
                    <label for="indigo" class="circu" style="background-color: #9c27b0;"> </label>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Evento</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal" id="modalUpdateEvento" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar mi Eventox</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="formEventoUpdate" id="formEventoUpdate" action="UpdateEvento.php" class="form-horizontal" method="POST">
                <input type="hidden" class="form-control" name="idEvento" id="idEvento">
                <div class="form-group">
                    <label for="evento" class="col-sm-12 control-label">Nombre del Evento</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="evento" id="evento" placeholder="Nombre del Evento" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio" class="col-sm-12 control-label">Fecha Inicio</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha Inicio">
                    </div>
                </div>
                <div class="form-group">
                    <label for="fecha_fin" class="col-sm-12 control-label">Fecha Final</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fecha_fin" id="fecha_fin" placeholder="Fecha Final">
                    </div>
                </div>

                <div class="col-md-12 activado">

                    <input type="radio" name="color_evento" id="orangeUpd" value="#FF5722" checked>
                    <label for="orangeUpd" class="circu" style="background-color: #FF5722;"> </label>

                    <input type="radio" name="color_evento" id="amberUpd" value="#FFC107">
                    <label for="amberUpd" class="circu" style="background-color: #FFC107;"> </label>

                    <input type="radio" name="color_evento" id="limeUpd" value="#8BC34A">
                    <label for="limeUpd" class="circu" style="background-color: #8BC34A;"> </label>

                    <input type="radio" name="color_evento" id="tealUpd" value="#009688">
                    <label for="tealUpd" class="circu" style="background-color: #009688;"> </label>

                    <input type="radio" name="color_evento" id="blueUpd" value="#2196F3">
                    <label for="blueUpd" class="circu" style="background-color: #2196F3;"> </label>

                    <input type="radio" name="color_evento" id="indigoUpd" value="#9c27b0">
                    <label for="indigoUpd" class="circu" style="background-color: #9c27b0;"> </label>

                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Cambios de mi Evento</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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

        events: [
            <?php foreach ($eventos as $evento): ?>
                {
                    _id: '<?php echo $evento->id; ?>',
                    title: '<?php echo $evento->titulo; ?>',
                    start: '<?php echo $evento->fechaInicio; ?>',
                    end:   '<?php echo $evento->fechaFinal; ?>',
                    color: '<?php echo $evento->color; ?>'
                },
            <?php endforeach ?>
        ],


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

        //Actualizar Evento del Calendario 
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
</script>