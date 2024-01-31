<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaCrecimiento-reunion.js"];
?>

<div class="page-top d-flex align-items-end justify-content-between mb-2">
<h2><strong>Reuniones de Celula de Crecimiento</strong></h2>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="search" class="form-control" placeholder="Buscar Reunion">
        </div>
    </div>
</div>



     <div class="table-responsive">
            <table id="celulaDatatables" class="table table-bordered table-rounded table-hover" style="width:100%"> 

                <thead>
                        <tr>
                        <th>Codigo Celula</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th class="text-center" style="width: 170px;">Opciones</th>
                        </tr>
                </thead>
                <!-- AQUI MOSTRARA LA INFORMACION -->
                </tbody>
            </table>
        </div>






    <!-- MODAL PARA VER TODA LA Informacion de la reunion REUNION -->
    <div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion de la reunion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->

                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-4">
                            <strong>Código:</strong>
                            <p id="inf_codigocelula"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Fecha:</strong>
                            <p id="inf_fecha"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Semana:</strong>
                            <p id="inf_semana"></p>
                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item text-center">
                            <strong>Asistencia:</strong>
                            <p id="inf_asistencia"></p>
                        </li>
                    </ul>

                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-4">
                            <strong>Infantil:</strong>
                            <p id="inf_infantil"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Juvenil:</strong>
                            <p id="inf_juvenil"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Adulto:</strong>
                            <p id="inf_adulto"></p>
                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Tema:</strong>
                            <p id="inf_tematica"></p>
                        </li>

                        <li class="list-group-item">
                            <strong>Generosidad:</strong>
                            <p id="inf_generosidad"></p>
                        </li>

                        <li class="list-group-item">
                            <strong>Actividad:</strong>
                            <p id="inf_actividad"></p>
                        </li>

                        <li class="list-group-item">
                            <strong>Observaciones:</strong>
                            <p id="inf_observaciones"></p>
                        </li>

                    </ul>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    
    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA Reunion -->
    <div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar datos de la Reunion</h5>
                </div>
                <div class="modal-body">
                    <form id="formularioReunion">
                        <div class="row">

                            <div class="mb-3">
                                <input type="hidden" id="idreunion">
                                <label for="idCelula" class="form-label fw-bold">Celula Crecimiento</label>
                                <select class="form-select" id="idCelula" name="idCelula">
                                    <option selected value="">Seleccione</option>
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCelula" role="alert">
                                </div>
                            </div>

                            <div class="mb-3">
                                    <label for="fecha" class="form-label fw-bold">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" aria-describedby="msj_fecha">
                                    <div class="invalid-feedback" id="msj_fecha" role="alert"></div>
                                </div>


                                <div class="mb-3">
                                    <label for="tematica" class="form-label fw-bold">Tematica</label>
                                    <textarea class="form-control" id="tematica" name="tematica" maxlength="100" aria-describedby="msj_tematica"></textarea>
                                    <div class="invalid-feedback" id="msj_tematica" role="alert"></div>
                                </div>


                                <div class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <label for="semana" class="form-label fw-bold">Semana del ciclo</label>
                                            <input type="number" class="form-control" id="semana" min="0" aria-describedby="msj_semana">
                                            <div class="invalid-feedback" id="msj_semana" role="alert"></div>
                                        </div>


                                        <div class="col-8">
                                            <label class="form-label fw-bold">Generosidad</label>
                                            <input type="number" class="form-control" id="generosidad"  step="0.01" min="0" aria-describedby="msj_generosidad">
                                            <div class="invalid-feedback" id="msj_generosidad" role="alert"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label fw-bold">Asistencia:</label>
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <label for="infantil" class="form-label fw-bold">Infantil:</label>
                                            <input type="number" class="form-control" id="infantil" name="infantil" placeholder="Infantil" min="0" aria-describedby="msj_infantil">
                                            <div class="invalid-feedback" id="msj_infantil" role="alert"></div>
                                        </div>


                                        <div class="col-4">
                                            <label for="juvenil" class="form-label fw-bold">Juvenil:</label>
                                            <input type="number" class="form-control" id="juvenil" name="juvenil" placeholder="Juvenil" min="0" aria-describedby="msj_juvenil">
                                            <div class="invalid-feedback" id="msj_juvenil" role="alert"></div>
                                        </div>

                                        <div class="col-4">
                                            <label for="adulto" class="form-label fw-bold">Adultos:</label>
                                            <input type="number" class="form-control" id="adulto" name="adulto" placeholder="Adulto" min="0" aria-describedby="msj_adulto">
                                            <div class="invalid-feedback" id="msj_adulto" role="alert"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label for="actividad" class="form-label fw-bold">Actividad</label>
                                    <textarea class="form-control" id="actividad" name="actividad" maxlength="100" aria-describedby="msj_actividad"></textarea>

                                    <div class="invalid-feedback" id="msj_actividad" role="alert"></div>
                                </div>


                                <div class="mb-3">
                                    <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" maxlength="100" rows="3" aria-describedby="msj_observaciones"></textarea>

                                    <div class="invalid-feedback" id="msj_observaciones" role="alert"></div>
                                </div>

                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-secondary" id="cerrarReunion">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
