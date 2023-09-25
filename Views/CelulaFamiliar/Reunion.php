<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaFamiliar-reunion.js"];
?>

<h2>Reuniones Celulas Familiares:</h2>

<div class="container-fluid">

    <div class="row mt-4">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="celulaDatatables" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Codigo Celula Familiar</th>
                            <th>Nombre de la Celula Familiar</th>
                            <th>Fecha de la reunión</th>
                            <th>Opciones</th>
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
                                <label for="idCelula" class="form-label fw-bold">Celula Familiar</label>
                                <select class="form-select" id="idCelula" name="idCelula">
                                    <option selected value="">Seleccione</option>
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCelula" role="alert">
                                    Debe seleccionar una CelulaFamiliar.
                                </div>
                            </div>

                            <div class="mb-3">
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
                                <label class="form-label fw-bold">Asistencia:</label>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label for="infantil" class="form-label fw-bold">Infantil:</label>
                                        <input type="number" class="form-control" id="infantil" name="infantil" placeholder="Infantil">
                                        <div class="alert alert-danger d-none" id="msj_infantil" role="alert"></div>
                                    </div>


                                    <div class="col-4">
                                        <label for="juvenil" class="form-label fw-bold">Juvenil:</label>
                                        <input type="number" class="form-control" id="juvenil" name="juvenil" placeholder="Juvenil">
                                        <div class="alert alert-danger d-none" id="msj_juvenil" role="alert"></div>
                                    </div>

                                    <div class="col-4">
                                        <label for="adulto" class="form-label fw-bold">Adultos:</label>
                                        <input type="number" class="form-control" id="adulto" name="adulto" placeholder="Adulto">
                                        <div class="alert alert-danger d-none" id="msj_adulto" role="alert"></div>
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
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>