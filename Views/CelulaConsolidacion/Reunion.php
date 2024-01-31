<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaConsolidacion-reunion.js"];
?>

<div class="page-top d-flex align-items-end justify-content-between mb-2">
<h2><strong>Reuniones de Celula Consolidacion</strong></h2>
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




    
    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA REUNION -->
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
                                <label for="idCelula" class="form-label fw-bold">Celula Consolidacion</label>
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


    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA ASISTENCIA -->
<div class="modal fade" id="modal_editarAsistencia" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar asistencias de la Reunion</h5>
            </div>
            <div class="modal-body">
                
                    <div class="table-responsive">
                        <table id="asistenciasDatatables" class="table" style="width:100%"> 
            
                            <thead>
                                    <tr>
                                        <th style="width: 170px;">Discipulos</th>
                                        <th class="text-center" style="width: 30px;"></th>
                                    </tr>
                            </thead>
                            <tbody>
                            <!-- AQUI MOSTRARA LA INFORMACION -->
                            </tbody>
                        </table>
                    </div>
                
                  <div class="mt-2">

                        <select multiple name="discipulosAsistencia" id="discipulos">

                        </select>
                        <div class="alert alert-danger d-none" id="msj_discipulosAsistencia" role="alert">
                        
                        </div>


                    </div>
                

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cerrarAsistencia">Cancelar</button>
                    <button type="submit" id="actualizarDiscipulos" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>