<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["inscripciones.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("estudiantes", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("estudiantes", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("estudiantes", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("estudiantes", "eliminar") ? 1 : 0 ?>
    }
</script>

<div class="container-fluid">

    <div class="page-top d-flex align-items-end justify-content-between mb-2">
        <h2><strong>Estudiantes</strong></h2>
        <div class="d-flex gap-3">
            <div class="buscador">
                <input type="text" id="search" class="form-control" placeholder="Buscar Estudiantes">
            </div>
            <?php if ($usuario->tienePermiso("estudiantes", "registrar")): ?>
                <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal"
                    data-bs-target="#modal_registrar">
                    <i class="fa-solid fa-plus"></i>
                    Registrar estudiante
                </button>
            <?php endif ?>
        </div>
    </div>


    <div class="table-responsive">
        <table id="estudiantesDatatables" class="table table-bordered table-rounded table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>fecha de inscripción</th>
                    <!-- <th>cursando</th> -->
                    <th class="text-center" style="width: 100px;">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- AQUI MOSTRARA LA INFORMACION -->
            </tbody>
        </table>
    </div>


    <?php if ($usuario->tienePermiso("estudiantes", "registrar")): ?>
        <!-- MODAL PARA INSCRIBIR ESTUDIANTE -->

        <div class="modal fade" id="modal_registrar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar E.I.D</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formulario">
                            <div class="mb-3">
                                <input type="number" class="form-control" min="0" maxlength="8" id="cedula"
                                    aria-describedby="msj_cedula">
                                <div id="msj_cedula" class="invalid-feedback">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" id="cerrarRegistrar" class="btn btn-secondary">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Inscribir</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    <?php endif ?>

    <?php if ($usuario->tienePermiso("estudiantes", "consultar")): ?>
        <!-- MODAL PARA INSCRIBIR ESTUDIANTE -->

        <div class="modal fade" id="modalHistorial" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="nombreEstudiante2"></h5>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-3 justify-content-end">
                            <div class="buscador">
                                <input type="text" id="search1" class="form-control" placeholder="Buscar Grupo">
                            </div>
                        </div>


                        <p id="idEstudiante" class="visually-hidden"></p>

                        <div class="table-responsive">
                            <table id="Historial" class="table table-bordered table-rounded table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Mentor</th>
                                        <th>Nota Total</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha fin</th>
                                        <th>estado</th>
                                        <th class="text-center" style="width: 100px;">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- AQUI MOSTRARA LA INFORMACION -->
                                </tbody>
                            </table>
                        </div>





                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end gap-1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?php endif ?>


    <?php if ($usuario->tienePermiso("estudiantes", "consultar")): ?>
        <!-- MODAL PARA INSCRIBIR ESTUDIANTE -->

        <div class="modal fade" id="modal_notas" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="tituloNotas"></h5>
                    </div>
                    <div class="modal-body">
                       


                        <p id="nombreEstudiante" class="mb-3"></p>
                        <p id="notaTotal" class="mb-3"></p>

                        <div class="d-flex gap-3 justify-content-end">
                            <div class="buscador">
                                <input type="text" id="search2" class="form-control" placeholder="Buscar nota">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="NotasEstudiante" class="table table-bordered table-rounded table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Clase</th>
                                        <th>Ponderacion</th>
                                        <th>Calificacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- AQUI MOSTRARA LA INFORMACION -->
                                </tbody>
                            </table>





                        </div>
                        <div class="modal-footer">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalHistorial" title="Ver Historial">atras</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        <?php endif ?>