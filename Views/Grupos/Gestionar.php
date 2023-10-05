<?php
/** @var Grupo $grupo */
/** @var Usuario[] $estudiantes */
/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<div class="d-flex justify-content-between mb-3">
    <a href="/AppwebMVC/Grupos" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
    <?php if ($usuario->tienePermiso("inscripciones", "registrar")): ?>
        <button class="btn btn-accent">
            <i class="fa-solid fa-plus"></i>
            Agregar estudiante
        </button>
    <?php endif ?>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="d-flex gap-2">
            <div class="d-flex flex-column pe-2 border-end">
                <span>Grupo</span>
                <h5 class="mb-0"><?= $grupo->getNombre() ?></h5>
            </div>
            <div class="d-flex flex-column">
                <span>Nivel</span>
                <h5 class="mb-0"><?= $grupo->subnivel->nivelCrecimiento->getNombre() ?></h5>
            </div>
        </div>
        <div class="d-flex flex-column text-end">
            <span>Estado</span>
            <h5 class="mb-0"><?= EstadosGrupo::tryFrom($grupo->getEstado())->name ?></h5>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (count($grupo->estudiantes) > 0): ?>
            <table class="table table-bordered table-rounded mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Correo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grupo->estudiantes as $estudiante): ?>
                        <td><?= $estudiante->getNombreCompleto() ?></td>
                        <td><?= $estudiante->getCedula() ?></td>
                        <td><?= $estudiante->getCorreo() ?></td>
                        <td class="acciones">
                            <a role="button">
                                <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                            </a>
                            <?php if ($usuario->tienePermiso("grupos", "actualizar")): ?>
                                <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                    data-id="<?= $estudiante->id ?>">
                                    <i class="fa-solid fa-trash" title="Eliminar" data-bs-toggle="tooltip"></i>
                                </a>
                            <?php endif ?>
                        </td>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="p-3 text-center">
                <h5 class="mb-0">¡Este grupo aun no tiene estudiantes!</h5>
            </div>
        <?php endif ?>
    </div>
</div>