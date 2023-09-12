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
                                <h5>Sedes</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="listarSede" value="true"
                                            <?= $rol->permisos->listarSede ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarSede" value="true"
                                            <?= $rol->permisos->registrarSede ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarSede" value="true"
                                            <?= $rol->permisos->actualizarSede ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarSede" value="true"
                                            <?= $rol->permisos->eliminarSede ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="cambiarSede" value="true"
                                            <?= $rol->permisos->cambiarSede ? "checked" : null ?>>
                                        Cambiar entre sedes
                                    </label>
                                </div>
                                <h5>Territorios</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="listarTerritorio" value="true"
                                            <?= $rol->permisos->listarTerritorio ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarTerritorio" value="true"
                                            <?= $rol->permisos->registrarTerritorio ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarTerritorio" value="true"
                                            <?= $rol->permisos->actualizarTerritorio ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarTerritorio" value="true"
                                            <?= $rol->permisos->eliminarTerritorio ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Agenda</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultarEventos" value="true"
                                            <?= $rol->permisos->consultarEventos ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarEventos" value="true"
                                            <?= $rol->permisos->registrarEventos ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarEventos" value="true"
                                            <?= $rol->permisos->actualizarEventos ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarEventos" value="true"
                                            <?= $rol->permisos->eliminarEventos ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                            </div>
                            <!-- Permisos de Celulas -->
                            <div class="tab-pane fade" id="tab-celulas" role="tabpanel" tabindex="0">
                                <h5>Células de Consolidación</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="listarCelulaConsolidacion" value="true"
                                            <?= $rol->permisos->listarCelulaConsolidacion ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarCelulaConsolidacion" value="true"
                                            <?= $rol->permisos->registrarCelulaConsolidacion ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarCelulaConsolidacion" value="true"
                                            <?= $rol->permisos->actualizarCelulaConsolidacion ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarCelulaConsolidacion" value="true"
                                            <?= $rol->permisos->eliminarCelulaConsolidacion ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Células de Crecimiento</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="listarCelulaCrecimiento" value="true"
                                            <?= $rol->permisos->listarCelulaCrecimiento ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarCelulaCrecimiento" value="true"
                                            <?= $rol->permisos->registrarCelulaCrecimiento ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarCelulaCrecimiento" value="true"
                                            <?= $rol->permisos->actualizarCelulaCrecimiento ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarCelulaCrecimiento" value="true"
                                            <?= $rol->permisos->eliminarCelulaCrecimiento ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Células Familiares</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="listarCelulaFamiliar" value="true"
                                            <?= $rol->permisos->listarCelulaFamiliar ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarCelulaFamiliar" value="true"
                                            <?= $rol->permisos->registrarCelulaFamiliar ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarCelulaFamiliar" value="true"
                                            <?= $rol->permisos->actualizarCelulaFamiliar ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarCelulaFamiliar" value="true"
                                            <?= $rol->permisos->eliminarCelulaFamiliar ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                            </div>
                            <!-- Permisos de Escuela -->
                            <div class="tab-pane fade" id="tab-escuela" role="tabpanel" tabindex="0">
                                <h5>Niveles de Crecimiento</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultarNivelesCrecimiento" value="true"
                                            <?= $rol->permisos->consultarNivelesCrecimiento ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="registrarNivelesCrecimiento" value="true"
                                            <?= $rol->permisos->registrarNivelesCrecimiento ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="actualizarNivelesCrecimiento" value="true"
                                            <?= $rol->permisos->actualizarNivelesCrecimiento ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="eliminarNivelesCrecimiento" value="true"
                                            <?= $rol->permisos->eliminarNivelesCrecimiento ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Notas</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="consultarNotas" value="true"
                                            <?= $rol->permisos->consultarNotas ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="gestionarNotas" value="true"
                                            <?= $rol->permisos->gestionarNotas ? "checked" : null ?>>
                                        Gestionar
                                    </label>
                                </div>
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