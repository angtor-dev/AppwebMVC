<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaConsolidacion-reunion.js"];
?>

<h2>Reuniones Celulas de Consolidacion:</h2>

<div class="container-fluid">

    <div class="row mt-4">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="celulaDatatables" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Codigo Celula Consolidacion</th>
                            <th>Nombre de la Celula Consolidacion</th>
                            <th>Fecha de la reunión</th>
                            <th class="text-center" style="width: 200px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AQUI MOSTRARA LA INFORMACION -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- MODAL PARA VER TODA LA Informacion de la reunion REUNION -->
    <div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion de la reunion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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


    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA Reunion -->
    <div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar datos de la Reunion</h5>
                </div>
                <div class="modal-body">
                    <form id="formularioReunion">

                        <div class="mb-3">
                            <div class="mb-3">
                                <input type="hidden" id="idreunion">
                                <label for="idCelula" class="form-label fw-bold">Celula de Consolidacion</label>
                                <select class="form-select" id="idCelula" name="idCelula">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCelula" role="alert">
                                    Debe seleccionar una Celula de Consolidacion.
                                </div>
                            </div>

                            <label for="fecha" class="form-label fw-bold">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                            <div class="alert alert-danger d-none" id="msj_fecha" role="alert"> Debe seleccionar la fecha de la reunión</div>
                        </div>


                        <div class="mb-3">
                            <label for="tematica" class="form-label fw-bold">Tematica</label>
                            <input type="text" class="form-control" id="tematica" name="tematica" maxlength="100">
                            <div class="alert alert-danger d-none" id="msj_tematica" role="alert">Este campo no puede estar vacio</div>
                        </div>


                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-4">
                                    <label for="tematica" class="form-label fw-bold">Semana del Ciclo</label>
                                    <input type="number" class="form-control" id="semana" name="tematica">
                                    <div class="alert alert-danger d-none" id="msj_semana" role="alert">Este campo no puede estar vacio</div>
                                </div>


                                <div class="col-8">
                                    <label class="form-label fw-bold">Generosidad</label>
                                    <input type="number" class="form-control" id="generosidad" name="tematica" step="0.01">
                                    <div class="alert alert-danger d-none" id="msj_generosidad" role="alert">Este campo no puede estar vacio</div>
                                </div>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="actividad" class="form-label fw-bold">Actividad</label>
                            <input type="text" class="form-control" id="actividad" name="actividad" maxlength="100">

                            <div class="alert alert-danger d-none" id="msj_actividad" role="alert">Este campo no puede estar vacio</div>
                        </div>


                        <div class="mb-3">
                            <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                            <input type="100" class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>

                            <div class="alert alert-danger d-none" id="msj_observaciones" role="alert">Este campo no puede estar vacio</div>
                        </div>



                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>



    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA Reunion -->
    <div class="modal fade" id="modal_editarAsistencia" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar asistencias de la Reunion</h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        

                            <table id="asistenciasDatatables" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Discipulos</th>
                                        <th>Opcion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- AQUI MOSTRARA LA INFORMACION -->
                                </tbody>
                            </table>

                       



                        <div class="mt-2">

                            <select multiple name="discipulosAsistencia" id="discipulos">

                            </select>
                            <div class="alert alert-danger d-none" id="msj_discipulosAsistencia" role="alert">
                                Debes seleccionar discipulos para actualizar.
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="actualizarDiscipulos" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>