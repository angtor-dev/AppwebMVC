
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
                        <input type="text" class="form-control" id="titulo" maxlength="50" name="titulo" aria-describedby="msj_titulo">

                        <div class="invalid-feedback" id="msj_titulo"
                            role="alert">
                           
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" maxlength="50" name="fechaInicio" aria-describedby="msj_fechaInicio">

                            <div class="invalid-feedback" id="msj_fechaInicio"
                                role="alert">
                          
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaFinal" class="form-label fw-bold">Fecha de Cierre</label>
                            <input type="date" class="form-control" id="fechaFinal" maxlength="50" name="fechaFinal" aria-describedby="msj_fechaFinal">

                            <div class="invalid-feedback" id="msj_fechaFinal"
                                role="alert">
                               
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sedes" class="form-label fw-bold">Sedes</label>
                        <select multiple class="form-select" id="sedes" name="sedes">
                        </select>
                        <div id="msj_sedes" class="alert alert-danger d-flex align-items-center mt-3 d-none">
                            
                            </div>
                            
                        </div>
                   

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Detalles sobre el evento:</label>
                        <textarea class="form-control" id="descripcion" maxlength="150" name="descripcion" aria-describedby="msj_descripcion"></textarea>

                        <div class="invalid-feedback" id="msj_descripcion"
                            role="alert">
                          
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

<!--Ver Evento rol Pastor -->
<div class="modal fade" id="verEventoPastor" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del evento</h5>
            </div>
            <div class="modal-body">
                <form id="formularioPastor">
                <p id="idEvento2" class="d-none"> </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Evento</label>
                        <p id="nombre2"></p>
                    </div>

                    <div class="row my-2" id="hola">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Inicio</label>
                            <p id="fechaInicio2" name="fechaInicio2"></p>
                        </div> 
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Cierre</label>
                            <p id="fechaCierre2" name="fechaInicio2"></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Detalles sobre el evento:</label>
                        <p id="descripcion2"></p>
                    </div>

                    <div class="mb-3 d-grid">
                        <label class="form-label fw-bold">Comentario</label>
                        <textarea class="form-control mb-2" id="comentarioPastor" maxlength="200"></textarea>
                        <button type="submit" class="btn btn-light border border-black">Actualizar comentario</button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--Ver Evento rol Usuario -->
<div class="modal fade" id="verEventoUsuario" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del evento</h5>

            </div>
            <div class="modal-body">
                <form id="formularioUsuario">
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
                        <p id="idEvento" class="d-none"> </p>
                        <div class="mb-3">
                        <label for="tituloEditar" class="form-label fw-bold">Nombre del Evento</label>
                        <input type="text" class="form-control" id="tituloEditar" maxlength="50" name="tituloEditar" aria-describedby="msj_tituloEditar">

                        <div class="invalid-feedback" id="msj_tituloEditar"
                            role="alert">
                           
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-md-6">
                            <label for="fechaInicioEditar" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechaInicioEditar" maxlength="50" name="fechaInicioEditar" aria-describedby="msj_fechaInicioEditar">

                            <div class="invalid-feedback" id="msj_fechaInicioEditar"
                                role="alert">
                          
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaFinalEditar" class="form-label fw-bold">Fecha de Cierre</label>
                            <input type="date" class="form-control" id="fechaFinalEditar" maxlength="50" name="fechaFinalEditar" aria-describedby="msj_fechaFinalEditar">

                            <div class="invalid-feedback" id="msj_fechaFinalEditar"
                                role="alert">
                               
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcionEditar" class="form-label fw-bold">Detalles sobre el evento:</label>
                        <textarea class="form-control" id="descripcionEditar" maxlength="150" name="descripcionEditar" aria-describedby="msj_descripcionEditar"></textarea>

                        <div class="invalid-feedback" id="msj_descripcionEditar"
                            role="alert">
                          
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sedes vinculadas</label>
                        <div class="table-responsive">
                        <table id="sedesDatatables" class="table" style="width:100%"> 
            
                            <thead>
                                    <tr>
                                        <th>Sedes</th>
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
                            <button type="button" id="agregarSedes" class="btn btn-light border border-black">Agregar</button>

                        </div>
                    </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <button type="button" id="eliminar" class="btn btn-danger">Eliminar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                </form>
            </div>

        </div>
    </div>
</div>

