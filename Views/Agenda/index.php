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
        registrarComentario: <?php echo $usuario->tienePermiso("agendaPastor", "registrar") ? 1 : 0 ?>,
        consultarComentario: <?php echo $usuario->tienePermiso("agendaPastor", "consultar") ? 1 : 0 ?>,
        actualizarComentario: <?php echo $usuario->tienePermiso("agendaPastor", "actualizar") ? 1 : 0 ?>,
        eliminarComentario: <?php echo $usuario->tienePermiso("agendaPastor", "eliminar") ? 1 : 0 ?>,
        consultarUsuario: <?php echo $usuario->tienePermiso("agendaUsuario", "consultar") ? 1 : 0 ?>
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

<div id="calendar"></div>


<!-- Agregar Evento -->
<div class="modal fade" id="agregar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Evento</h5>
            </div>
            <div class="modal-body">
                <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                <form id="formulario1">
                    <div class="mb-3">
                        <label for="titulo" class="form-label fw-bold">Nombre del Evento</label>
                        <input type="text" class="form-control" id="titulo" maxlength="50" name="titulo">

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_titulo"
                            role="alert">
                            Este campo es obligatorio.
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" maxlength="50" name="fechaInicio">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_fechaInicio"
                                role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaFinal" class="form-label fw-bold">Fecha de Cierre</label>
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
                        <button type="submit" class="btn btn-primary">Agregar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!--Editar Evento -->
<div class="modal fade" id="editarEvento" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Evento</h5>
            </div>
            <div class="modal-body">
                <form id="editarFormulario">
                    <div class="mb-3">
                        <label for="nombreEditar" class="form-label fw-bold">Nombre del Evento</label>
                        <input type="text" class="form-control" id="nombreEditar" maxlength="50" name="nombreEditar">

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombreEditar"
                            role="alert">
                            Este campo es obligatorio.
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editarfechaInicio" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="editarfechaInicio" maxlength="50"
                                name="editarfechaInicio">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none"
                                id="msj_editarfechaInicio" role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="editarfechaFinal" class="form-label fw-bold">Fecha de Cierre</label>
                            <input type="date" class="form-control" id="editarfechaFinal" maxlength="50"
                                name="editarfechaFinal">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none"
                                id="msj_editarfechaFinal" role="alert">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editarDescripcion" class="form-label fw-bold">Detalles sobre el evento</label>
                        <textarea class="form-control" id="editarDescripcion" maxlength="200"
                            name="editarDescripcion"></textarea>

                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_editarDescripcion"
                            role="alert">
                            Este campo no puede estar vacio.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sedes vinculadas</label>
                        <div class="table-responsive">
                            <table id="sedesDatatables" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Sede</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- AQUI MOSTRARA LA INFORMACION -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sedes_sin_agregar" class="form-label fw-bold">Agregar Sedes</label>
                        <div class="d-grid">
                            <select multiple class="form-select" id="sedes_sin_agregar" name="sedes_sin_agregar">
                            </select>
                            <button type="submit" class="btn btn-light border border-black">Agregar</button>

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none"
                                id="msj_sedes_sin_agregar" role="alert">
                                Debe seleccionar al menos una sede.
                            </div>
                        </div>
                    </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">Actualizar</button>
                            <button type="button" class="btn btn-danger">Eliminar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                </form>
            </div>

        </div>
    </div>
</div>



<!--Ver Evento rol Pastor -->
<div class="modal fade" id="verEventoPastor" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del evento</h5>
            </div>
            <div class="modal-body">
                <form id="editarFormulario">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Evento</label>
                        <h6 id="nombre2"></h6>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Inicio</label>
                            <h6 id="fechaInicio2"></h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Cierre</label>
                            <h6 id="fechaCierre2"></h6>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Detalles sobre el evento:</label>
                        <h6 id="descripcion2"></h6>
                    </div>

                    <div class="mb-3 d-grid">
                        <label class="form-label fw-bold">Comentario</label>
                        <textarea class="form-control mb-2" id="comentarioPastor" maxlength="200"></textarea>
                        <button type="button" class="btn btn-primary">Actualizar comentario</button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="verEventoUsuario" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del evento</h5>
            </div>
            <div class="modal-body">
                <form id="editarFormulario">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Evento</label>
                        <h6 id="nombre3"></h6>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Inicio</label>
                            <h6 id="fechaInicio3"></h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Cierre</label>
                            <h6 id="fechaCierre3"></h6>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Detalles sobre el evento:</label>
                        <h6 id="descripcion3"></h6>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>