<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["Discipulo-listar.js"];
?>

<h2>Discipulos:</h2>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="sedeDatatables" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Celula de Consolidacion </th>
                            <th>Asistencias</th>
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
                    <h5 class="modal-title">Informacion del Discipulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->


                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-4">
                            <strong>Nombre:</strong>
                            <p id="inf_nombre"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Apellido:</strong>
                            <p id="inf_apellido"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Cedula</strong>
                            <p id="inf_cedula"></p>
                        </li>
                    </ul>


                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-4">
                            <strong>Fecha de Nacimiento</strong>
                            <p id="inf_fechaNacimiento"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Numero de telefono</strong>
                            <p id="inf_telefono"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Estado Civil</strong>
                            <p id="inf_estadoCivil"></p>
                        </li>
                    </ul>

                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-4">
                            <strong>Celula de Consolidacion</strong>
                            <p id="inf_codigo"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Consolidador</strong>
                            <p id="inf_idConsolidador"></p>
                        </li>

                        <li class="list-group-item col-4">
                            <strong>Fecha de Conversion</strong>
                            <p id="inf_fechaConvercion"></p>
                        </li>
                    </ul>


                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item col-6">
                            <strong>Asiste a Celula Familiar:</strong>
                            <p id="inf_asisFamiliar"></p>
                        </li>

                        <li class="list-group-item col-6">
                            <strong>Asiste a Celula de Crecimiento</strong>
                            <p id="inf_asisCrecimiento"></p>
                        </li>

                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Motivo:</strong>
                            <p id="inf_motivo"></p>
                        </li>

                        <li class="list-group-item">
                            <strong>Direccion:</strong>
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


    <!-- MODAL PARA EDITAR TODOS LOS DATOS DEL DISCIPULO -->
    <div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar datos de el Discipulo</h5>
                </div>
                <div class="modal-body">
                    <form id="formulario" class="row" novalidate>
                        <div class="row my-2">
                            <div class="col-md-6">
                                <input type="hidden" class="d-none" id="idDiscipulo">
                                <label for="nombre" class="form-label fw-bold">Nombre</label>
                                <input name="nombre" type="text" class="form-control" id="nombre" maxlength="50" aria-describedby="msj_nombre" required>
                                <div id="msj_nombre" class="invalid-feedback">
                                    Este campo no puede estar vacio y no acepta numeros.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="apellido" class="form-label fw-bold">Apellido</label>
                                <input type="text" class="form-control" id="apellido" aria-describedby="msj_apellido" maxlength="50">
                                <div id="msj_apellido" class="invalid-feedback">
                                    este campo no puede estar vacio y no acepta numeros.
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="cedula" class="form-label fw-bold">Cedula de Identidad</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">CI</span>
                                    <input type="number" class="form-control" id="cedula" aria-describedby="msj_cedula">
                                    <div id="msj_cedula" class="invalid-feedback">
                                        este campo no puede estar vacio, escriba correctamente la cedula.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-6">
                                <label for="telefono" class="form-label fw-bold">Numero de telefono</label>
                                <input type="number" class="form-control" id="telefono" aria-describedby="msj_telefono">
                                <div id="msj_telefono" class="invalid-feedback">
                                    escriba correctamente el numero de telefono ej.04145555555
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                                <select class="form-select" id="estadoCivil" aria-describedby="msj_estadoCivil">
                                    <option selected value="">Seleccione...</option>
                                    <option value="soltero/a">Soltero/a</option>
                                    <option value="casado/a">Casado/a</option>
                                    <option value="viudo/a">Viudo/a</option>
                                </select>
                                <div id="msj_estadoCivil" class="invalid-feedback">
                                    Este campo es obligatorio.
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-6">
                                <label for="fechaNacimiento" class="form-label fw-bold">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fechaNacimiento">
                                <div id="msj_fechaNacimiento" class="invalid-feedback">
                                    Este campo es obligatorio.
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="fechaConvercion" class="form-label fw-bold">Fecha de Conversión</label>
                                <input type="date" class="form-control" id="fechaConvercion">
                                <div id="msj_fechaConvercion" class="invalid-feedback">
                                    este campo es obligatorio
                                </div>
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <label class="form-check-label fw-bold" for="asisCrecimiento">
                                        Asiste a Celula de Crecimiento
                                    </label>
                                    <input class="form-check-input" type="checkbox" value="si" id="asisCrecimiento">
                                </div>

                            </div>

                            <div class="col-lg-6">
                                <div class="form-check">
                                    <label class="form-check-label fw-bold" for="asisCrecimiento">
                                        Asiste a Celula Familiar
                                    </label>
                                    <input class="form-check-input" type="checkbox" value="si" id="asisFamiliar">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-12">
                                <label for="idConsolidador" class="form-label fw-bold">Consolidador</label>
                                <select class="form-select" id="idConsolidador">
                                    <option selected value="">Selecciona un consolidador</option>
                                </select>
                                <div id="msj_idConsolidador" class="text-danger d-none">
                                    Escoja el Consolidador.
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="idcelulaconsolidacion" class="form-label fw-bold">Celula de Consolidacion</label>
                                <select class="form-select" id="idcelulaconsolidacion">
                                    <option selected value="">Selecciona una celula</option>
                                </select>
                                <div id="msj_idcelulaconsolidacion" class="text-danger d-none">
                                    Escoja una Celula de Consolidacion.
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-6">
                                <label for="direccion" class="form-label fw-bold">Dirección</label>
                                <textarea class="form-control" id="direccion" placeholder="" maxlength="100"></textarea>
                                <div class="invalid-feedback">
                                    Este campo no puede estar vacio.
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="motivo" class="form-label fw-bold">Motivo</label>
                                <textarea class="form-control" id="motivo" placeholder="" maxlength="100"></textarea>
                                <div class="invalid-feedback">
                                    Este campo no puede estar vacio.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-1 mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>