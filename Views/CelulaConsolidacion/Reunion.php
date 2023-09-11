<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaConsolidacion-reunion.js"];
?>

<h2>Reuniones Celulas de Consolidacion:</h2>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="celulaDatatables" class="table table-light table-hover">
                    <thead>
                        <tr>
                            <th>Codigo Celula Consolidacion</th>
                            <th>Nombre de la Celula Consolidacion</th>
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


    <!-- MODAL PARA VER TODOS LOS DATOS DE LA Celula -->
    <div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion de la Celula de Consolidacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->

                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-4">
                            <strong>Código:</strong>
                            <p id="inf_codigocelulaconsolidacion"></p>
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
                    <div class="mb-3">

                        <form id="formularioReunion">



                           
                            <div class="mb-3">

                                <input type="hidden" id="idreunionconsolidacion">
                                <label for="idCelulaConsolidacion" class="form-label fw-bold">Celula de Consolidacion</label>
                                <select class="form-select" id="idCelulaConsolidacion" name="idCelulaConsolidacion">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCelulaConsolidacion" role="alert">
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
                        <label class="form-label fw-bold">Asistencia:</label>





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



                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>