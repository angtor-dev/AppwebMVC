<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["grupos.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];


?>

<script>
    const permisosRoles = {
        rolEstudiante: <?php echo $usuario->tieneRol("Estudiante") ? 1 : 0 ?>
    }

    const permisosclases = {
        registrar: <?php echo $usuario->tienePermiso("clases", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("clases", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("clases", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("clases", "eliminar") ? 1 : 0 ?>,
        
    }

    const permisosnotas = {
        registrar: <?php echo $usuario->tienePermiso("notas", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("notas", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("notas", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("notas", "eliminar") ? 1 : 0 ?>,
    }

    const permisosgrupos = {
        registrar: <?php echo $usuario->tienePermiso("grupos", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("grupos", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("grupos", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("grupos", "eliminar") ? 1 : 0 ?>,
    }

    const permisosestudiantes = {
        registrar: <?php echo $usuario->tienePermiso("estudiantes", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("estudiantes", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("estudiantes", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("estudiantes", "eliminar") ? 1 : 0 ?>,
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
        <?php if ($usuario->tienePermiso("grupos", "consultar")): ?>

            <div class="card">
                <div class="card-header">
                    <nav>

                        <?php  if ($usuario->tienePermiso("grupos", "registrar") || $usuario->tieneRol("Mentor")): ?>
                        <div class="nav nav-tabs card-header-tabs" id="nav-tabGrupos" role="tablist">
                            <button class="nav-link active" data-bs-toggle="tab" id="activo"
                                data-bs-target="#tab-gruposActivos" type="button" role="tab" aria-selected="true">
                                Activos
                            </button>
                            <?php endif ?>

                            <?php  if ($usuario->tienePermiso("grupos", "registrar")): ?>
                            <button class="nav-link" data-bs-toggle="tab" id="abierto" data-bs-target="#tab-gruposActivos"
                                type="button" role="tab" aria-selected="false">
                                Abiertos
                            </button>
                            <button class="nav-link" data-bs-toggle="tab" id="cerrado" data-bs-target="#tab-gruposActivos"
                                type="button" role="tab" aria-selected="false">
                                Cerrados
                            </button>
                            <?php endif ?>

                            <?php  if ($usuario->tieneRol("Estudiante")): ?>
                            <button class="nav-link" data-bs-toggle="tab" id="misGrupos" data-bs-target="#tab-gruposActivos"
                                type="button" role="tab" aria-selected="false">
                                Mis Grupos
                            </button>
                            <?php endif ?>
                         


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
                                                <th>Fecha de inicio</th>
                                                <th>Fecha fin</th>
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




   


<?php if ($usuario->tienePermiso("grupos", "consultar")): ?>
    <!-- MODAL CLASES-->
    <div class="modal fade" id="modalClases" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
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


                                    <div id="registrarClases" class="mb-3"></div>

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
                                                            <th class="text-center" style="width: 300px;">Opciones</th>
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



                                    <div class="card">
                                        <div class="card-header">
                                            <div class="page-top d-flex align-items-end justify-content-between mb-2">
                                                <h4 id="infoClase2"><strong></strong></h4>
                                                <div class="d-flex gap-3">
                                                    <div class="buscador">
                                                        <input type="text" id="searchNivel" class="form-control"
                                                            placeholder="Buscar Estudiante">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p id="idClase1" class="visually-hidden"></p>

                                            <p id="tituloNotas">
                                            <p>
                                                <input type="number" id="ponderacionClases" class="visually-hidden">

                                            <div class="table-responsive">
                                                <table id="notasdatatble"
                                                    class="table table-bordered table-rounded table-hover"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>cedula</th>
                                                            <th>Nombres</th>
                                                            <th class="gap-2" style="width: 250px;">Calificación</th>

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


<?php if ($usuario->tienePermiso("grupos", "consultar")): ?>
    <!-- MODAL MATRICULA-->
    <div class="modal fade" id="modal_registroMatricula" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 id="titulomatricula" class="modal-title"><strong></strong></h5>
           
                </div> 
                <div class="modal-body">

                        <div id="registrarEstudiante" class="mb-3">
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <nav>
                                    <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                                        <div class="nav-link active" data-bs-toggle="" id="MatriculaNAV" data-bs-target="#"
                                            type="button" role="tab">
                                            Matricula
                                        </div>
                                        <div class="nav-link d-none" data-bs-toggle="" id="EstudianteNAV" data-bs-target="#"
                                            type="button" role="tab">

                                        </div>

                                    </div>
                                </nav>
                            </div>
                            <div class="card-body">

                                <div class="tab-content" id="nav-tabContent3">
                                    <div class="tab-pane fade show active" id="tab-Matricula" role="tabpanel" tabindex="0">

                                    <div class="table-responsive">
                                    <table id="Matricula" class="table table-bordered table-rounded table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cedula</th>
                                                <th>Nombre</th>
                                                <th>Nota Total</th>
                                                <th>Nota Acumulada</th>
                                                <th>Estado</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- AQUI MOSTRARA LA INFORMACION -->
                                        </tbody>
                                    </table>
                                    </div>

                                    </div>

                                    <div class="tab-pane fade show" id="tab-Estudiante" role="tabpanel" tabindex="0">

                                    <p id="nombreEstudiante2" class="mb-3"></p>
                                    <p id="notaAcumuladaEstudiante" class="mb-3"></p>

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

                                    </div>
                                </div>
                            </div>
                        </div>


                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cerrarmatricula">Cerrar</button>
                </div>
            </div>
        </div>
</div>
<?php endif ?>

<?php if ($usuario->tienePermiso("grupos", "consultar")): ?>
    <div class="modal fade" id="contenidoModal" aria-hidden="true" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Contenido</h5>
                </div>
                <div class="modal-body">
                    <div id="contenido" class="w-100"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-target="#modalClases" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Atras</button>
                    <button id="agregarContenido" class="btn btn-primary">Agregar contenido</button>
                    <button id="guardarContenido" class="btn btn-primary">Guardar</button>
                    <button id="editarContenido" class="btn btn-primary">Editar contenido</button>
                    <button id="cancelarContenido" class="btn btn-secondary">Cancelar</button>
                    <button class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


</div>
