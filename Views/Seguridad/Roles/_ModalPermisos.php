<?php
/** @var Rol $rol */
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Permisos del rol <?= $rol->nombre ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-header">
                    <nav>
                        <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-iglesia" type="button" role="tab" aria-selected="true">
                                Iglesia
                            </button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-celulas" type="button" role="tab" aria-selected="false">
                                Células
                            </button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-escuela" type="button" role="tab" aria-selected="false">
                                Escuela
                            </button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-sistema" type="button" role="tab" aria-selected="false">
                                Sistema
                            </button>
                        </div>
                    </nav>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="formPermisos">
                        <div class="tab-content" id="nav-tabContent">
                            <!-- Permisos de Iglesia -->
                            <div class="tab-pane fade show active" id="tab-iglesia" role="tabpanel" tabindex="0">
                                Permisos de iglesia aqui
                            </div>
                            <!-- Permisos de Celulas -->
                            <div class="tab-pane fade" id="tab-celulas" role="tabpanel" tabindex="0">
                                Permisos de celulas aqui
                            </div>
                            <!-- Permisos de Escuela -->
                            <div class="tab-pane fade" id="tab-escuela" role="tabpanel" tabindex="0">
                                Permisos de escuela aqui
                            </div>
                            <!-- Permisos del Sistema -->
                            <div class="tab-pane fade" id="tab-sistema" role="tabpanel" tabindex="0">
                                <h5>Usuarios</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultaUsuarios" value="true"
                                            <?= $rol->permisos->consultaUsuarios ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registraUsuarios" value="true"
                                            <?= $rol->permisos->registraUsuarios ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizaUsuarios" value="true"
                                            <?= $rol->permisos->actualizaUsuarios ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminaUsuarios" value="true"
                                            <?= $rol->permisos->eliminaUsuarios ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Seguridad</h5>
                                <h6>Roles y permisos</h6>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultaRoles" value="true">
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registraRoles" value="true">
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizaRoles" value="true">
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminaRoles" value="true">
                                        Eliminar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="gestionaPermisos" value="true">
                                        Gestionar permisos
                                    </label>
                                </div>
                                <h6>Bitacora</h6>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultaBitacora" value="true">
                                        Consultar bitacora
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </div>
</div>