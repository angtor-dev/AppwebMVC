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
                    <form action="/AppwebMVC/Seguridad/Roles/ActualizarPermisos" method="post" id="formPermisos">
                        <input type="hidden" name="id" id="idPermisos" value="<?= $rol->idPermisos ?>">
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
                                        <input type="checkbox" name="consultarUsuarios" value="true"
                                            <?= $rol->permisos->consultarUsuarios ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarUsuarios" value="true"
                                            <?= $rol->permisos->registrarUsuarios ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarUsuarios" value="true"
                                            <?= $rol->permisos->actualizarUsuarios ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarUsuarios" value="true"
                                            <?= $rol->permisos->eliminarUsuarios ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Seguridad</h5>
                                <h6>Roles y permisos</h6>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultarRoles" value="true"
                                            <?= $rol->permisos->consultarRoles ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarRoles" value="true"
                                            <?= $rol->permisos->registrarRoles ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarRoles" value="true"
                                            <?= $rol->permisos->actualizarRoles ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarRoles" value="true"
                                            <?= $rol->permisos->eliminarRoles ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="gestionarPermisos" value="true"
                                            <?= $rol->permisos->gestionarPermisos ? "checked" : null ?>>
                                        Gestionar permisos
                                    </label>
                                </div>
                                <h6>Bitacora</h6>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultarBitacora" value="true"
                                            <?= $rol->permisos->consultarBitacora ? "checked" : null ?>>
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
            <button class="btn btn-primary" form="formPermisos">Guardar</button>
        </div>
    </div>
</div>