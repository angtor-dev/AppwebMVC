<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["territorio-listar.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("territorios", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("territorios", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("territorios", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("territorios", "eliminar") ? 1 : 0 ?>
    }
</script>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="territorioDatatables" class="table table-hover">
                    <thead>

                        <div class="d-flex align-items-end justify-content-between mb-2">
                            <h2><strong>Territorios</strong></h2>
                            <?php if ($usuario->tienePermiso("territorios", "registrar")) : ?>
                                <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal" data-bs-target="#modal_registrar">
                                    <i class="fa-solid fa-plus"></i>
                                    Nuevo Territorio
                                </button>
                            <?php endif ?>
                        </div>

                        <tr>
                            <th>Codigo</th>
                            <th>Nombre de Territorio</th>
                            <th>Lider Responsable</th>
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
                    <h5 class="modal-title">Informacion del Territorio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                    <ul class="list-group">
                        <li class="list-group-item "><strong>Codigo:</strong>
                            <p id="inf_codigo"></p>
                        </li>
                        <li class="list-group-item "><strong>Nombre de Territorio:</strong>
                            <p id="inf_nombre"></p>
                        </li>
                        <li class="list-group-item "><strong>Lider a cargo:</strong>
                            <p id="inf_idLider"></p>
                        </li>
                        <li class="list-group-item ">
                            <h6><strong>Detalles:</strong></h6>
                            <p id="inf_detalles"></p>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php if ($usuario->tienePermiso("territorios", "registrar")) : ?>
        <!-- MODAL PARA REGISTRAR TERRITORIO -->

        <div class="modal fade" id="modal_registrar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Territorio</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formulario">

                            <div class="mb-3">
                                <label for="sede" class="form-label fw-bold">Sede</label>
                                <select class="form-select" id="idSede" name="idSede">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idSede" role="alert">
                                    Debe seleccionar una Sede.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Nombre de el Territorio</label>
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
                                    Debe seleccionar un Lider.
                                </div>

                            </div>


                            <div class="mb-3">
                                <label for="detalles" class="form-label fw-bold">Detalles</label>
                                <input type="text" class="form-control" id="detalles" maxlength="100" name="detalles">

                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_detalles" role="alert">
                                    Este campo no puede estar vacio.
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>


    <?php if ($usuario->tienePermiso("territorios", "actualizar")) : ?>
        <!-- MODAL PARA EDITAR TERRITORIO -->

        <div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar datos del Territorio</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formulario2">

                            <input type="hidden" id="idTerritorio">
                            <div class="mb-3">
                                <label for="idSede2" class="form-label fw-bold">Sede</label>
                                <select class="form-select" id="idSede2" name="idSede2">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idSede2" role="alert">
                                    Debe seleccionar una Sede.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nombre2" class="form-label fw-bold">Nombre de el Territorio</label>
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
                                    Debe seleccionar un Lider.
                                </div>

                            </div>


                            <div class="mb-3">
                                <label for="detalles2" class="form-label fw-bold">Detalles</label>
                                <input type="text" class="form-control" id="detalles2" maxlength="100" name="detalles2">

                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_detalles2" role="alert">
                                    Este campo no puede estar vacio.
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
    <?php endif ?>

</div>