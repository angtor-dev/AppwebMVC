<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["escuela.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("eid", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("eid", "consultar") ? 1 : 0 ?>,
        actualizar: <?php echo $usuario->tienePermiso("eid", "actualizar") ? 1 : 0 ?>,
        eliminar: <?php echo $usuario->tienePermiso("eid", "eliminar") ? 1 : 0 ?>
    }
</script>

<div class="container-fluid">

    <div class="page-top d-flex align-items-end justify-content-between mb-2">
        <h2><strong>Escuela de Impulso y Desarrollo</strong></h2>
        <div class="d-flex gap-3">
            <div class="buscador">
                <input type="text" id="search" class="form-control" placeholder="Buscar E.I.D">
            </div>
            <?php if ($usuario->tienePermiso("eid", "registrar")): ?>
                <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal"
                    data-bs-target="#modal_registrar">
                    <i class="fa-solid fa-plus"></i>
                    Nueva EID
                </button>
            <?php endif ?>
        </div>
    </div>


    <div class="table-responsive">
        <table id="eidDatatables" class="table table-bordered table-rounded table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Modulos</th>
                    <th class="text-center" style="width: 100px;">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- AQUI MOSTRARA LA INFORMACION -->
            </tbody>
        </table>
    </div>





    <?php if ($usuario->tienePermiso("eid", "registrar")): ?>
        <!-- MODAL PARA REGISTRAR EID -->

        <div class="modal fade" id="modal_registrar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar E.I.D</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formulario">

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Nombre</label>
                                <input type="text" class="form-control" id="nombre" maxlength="30" name="nombre"
                                    aria-describedby="msj_nombre">

                                <div class="invalid-feedback" id="msj_nombre" role="alert">

                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="idEid" class="form-label fw-bold">E.I.D requerida para cursar</label>
                                <select multiple class="form-select" id="idEid" name="idEid">
                                </select>


                            </div>



                            <div class="mb-3">
                                <label for="idRolR" class="form-label fw-bold">Roles requeridos para cursar</label>
                                <select multiple class="form-select" id="idRolR" name="idRolR">
                                </select>


                            </div>

                            <div class="mb-3">
                                <label for="idRolA" class="form-label fw-bold">Roles adquiridos al aprobar</label>
                                <select multiple class="form-select" id="idRolA" name="idRolA">
                                </select>


                            </div>

                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" id="cerrarRegistrar" class="btn btn-secondary">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    <?php endif ?>


    <?php if ($usuario->tienePermiso("eid", "actualizar")): ?>
        <!-- MODAL PARA EDITAR NOMBRE EID -->

        <div class="modal fade" id="modal_editar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar nombre EID</h5>
                    </div>
                    <div class="modal-body">


                        <form id="formulario2">
                            <div class="mb-3">

                                <input type="text" class="form-control" id="nombre2" name="nombre2" maxlength="50"
                                    aria-describedby="msj_nombre2" required>
                                <div class="invalid-feedback" id="msj_nombre2">
                                </div>
                            </div>
                            <p id="idEid3" class="visually-hidden"></p>


                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" id="cerrarEditar" class="btn btn-secondary">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    <?php endif ?>

    <?php if ($usuario->tienePermiso("eid", "actualizar")): ?>
        <div class="modal fade" id="modal_requisitos" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">requisitos</h5>
                    </div>
                    <div class="modal-body">

                        <div class="card">
                            <div class="card-header">
                                <nav>
                                    <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-eid"
                                            type="button" role="tab" aria-selected="true">
                                            Eid requeridas
                                        </button>
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rolr"
                                            type="button" role="tab" aria-selected="false">
                                            roles requeridos
                                        </button>
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rola"
                                            type="button" role="tab" aria-selected="false">
                                            roles adquiridos
                                        </button>

                                    </div>
                                </nav>
                            </div>
                            <div class="card-body">
                                <p id="idEid2" class="visually-hidden"></p>
                                <div class="tab-content" id="nav-tabContent">
                                    <!-- eid requeridas-->
                                    <div class="tab-pane fade show active" id="tab-eid" role="tabpanel" tabindex="0">
                                        <h5>EID requeridas para cursar vinculadas</h5>

                                        <div class="mb-3">

                                            <div class="table-responsive">
                                                <table id="eidVDatatables" class="table" style="width:100%">

                                                    <thead>
                                                        <tr>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AQUI MOSTRARA LA INFORMACION -->
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="eid_sin_agregar" class="form-label fw-bold">Agregar</label>
                                            <div class="d-grid">
                                                <select multiple class="form-select" id="eid_sin_agregar"
                                                    name="eid_sin_agregar">
                                                </select>
                                                <button type="button" id="agregareids"
                                                    class="btn btn-light border border-black">Agregar</button>

                                            </div>
                                        </div>



                                    </div>
                                    <!-- Roles requeridos -->
                                    <div class="tab-pane fade show" id="tab-rolr" role="tabpanel" tabindex="0">
                                        <h5>Roles requeridos para cursar vinculados</h5>

                                        <div class="mb-3">

                                            <div class="table-responsive">
                                                <table id="rolesRDatatables" class="table" style="width:100%">

                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AQUI MOSTRARA LA INFORMACION -->
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="rolesR_sin_agregar" class="form-label fw-bold">Agregar</label>
                                            <div class="d-grid">
                                                <select multiple class="form-select" id="rolesR_sin_agregar"
                                                    name="rolesR_sin_agregar">
                                                </select>
                                                <button type="button" id="agregarrolesR"
                                                    class="btn btn-light border border-black">Agregar</button>

                                            </div>
                                        </div>


                                    </div>
                                    <!-- Roles adquiridos -->
                                    <div class="tab-pane fade show" id="tab-rola" role="tabpanel" tabindex="0">
                                        <h5>Roles adquiridos por cursar vinculados</h5>
                                        <div class="mb-3">

                                            <div class="table-responsive">
                                                <table id="rolesADatatables" class="table" style="width:100%">

                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AQUI MOSTRARA LA INFORMACION -->
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="rolesA_sin_agregar" class="form-label fw-bold">Agregar</label>
                                            <div class="d-grid">
                                                <select multiple class="form-select" id="rolesA_sin_agregar"
                                                    name="rolesA_sin_agregar">
                                                </select>
                                                <button type="button" id="agregarrolesA"
                                                    class="btn btn-light border border-black">Agregar</button>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        <?php endif ?>

    </div>

    <!-- Modal para modulos y niveles -->
    <?php if ($usuario->tienePermiso("eid", "registrar")): ?>
        <div class="modal fade" id="modal_modulos" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><h2 id="cartaEid"></h2></h5>
                    </div>
                    <div class="modal-body">

                        <div class="card">
                            <div class="card-header">
                                <nav>
                                    <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                                        <div class="nav-link active" data-bs-toggle="" id="aja1" data-bs-target="#"
                                            type="button" role="tab">
                                            Modulos
                                        </div>
                                        <div class="nav-link d-none" data-bs-toggle="" id="aja2" data-bs-target="#"
                                            type="button" role="tab">
                                            Niveles
                                        </div>


                                    </div>
                                </nav>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="nav-tabContent2">

                                    <div class="tab-pane fade show active" id="tab-eidlist" role="tabpanel" tabindex="0">
                                        



                                        <form id="formulario3">
                                            <div class="mb-3">
                                                
                                                <div class="row g-3">
                                                    <div class="col-7">


                                                        <p id="idEid4" class="visually-hidden"></p>
                                                        <p id="idmodulo" class="visually-hidden"></p>
                                                        <input type="text" class="form-control" id="nombre3"
                                                            placeholder="Nombre del modulo" name="nombre3" maxlength="50"
                                                            aria-describedby="msj_nombre3" required>
                                                        <div class="invalid-feedback" id="msj_nombre3">
                                                        </div>
                                                    </div>

                                                    <div class="col-5">
                                                        <div class="d-flex justify-content-end gap-1">
                                                            <button type="button" id="editarmodulo"
                                                                class="btn btn-info d-none">editar</button>
                                                                <button type="button" id="cancelar"
                                                                class="btn btn-secondary d-none">cancelar</button>
                                                            <button type="button" id="registrarmodulo"
                                                                class="btn btn-primary">Registrar</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="card">
                                            <div class="card-header">
                                                <div class="page-top d-flex align-items-end justify-content-between mb-2">
                                                    <h4><strong>Modulos</strong></h4>
                                                    <div class="d-flex gap-3">
                                                        <div class="buscador">
                                                            <input type="text" id="searchModulo" class="form-control"
                                                                placeholder="Buscar Modulo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="moduloDatatables"
                                                        class="table table-bordered table-rounded table-hover"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Codigo</th>
                                                                <th>Nombre</th>
                                                                <th>Niveles</th>
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

                                    <div class="tab-pane fade show" id="tab-niveles" role="tabpanel" tabindex="0">
                                        <h5 id="cartaNiveles"></h5>

                                        <form id="formulario4">
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


                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" id="cerrarmodulos" data-bs-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        <?php endif ?>

    </div>

</div>