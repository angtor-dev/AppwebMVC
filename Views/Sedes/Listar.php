<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["listar-sede.js"];
?>

<h2>Sedes:</h2>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="sedeDatatables" class="table table-light table-hover">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre de la sede</th>
                            <th>Direccion</th>
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


    <!-- MODAL PARA VER TODOS LOS DATOS DE LA SEDE -->
    <div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion de la sede</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                    <ul class="list-group">
                    <input type="hidden" id="id_Pastor">
                        <li class="list-group-item "><strong>Codigo:</strong>
                            <p id="inf_codigo"></p>
                        </li>
                        <li class="list-group-item "><strong>Nombre de la Sede:</strong>
                            <p id="inf_nombre"></p>
                        </li>
                        <li class="list-group-item "><strong>Pastor a cargo:</strong>
                            <p id="inf_idPastor"></p>
                        </li>
                        <li class="list-group-item "><strong>estado:</strong>
                            <p id="inf_estado"></p>
                        </li>
                        <li class="list-group-item ">
                            <h6><strong>Dirección:</strong></h6>
                            <p id="inf_direccion"></p>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA SEDE -->
    <div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar datos de la Sede</h5>
                </div>
                <div class="modal-body">
                    <form id="formulario">
                        <div class="mb-3">
                            <input type="hidden" id="idSede">
                            <label for="sede" class="form-label fw-bold">Pastor responsable</label>
                            <select class="form-select" id="idPastor" name="idPastor">
                            </select>
                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idPastor" role="alert">
                                Debe seleccionar un Pastor.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre de la sede</label>
                            <input type="text" class="form-control" id="nombre" maxlength="30" name="nombre">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombre" role="alert">
                                Este campo no acepta numeros y no puede estar vacio.
                            </div>


                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label fw-bold">Dirección</label>
                            <input type="text" class="form-control" id="direccion" maxlength="100" name="direccion">

                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_direccion" role="alert">
                                Este campo no puede estar vacio.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="" selected>Selecciona un estado</option>
                                <option value="ANZ">Anzoátegui</option>
                                <option value="APUR">Apure</option>
                                <option value="ARA">Aragua</option>
                                <option value="BAR">Barinas</option>
                                <option value="BOL">Bolívar</option>
                                <option value="CAR">Carabobo</option>
                                <option value="COJ">Cojedes</option>
                                <option value="DELTA">Delta Amacuro</option>
                                <option value="FAL">Falcón</option>
                                <option value="GUA">Guárico</option>
                                <option value="LAR">Lara</option>
                                <option value="MER">Mérida</option>
                                <option value="MIR">Miranda</option>
                                <option value="MON">Monagas</option>
                                <option value="ESP">Nueva Esparta</option>
                                <option value="POR">Portuguesa</option>
                                <option value="SUC">Sucre</option>
                                <option value="TÁCH">Táchira</option>
                                <option value="TRU">Trujillo</option>
                                <option value="VAR">Vargas</option>
                                <option value="YAR">Yaracuy</option>
                                <option value="ZUL">Zulia</option>
                            </select>
                            <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_estado" role="alert">
                                Debe seleccionar un estado.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Editar</button>
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>