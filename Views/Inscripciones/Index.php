<?php
/** @var Array<Usuario> $estudiantes */
$title = "Inscripciones";
/** @var Usuario */
$usuarioSesion = $_SESSION['usuario'];
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Estudiantes</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-estudiantes_search" class="form-control" placeholder="Buscar estudiante">
        </div>
        <?php if ($usuarioSesion->tienePermiso("inscripciones", "registrar")): ?>
            <button class="btn btn-accent text-nowrap" data-bs-toggle="modal" data-bs-target="#modal-cedula">
                <i class="fa-solid fa-plus"></i>
                Nueva inscripción
            </button>
        <?php endif ?>
    </div>
</div>

<table class="table table-bordered table-rounded table-hover datatable" id="tabla-estudiantes">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cedula</th>
            <th>Correo</th>
            <th>Grupo</th>
            <th>Nivel</th>
            <th class="text-center" style="width: 90px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($estudiantes as $estudiante) : ?>
            <tr>
                <td><?= $estudiante->getNombreCompleto() ?></td>
                <td><?= $estudiante->getCedula() ?></td>
                <td><?= $estudiante->getCorreo() ?></td>
                <td>
                    <?php $grupoActivo = $estudiante->getGrupoActivo() ?>
                    <?= is_null($grupoActivo) ? "<em>Sin grupo activo</em>" : $grupoActivo->getNombre() ?>
                </td>
                <td><?= is_null($grupoActivo) ? "" : $grupoActivo->subnivel->nivelCrecimiento->getNombre() ?></td>
                <td>
                    <div class="acciones">
                        <?php if ($usuarioSesion->tienePermiso("usuarios", "consultar")): ?>
                            <a href="/AppwebMVC/Usuarios/Detalles?id=<?= $estudiante->id ?>">
                                <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                            </a>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Confirmar eliminación -->
<div class="modal fade modal-eliminar" id="confirmar-eliminacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-0" role="alert">
                    ¿Seguro quieres eliminar este estudiante?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                <a href="#" data-href="/AppwebMVC/Inscripciones/Eliminar?id=" type="button" class="btn btn-danger btn-eliminar">Si, eliminar</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-cedula">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Inscripción de estudiante</h4>
            </div>
            <div class="modal-body">
                <h5>Ingresa la cedula del estudiante</h5>
                <form action="/AppwebMVC/Inscripciones/ValidarEstudiante" method="post" id="form-buscar-cedula">
                    <input type="text" name="cedula" id="buscar-cedula" class="form-control" placeholder="28123456">
                </form>
                <div class="msgBox"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" form="form-buscar-cedula" class="btn btn-primary">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Registrar o actualizar estudiante -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-estudiante">
    <!-- Contenido cargado desde ajax -->
</div>

<?php agregarScript("inscripciones.js"); ?>