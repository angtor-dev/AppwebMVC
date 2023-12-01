<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["agenda.js"];

$title = "Agenda"
?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("agendaApostol", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("agendaApostol", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("agendaApostol", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("agendaApostol", "eliminar") ? 1 : 0 ?>,
        registrarFeedback: <?php echo $usuario->tienePermiso("agendaPastor", "registrar") ? 1 : 0 ?>,
        consultarFeedback: <?php echo $usuario->tienePermiso("agendaPastor", "consultar") ? 1 : 0 ?>,
        actualizarFeedback: <?php echo $usuario->tienePermiso("agendaPastor", "actualizar") ? 1 : 0 ?>,
        eliminarFeedback: <?php echo $usuario->tienePermiso("agendaPastor", "eliminar") ? 1 : 0 ?>
    }

    console.log(permisos);
</script>

<style>
#calendar {
    font-size: 14px;
    max-width: 1100px;
    margin: 0 auto;
}

.fc-daygrid-day-number {
    color: black;
}

.fc-col-header-cell-cushion {
    color: black;
}
</style>

<h1><?php echo $usuario->idSede ?></h1>
<div id="calendar"></div>



<!-- Agregar Evento -->
<div class="modal fade" id="agregar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                <form id="formulario1">


                    <p id="hola"></p>
                    <div class="mb-3">
                        <label for="titulo" class="form-label fw-bold">Nombre del Evento:</label>
                        <input type="text" class="form-control" id="titulo" maxlength="50" name="titulo">

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_titulo"
                            role="alert">
                            Este campo es obligatorio.
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label fw-bold">Fecha de Inicio:</label>
                            <input type="date" class="form-control" id="fechaInicio" maxlength="50" name="fechaInicio">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_fechaInicio"
                                role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaFinal" class="form-label fw-bold">Fecha de Cierre:</label>
                            <input type="date" class="form-control" id="fechaFinal" maxlength="50" name="fechaFinal">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_fechaFinal"
                                role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sedes" class="form-label fw-bold">Sedes</label>
                        <select multiple class="form-select" id="sedes" name="idSede">
                        </select>
                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idSede"
                            role="alert">
                            Debe seleccionar al menos una sede.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Detalles sobre el evento:</label>
                        <textarea class="form-control" id="descripcion" maxlength="150" name="descripcion"></textarea>

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_descripcion"
                            role="alert">
                            Este campo no puede estar vacio.
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Agregar</button>
                </form>
            </div>

        </div>
    </div>
</div>



<!-- Editar Evento
<div class="modal fade" id="Editar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO 
                <form id="formulario">


                    <div class="mb-3">
                        <label for="evento" class="form-label fw-bold">Nombre del Evento:</label>
                        <input type="text" class="form-control" id="evento" maxlength="50" name="evento">

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_evento"
                            role="alert">
                            Este campo es obligatorio.
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label fw-bold">Fecha de Inicio:</label>
                            <input type="date" class="form-control" id="fechaInicio" maxlength="50" name="fechaInicio">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_fechaInicio"
                                role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaCierre" class="form-label fw-bold">Fecha de Cierre:</label>
                            <input type="date" class="form-control" id="fechaCierre" maxlength="50" name="fechaCierre">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_fechaCierre"
                                role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sedes" class="form-label fw-bold">Sedes</label>
                        <select class="form-select" id="idSede" name="idSede">
                        </select>
                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idSede"
                            role="alert">
                            Debe seleccionar al menos una sede.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="detalles" class="form-label fw-bold">Detalles sobre el evento:</label>
                        <textarea class="form-control" id="detalles" maxlength="150" name="detalles"></textarea>

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_detalles"
                            role="alert">
                            Este campo no puede estar vacio.
                        </div>
                    </div>



                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary">Actualizar</button>

                </form>
            </div>

        </div>
    </div>
</div> -->
