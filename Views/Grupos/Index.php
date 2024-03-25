<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["grupos.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];


?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("grupos", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("grupos", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("grupos", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("grupos", "eliminar") ? 1 : 0 ?>
    }
</script>

<div class="container-fluid">

    <div class="page-top d-flex align-items-end justify-content-between mb-2">
        <h1><strong>Grupos</strong></h1>
        <div class="d-flex gap-3">
            <?php if ($usuario->tienePermiso("grupos", "registrar")): ?>
                <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal"
                    data-bs-target="#modal_registrar">
                    <i class="fa-solid fa-plus"></i>
                    Nuevo grupo
                </button>
            <?php endif ?>
        </div>
    </div>

    <div class="mb-3">
        <?php if ($usuario->tienePermiso("grupos", "registrar")): ?>

            <div class="card">
                <div class="card-header">
                    <nav>
                        <div class="nav nav-tabs card-header-tabs" id="nav-tabGrupos" role="tablist">
                            <button class="nav-link active" data-bs-toggle="tab" id="activo"
                                data-bs-target="#tab-gruposActivos" type="button" role="tab" aria-selected="true">
                                Activos
                            </button>
                            <button class="nav-link" data-bs-toggle="tab" id="abierto" data-bs-target="#tab-gruposActivos"
                                type="button" role="tab" aria-selected="false">
                                Abiertos
                            </button>
                            <button class="nav-link" data-bs-toggle="tab" id="cerrado" data-bs-target="#tab-gruposActivos"
                                type="button" role="tab" aria-selected="false">
                                Cerrados
                            </button>

                        </div>
                    </nav>
                </div>
                <div class="card-body">
                    <!-- Grupos Activos -->
                    <!-- <p id="idEid2" class="visually-hidden"></p> -->
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab-gruposActivos" role="tabpanel" tabindex="0">
                            <div class="page-top d-flex align-items-end justify-content-between mb-2">
                                <strong>
                                    <h5>Grupos Activos</h5>
                                </strong>
                                <div class="d-flex gap-3">
                                    <div class="buscador">
                                        <input type="text" id="searchgruposActivos" class="form-control"
                                            placeholder="Buscar grupo">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">

                                <div class="table-responsive">
                                    <table id="Grupos" class="table table-bordered table-rounded table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Mentor</th>
                                                <th>Estudiantes</th>
                                                <th>FechaInicio</th>
                                                <th>Fecha Fin</th>
                                                <th class="text-center" style="width: 100px;">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- AQUI MOSTRARA LA INFORMACION -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php endif ?>
            </div>

        </div>
    </div>

    <?php if ($usuario->tienePermiso("grupos", "registrar")): ?>
        <!-- MODAL PARA REGISTRAR GRUPO -->
        <div class="modal fade" id="modal_registrar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="titulo1" class="modal-title"></h5>
                    </div>
                    <div class="modal-body">
                        <form id="formulario">

                            <p id="Grupo" class="visually-hidden"></p>
                            <p id="accion" class="visually-hidden"></p>

                            <div class="mb-3">
                                <label for="idNivel" class="form-label fw-bold">Nivel</label>
                                <select class="form-select" id="idNivel" name="idNivel">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idNivel"
                                    role="alert">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="idMentor" class="form-label fw-bold">Mentor</label>
                                <select class="form-select" id="idMentor" name="idMentor">
                                </select>
                                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idMentor"
                                    role="alert">
                                </div>


                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button" id="cerrarRegistrar" class="btn btn-secondary">Cerrar</button>
                                    <button type="submit" id="submitRE" class="btn btn-primary"></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    <?php endif ?>




    <?php if ($usuario->tienePermiso("grupos", "registrar")): ?>
        <!-- MODAL PARA REGISTRAR GRUPO -->
        <div class="modal fade" id="modal_registroMatricula" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><strong>Matricula</strong></h3>

                    </div>
                    <div class="modal-body">

                        <p id="titulomatricula"><strong></strong>
                        <p>


                        <div class="card">
                            <div class="card-header">

                                <div id="registrarEstudiante">
                                </div>

                            </div>
                            <div class="card-body">

                                <div class="card">
                                    <div class="card-header">
                                        <div class="page-top d-flex align-items-end justify-content-between mb-2">
                                            <h4><strong>Estudiantes</strong></h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="Matricula" class="table table-bordered table-rounded table-hover"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Cedula</th>
                                                        <th>Nombre</th>
                                                        <th class="visually-hidden"></th>
                                                        <th class="visually-hidden"></th>
                                                        <th class="text-center" style="width: 100px;">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- AQUI MOSTRARA LA INFORMACION -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cerrarmatricula"
                            data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    <?php endif ?>


    <?php if ($usuario->tienePermiso("grupos", "registrar")): ?>
        <!-- MODAL PARA REGISTRAR GRUPO -->
        <div class="modal fade" id="modalClases" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="tituloClases"><strong></strong></h3>

                    </div>
                    <div class="modal-body">

                        <p><strong></strong>
                        <p>

                        <div class="card">
                            <div class="card-header">
                                <nav>
                                    <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                                        <div class="nav-link active" data-bs-toggle="" id="clasesNAV" data-bs-target="#"
                                            type="button" role="tab">
                                            Clases
                                        </div>
                                        <div class="nav-link d-none" data-bs-toggle="" id="notasNAV" data-bs-target="#"
                                            type="button" role="tab">
                                            Notas
                                        </div>
                                        <div class="nav-link d-none" data-bs-toggle="" id="infoNAV" data-bs-target="#"
                                            type="button" role="tab">
                                            Info
                                        </div>

                                    </div>
                                </nav>
                            </div>
                            <div class="card-body">

                                <div class="tab-content" id="nav-tabContent2">

                                    <div class="tab-pane fade show active" id="tab-clases" role="tabpanel" tabindex="0">

                                        <p id="idGrupo1" class="visually-hidden"></p>
                                        <p id="idClase" class="visually-hidden"></p>


                                        <form id="formulario2">
                                            <div class="mb-3">

                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">



                                                        <input type="text" class="form-control" id="titulo"
                                                            placeholder="Titulo" name="titulo" maxlength="50"
                                                            aria-describedby="msj_titulo" required>
                                                        <div class="invalid-feedback" id="msj_titulo">
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <input type="number" id="ponderacion" placeholder="Ponderación 0.00"
                                                            class="form-control" step="0.01" min="0"
                                                            aria-describedby="msj_ponderacion">
                                                        <div id="msj_ponderacion" class="invalid-feedback"></div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 ">
                                                    <div class="col-7">

                                                        <textarea class="form-control" id="Objetivo"
                                                            placeholder="Objetivo de la Clase" maxlength="100"></textarea>
                                                        <div id="msj_Objetivo" class="invalid-feedback">
                                                        </div>
                                                    </div>

                                                    <div class="col-5 d-flex justify-content-end align-items-end gap-1">
                                                        <div class="d-flex justify-content-end gap-1">
                                                            <button type="button" id="editarClase"
                                                                class="btn btn-info d-none">editar</button>
                                                            <button type="button" id="cancelar4"
                                                                class="btn btn-secondary d-none">cancelar</button>
                                                            <button type="button" id="registrarClase"
                                                                class="btn btn-primary">Registrar</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="card">
                                            <div class="card-header">
                                                <div class="page-top d-flex align-items-end justify-content-between mb-2">
                                                    <h4><strong>Clases</strong></h4>
                                                    <div class="d-flex gap-3">
                                                        <div class="buscador">
                                                            <input type="text" id="searchModulo" class="form-control"
                                                                placeholder="Buscar Clase">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="ClaseDatatables"
                                                        class="table table-bordered table-rounded table-hover"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>

                                                                <th>Nombre</th>
                                                                <th>ponderacion</th>
                                                                <th class="text-center" style="width: 100px;">Opciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- AQUI MOSTRARA LA INFORMACION -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>





                                    </div>

                                    <div class="tab-pane fade show" id="tab-notas" role="tabpanel" tabindex="0">
                                        <h5 id="cartaNiveles"></h5>

                                        <form id="formulario3">
                                            <div class="mb-3">

                                                <div class="row g-3">
                                                    <div class="col-7">


                                                        <p id="idmodulo1" class="visually-hidden"></p>
                                                        <p id="idnivel" class="visually-hidden"></p>
                                                        <input type="text" class="form-control" id="nombre4"
                                                            placeholder="Nombre del modulo" name="nombre4" maxlength="50"
                                                            aria-describedby="msj_nombre4" required>
                                                        <div class="invalid-feedback" id="msj_nombre4">
                                                        </div>
                                                    </div>

                                                    <div class="col-5">
                                                        <div class="d-flex justify-content-end gap-1">
                                                            <button type="button" id="editarnivel"
                                                                class="btn btn-info d-none">editar</button>
                                                            <button type="button" id="cancelar2"
                                                                class="btn btn-secondary d-none">cancelar</button>
                                                            <button type="button" id="registrarnivel"
                                                                class="btn btn-primary">Registrar</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="card">
                                            <div class="card-header">
                                                <div class="page-top d-flex align-items-end justify-content-between mb-2">
                                                    <h4><strong>Niveles</strong></h4>
                                                    <div class="d-flex gap-3">
                                                        <div class="buscador">
                                                            <input type="text" id="searchNivel" class="form-control"
                                                                placeholder="Buscar Nivel">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="nivelesDatatables"
                                                        class="table table-bordered table-rounded table-hover"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Codigo</th>
                                                                <th>Nombre</th>
                                                                <th class="text-center" style="width: 100px;">Opciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- AQUI MOSTRARA LA INFORMACION -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade show" id="tab-info" role="tabpanel" tabindex="0">
                                        <h5 id="infoClase"></h5>

                                        <ul class="list-group">

                                            <li class="list-group-item "><strong>Ponderacion:</strong>
                                                <p id="inf_ponderacion"></p>
                                            </li>
                                            <li class="list-group-item "><strong>Objetivo:</strong>
                                                <p id="inf_objetivo"></p>
                                            </li>
                                        </ul>

                                   

                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cerrarClases">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    <?php endif ?>


</div>