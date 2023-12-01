<?php
/** @var Rol $rol */
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Permisos del rol <?= $rol->getNombre() ?></h5>
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
                        <input type="hidden" name="idRol" value="<?= $rol->id ?>">
                        <div class="tab-content" id="nav-tabContent">
                            <!-- Permisos de Iglesia -->
                            <div class="tab-pane fade show active" id="tab-iglesia" role="tabpanel" tabindex="0">
                                <h5>Sedes</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="sedes[consultar]" value="true"
                                            <?= $rol->tienePermiso("sedes", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="sedes[registrar]" value="true"
                                            <?= $rol->tienePermiso("sedes", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="sedes[actualizar]" value="true"
                                            <?= $rol->tienePermiso("sedes", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="sedes[eliminar]" value="true"
                                            <?= $rol->tienePermiso("sedes", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                    <!-- <label class="pill-check">
                                        <input type="checkbox" name="cambiarSede" value="true"
                                            <?= $rol->getNombre() == "Superusuario" ? "checked" : null ?>>
                                        Cambiar entre sedes
                                    </label> -->
                                </div>
                                <h5>Territorios</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="territorios[consultar]" value="true"
                                            <?= $rol->tienePermiso("territorios", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="territorios[registrar]" value="true"
                                            <?= $rol->tienePermiso("territorios", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="territorios[actualizar]" value="true"
                                            <?= $rol->tienePermiso("territorios", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="territorios[eliminar]" value="true"
                                            <?= $rol->tienePermiso("territorios", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Agenda de Apostol</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaApostol[consultar]" value="true"
                                            <?= $rol->tienePermiso("agendaApostol", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaApostol[registrar]" value="true"
                                            <?= $rol->tienePermiso("agendaApostol", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaApostol[actualizar]" value="true"
                                            <?= $rol->tienePermiso("agendaApostol", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaApostol[eliminar]" value="true"
                                            <?= $rol->tienePermiso("agendaApostol", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Agenda de Pastor</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaPastor[consultar]" value="true"
                                            <?= $rol->tienePermiso("agendaPastor", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaPastor[registrar]" value="true"
                                            <?= $rol->tienePermiso("agendaPastor", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaPastor[actualizar]" value="true"
                                            <?= $rol->tienePermiso("agendaPastor", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaPastor[eliminar]" value="true"
                                            <?= $rol->tienePermiso("agendaPastor", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Agenda de Usuario</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaUsuario[consultar]" value="true"
                                            <?= $rol->tienePermiso("agendaUsuario", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaUsuario[registrar]" value="true"
                                            <?= $rol->tienePermiso("agendaUsuario", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaUsuario[actualizar]" value="true"
                                            <?= $rol->tienePermiso("agendaUsuario", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="agendaUsuario[eliminar]" value="true"
                                            <?= $rol->tienePermiso("agendaUsuario", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                            </div>
                            <!-- Permisos de Celulas -->
                            <div class="tab-pane fade" id="tab-celulas" role="tabpanel" tabindex="0">
                                <h5>Células de Consolidación</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaConsolidacion[consultar]" value="true"
                                            <?= $rol->tienePermiso("celulaConsolidacion", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaConsolidacion[registrar]" value="true"
                                            <?= $rol->tienePermiso("celulaConsolidacion", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaConsolidacion[actualizar]" value="true"
                                            <?= $rol->tienePermiso("celulaConsolidacion", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaConsolidacion[eliminar]" value="true"
                                            <?= $rol->tienePermiso("celulaConsolidacion", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Células de Crecimiento</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaCrecimiento[consultar]" value="true"
                                            <?= $rol->tienePermiso("celulaCrecimiento", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaCrecimiento[registrar]" value="true"
                                            <?= $rol->tienePermiso("celulaCrecimiento", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaCrecimiento[actualizar]" value="true"
                                            <?= $rol->tienePermiso("celulaCrecimiento", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaCrecimiento[eliminar]" value="true"
                                            <?= $rol->tienePermiso("celulaCrecimiento", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Células Familiares</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaFamiliar[consultar]" value="true"
                                            <?= $rol->tienePermiso("celulaFamiliar", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaFamiliar[registrar]" value="true"
                                            <?= $rol->tienePermiso("celulaFamiliar", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaFamiliar[actualizar]" value="true"
                                            <?= $rol->tienePermiso("celulaFamiliar", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="celulaFamiliar[eliminar]" value="true"
                                            <?= $rol->tienePermiso("celulaFamiliar", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Discipulos</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="discipulos[consultar]" value="true"
                                            <?= $rol->tienePermiso("discipulos", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="discipulos[registrar]" value="true"
                                            <?= $rol->tienePermiso("discipulos", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="discipulos[actualizar]" value="true"
                                            <?= $rol->tienePermiso("discipulos", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="discipulos[eliminar]" value="true"
                                            <?= $rol->tienePermiso("discipulos", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                            </div>
                            <!-- Permisos de Escuela -->
                            <div class="tab-pane fade" id="tab-escuela" role="tabpanel" tabindex="0">
                                <h5>Niveles de Crecimiento</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="nivelesCrecimiento[consultar]" value="true"
                                            <?= $rol->tienePermiso("nivelesCrecimiento", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="nivelesCrecimiento[registrar]" value="true"
                                            <?= $rol->tienePermiso("nivelesCrecimiento", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="nivelesCrecimiento[actualizar]" value="true"
                                            <?= $rol->tienePermiso("nivelesCrecimiento", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="nivelesCrecimiento[eliminar]" value="true"
                                            <?= $rol->tienePermiso("nivelesCrecimiento", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Grupos</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="grupos[consultar]" value="true"
                                            <?= $rol->tienePermiso("grupos", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="grupos[registrar]" value="true"
                                            <?= $rol->tienePermiso("grupos", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="grupos[actualizar]" value="true"
                                            <?= $rol->tienePermiso("grupos", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="grupos[eliminar]" value="true"
                                            <?= $rol->tienePermiso("grupos", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Clases</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="clases[consultar]" value="true"
                                            <?= $rol->tienePermiso("clases", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="clases[registrar]" value="true"
                                            <?= $rol->tienePermiso("clases", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="clases[actualizar]" value="true"
                                            <?= $rol->tienePermiso("clases", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="clases[eliminar]" value="true"
                                            <?= $rol->tienePermiso("clases", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Inscripciones</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="inscripciones[consultar]" value="true"
                                            <?= $rol->tienePermiso("inscripciones", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="inscripciones[registrar]" value="true"
                                            <?= $rol->tienePermiso("inscripciones", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="inscripciones[actualizar]" value="true"
                                            <?= $rol->tienePermiso("inscripciones", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="inscripciones[eliminar]" value="true"
                                            <?= $rol->tienePermiso("inscripciones", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Notas</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="notas[consultar]" value="true"
                                            <?= $rol->tienePermiso("notas", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="notas[registrar]" value="true"
                                            <?= $rol->tienePermiso("notas", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="notas[actualizar]" value="true"
                                            <?= $rol->tienePermiso("notas", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="notas[eliminar]" value="true"
                                            <?= $rol->tienePermiso("notas", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                            </div>
                            <!-- Permisos del Sistema -->
                            <div class="tab-pane fade" id="tab-sistema" role="tabpanel" tabindex="0">
                                <h5>Usuarios</h5>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="usuarios[consultar]" value="true"
                                            <?= $rol->tienePermiso("usuarios", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="usuarios[registrar]" value="true"
                                            <?= $rol->tienePermiso("usuarios", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="usuarios[actualizar]" value="true"
                                            <?= $rol->tienePermiso("usuarios", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="usuarios[eliminar]" value="true"
                                            <?= $rol->tienePermiso("usuarios", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                </div>
                                <h5>Seguridad</h5>
                                <h6>Roles y permisos</h6>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="roles[consultar]" value="true"
                                            <?= $rol->tienePermiso("roles", "consultar") ? "checked" : null ?>>
                                        Consultar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="roles[registrar]" value="true"
                                            <?= $rol->tienePermiso("roles", "registrar") ? "checked" : null ?>>
                                        Registrar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="roles[actualizar]" value="true"
                                            <?= $rol->tienePermiso("roles", "actualizar") ? "checked" : null ?>>
                                        Actualizar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="roles[eliminar]" value="true"
                                            <?= $rol->tienePermiso("roles", "eliminar") ? "checked" : null ?>>
                                        Eliminar
                                    </label>
                                    <label class="pill-check">
                                        <input type="checkbox" name="permisos[actualizar]" value="true"
                                            <?= $rol->tienePermiso("permisos", "actualizar") ? "checked" : null ?>>
                                        Gestionar permisos
                                    </label>
                                </div>
                                <h6>Bitacora</h6>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <label class="pill-check">
                                        <input type="checkbox" name="bitacora[consultar]" value="true"
                                            <?= $rol->tienePermiso("bitacora", "consultar") ? "checked" : null ?>>
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