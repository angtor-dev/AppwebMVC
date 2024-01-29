<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaConsolidacion-listar.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("celulaConsolidacion", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("celulaConsolidacion", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("celulaConsolidacion", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("celulaConsolidacion", "eliminar") ? 1 : 0 ?>
    }
</script>


<div class="page-top d-flex align-items-end justify-content-between mb-2">
    <h2><strong>Celula de Consolidacion</strong></h2>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="search" class="form-control" placeholder="Buscar Celula">
        </div>
        <?php if ($usuarioSesion->tienePermiso("celulaConsolidacion", "registrar")): ?>
            <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal"
                data-bs-target="#modal_registrar">
                <i class="fa-solid fa-plus"></i>
                Nueva Celula
            </button>
        <?php endif ?>
    </div>
</div>



<div class="table-responsive">
    <table id="celulaDatatables" class="table table-bordered table-rounded table-hover" style="width:100%">

        <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre de la Celula Consolidacion</th>
                <th>Lider</th>
                <th class="text-center" style="width: 170px;">Opciones</th>
            </tr>
        </thead>
        <!-- AQUI MOSTRARA LA INFORMACION -->
        </tbody>
    </table>
</div>


<?php if ($usuario->tienePermiso("celulaConsolidacion", "registrar")): ?>
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
                                <input type="text" class="form-control" id="nombre" maxlength="50" name="nombre"
                                    aria-describedby="msj_nombre">

                                <div class="invalid-feedback" id="msj_nombre" role="alert">

                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idLider" class="form-label fw-bold">Lider Responsable</label>
                                <select class="form-select" id="idLider" name="idLider">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idLider"
                                    role="alert">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="CoLider" class="form-label fw-bold">Co Lider Responsable</label>
                                <select class="form-select" id="idCoLider" name="idCoLider">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCoLider"
                                    role="alert">
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idTerritorio" class="form-label fw-bold">Territorio</label>
                                <select id="idTerritorio" name="idTerritorio" placeholder="hola">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idTerritorio"
                                    role="alert">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-secondary" id="cerrarRegistrar">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


<!-- MODAL PARA VER TODOS LOS DATOS DE LA Celula -->
<div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informacion de la Celula Consolidacion</h5>
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



<?php if ($usuario->tienePermiso("celulaConsolidacion", "actualizar")): ?>
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
                                <input type="hidden" id="idCelulaConsolidacion">
                                <label for="nombre2" class="form-label fw-bold">Nombre de la Celula</label>
                                <input type="text" class="form-control" id="nombre2" maxlength="50" name="nombre2"
                                    aria-describedby="msj_nombre2">

                                <div class="invalid-feedback" id="msj_nombre2" role="alert">
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idLider2" class="form-label fw-bold">Lider Responsable</label>
                                <select class="form-select" id="idLider2" name="idLider2">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idLider2"
                                    role="alert">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="idCoLider2" class="form-label fw-bold">Co Lider Responsable</label>
                                <select class="form-select" id="idCoLider2" name="idCoLider2">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCoLider2"
                                    role="alert">
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="idTerritorio2" class="form-label fw-bold">Territorio</label>
                                <select class="form-select" id="idTerritorio2" name="idTerritorio2">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idTerritorio2"
                                    role="alert">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-secondary" id="cerrarEditar">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


<?php if ($usuario->tienePermiso("celulaConsolidacion", "actualizar")): ?>
    <!-- MODAL PARA REGISTRAR REUNION DE LA CELULA-->

    <div class="modal fade" id="modal_registroreunion" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Reunión</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <form id="formularioReunion">

                            <input type="hidden" id="idCelulaConsolidacionR">
                            <div class="mb-3">
                                <label for="fecha" class="form-label fw-bold">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha"
                                    aria-describedby="msj_fecha">
                                <div class="invalid-feedback" id="msj_fecha" role="alert"></div>
                            </div>


                            <div class="mb-3">
                                <label for="tematica" class="form-label fw-bold">Tematica</label>
                                <textarea class="form-control" id="tematica" name="tematica" maxlength="100"
                                    aria-describedby="msj_tematica"></textarea>
                                <div class="invalid-feedback" id="msj_tematica" role="alert"></div>
                            </div>


                            <div class="mb-3">
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label for="semana" class="form-label fw-bold">Semana del ciclo</label>
                                        <input type="number" class="form-control" id="semana" min="0"
                                            aria-describedby="msj_semana">
                                        <div class="invalid-feedback" id="msj_semana" role="alert"></div>
                                    </div>


                                    <div class="col-8">
                                        <label class="form-label fw-bold">Generosidad</label>
                                        <input type="number" class="form-control" id="generosidad" step="0.01" min="0"
                                            aria-describedby="msj_generosidad">
                                        <div class="invalid-feedback" id="msj_generosidad" role="alert"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="discipulos" class="form-label fw-bold">Asistencia</label>
                                <select multiple class="form-select" id="discipulos" name="discipulos">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_discipulos"
                                    role="alert">
                                </div>
                            </div>



                            <div class="mb-3">
                                <label for="actividad" class="form-label fw-bold">Actividad</label>
                                <textarea class="form-control" id="actividad" name="actividad" maxlength="100"
                                    aria-describedby="msj_actividad"></textarea>

                                <div class="invalid-feedback" id="msj_actividad" role="alert"></div>
                            </div>


                            <div class="mb-3">
                                <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" maxlength="100"
                                    rows="3" aria-describedby="msj_observaciones"></textarea>

                                <div class="invalid-feedback" id="msj_observaciones" role="alert"></div>
                            </div>



                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-secondary" id="cerrarReunion">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>