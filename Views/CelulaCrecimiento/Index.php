<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaCrecimiento-listar.js"];
?>

<div class="container-fluid">

    <div class="row mt-4">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="celulaDatatables" class="table table-hover">
                    <thead>

                        <div class="d-flex align-items-end justify-content-between mb-2">
                            <h2><strong>Celulas de Crecimiento:</strong></h2>
                            <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal" data-bs-target="#modal_registrar">
                                <i class="fa-solid fa-plus"></i>
                                Nueva Celula
                            </button>
                        </div>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre de la Celula Familiar</th>
                            <th>Lider</th>
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

    <!-- MODAL PARA REGISTRAR CELULA -->
    <div class="modal fade" id="modal_registrar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Celula</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form id="formulario">
                            <div class="mb-3 col-12">

                                <label for="nombre" class="form-label fw-bold">Nombre de la Celula</label>
                                <input type="text" class="form-control" id="nombre" maxlength="50" name="nombre">

                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombre" role="alert">
                                    Este campo no acepta numeros y no puede estar vacio.
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idLider" class="form-label fw-bold">Lider Responsable</label>
                                <select class="form-select" id="idLider" name="idLider">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idLider" role="alert">
                                    Debe seleccionar un Lider. Ademas, no puede ser el mismo lider en ambos seleccionadores
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="CoLider" class="form-label fw-bold">Co Lider Responsable</label>
                                <select class="form-select" id="idCoLider" name="idCoLider">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCoLider" role="alert">
                                    Debe seleccionar un Lider. Ademas, no puede ser el mismo lider en ambos seleccionadores
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idTerritorio" class="form-label fw-bold">Territorio</label>
                                <select class="form-select" id="idTerritorio" name="idTerritorio" placeholder="hola">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idTerritorio" role="alert">
                                    Debe seleccionar un Territorio.
                                </div>
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

    <!-- MODAL PARA VER TODOS LOS DATOS DE LA Celula -->
    <div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion de la Celula de Crecimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                    <ul class="list-group">

                        <li class="list-group-item "><strong>Codigo:</strong>
                            <p id="inf_codigo"></p>
                        </li>
                        <li class="list-group-item "><strong>Nombre:</strong>
                            <p id="inf_nombre"></p>
                        </li>
                        <li class="list-group-item "><strong>Lider:</strong>
                            <p id="inf_idLider"></p>
                        </li>
                        <li class="list-group-item "><strong>Co Lider:</strong>
                            <p id="inf_idCoLider"></p>
                        </li>
                        <li class="list-group-item "><strong>Territorio:</strong>
                            <p id="inf_idTerritorio"></p>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA CELULA -->
    <div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar datos de la Celula</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form id="formulario2">
                            <div class="mb-3 col-12">
                                <input type="hidden" id="idCelulaCrecimiento">
                                <label for="nombre2" class="form-label fw-bold">Nombre de la Celula</label>
                                <input type="text" class="form-control" id="nombre2" maxlength="50" name="nombre2">

                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombre2" role="alert">
                                    Este campo no acepta numeros y no puede estar vacio.
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idLider2" class="form-label fw-bold">Lider Responsable</label>
                                <select class="form-select" id="idLider2" name="idLider2">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idLider2" role="alert">
                                    Debe seleccionar un Lider. Ademas, no puede ser el mismo lider en ambos seleccionadores
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="CoLider2" class="form-label fw-bold">Co Lider Responsable</label>
                                <select class="form-select" id="idCoLider2" name="idCoLider2">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCoLider2" role="alert">
                                    Debe seleccionar un Lider. Ademas, no puede ser el mismo lider en ambos seleccionadores
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idTerritorio2" class="form-label fw-bold">Territorio</label>
                                <select class="form-select" id="idTerritorio2" name="idTerritorio2" placeholder="hola">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idTerritorio2" role="alert">
                                    Debe seleccionar un Territorio.
                                </div>
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


    <!-- MODAL PARA REGISTRAR REUNION DE LA CELULA-->
    <div class="modal fade" id="modal_registroreunion" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Reunión</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form id="formularioReunion">

                            <input type="hidden" id="idCelulaFamiliarR">
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


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>