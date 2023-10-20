<?php
/** @var Grupo[] $grupos */
$title = "Grupos";
?>

<div class="d-flex align-items-end justify-content-between mb-3">
    <h4 class="mb-0 fw-bold">Grupos</h4>
    <div class="d-flex gap-3">
        <?php if (true/*$usuario->tienePermiso("grupos", "registrar")*/): ?>
            <a href="/AppwebMVC/Grupos/Registrar" class="btn btn-accent text-nowrap">
                <i class="fa-solid fa-plus"></i>
                Nuevo grupo
            </a>
        <?php endif ?>
    </div>
</div>

<div class="card border-0 shadow p-3 mb-4">
    <h5 class="border-bottom pb-2 mb-3">Grupos Activos</h5>
    <div class="row gy-3">
        <?php foreach ($grupos as $grupo): ?>
            <?php if ($grupo->getEstado() == EstadosGrupo::Activo->value) : ?>
                <div class="col-12 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title fw-medium mb-0"><?= $grupo->getNombre() ?></div>
                        </div>
                        <div class="card-body">
                            <b>Nivel:</b> <?= $grupo->subnivel->nivelCrecimiento->getNombre() ?> <br>
                            <b>Profesor:</b> <?= $grupo->profesor->getNombreCompleto() ?> <br>
                            <b>Participantes:</b> <?= count($grupo->estudiantes) ?>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-danger px-2" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                    data-id="<?= $grupo->id ?>">
                                <i class="fa-solid fa-trash fa-fw"></i>
                            </button>
                            <a href="/AppwebMVC/Grupos/Registrar?id=<?= $grupo->id ?>" class="btn btn-primary px-2">
                                <i class="fa-solid fa-pen fa-fw"></i>
                            </a>
                            <a href="/AppwebMVC/Grupos/Gestionar?id=<?= $grupo->id ?>" class="btn btn-secondary px-2">
                                Matricula
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>

<div class="card border-0 shadow p-3">
    <h5 class="border-bottom pb-2 mb-3">Grupos finalizados</h5>
    <div class="row gy-3">
        <?php foreach ($grupos as $grupo): ?>
            <?php if ($grupo->getEstado() == EstadosGrupo::Finalizado->value) : ?>
                <div class="col-12 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title fw-medium mb-0"><?= $grupo->getNombre() ?></div>
                        </div>
                        <div class="card-body">
                            <b>Nivel:</b> <?= $grupo->subnivel->nivelCrecimiento->getNombre() ?> <br>
                            <b>Profesor:</b> <?= $grupo->profesor->getNombreCompleto() ?> <br>
                            <b>Participantes:</b> <?= count($grupo->estudiantes) ?>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-danger px-2" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                    data-id="<?= $grupo->id ?>">
                                <i class="fa-solid fa-trash fa-fw"></i>
                            </button>
                            <a href="/AppwebMVC/Grupos/Registrar?id=<?= $grupo->id ?>" class="btn btn-primary px-2">
                                <i class="fa-solid fa-pen fa-fw"></i>
                            </a>
                            <a href="/AppwebMVC/Grupos/Gestionar?id=<?= $grupo->id ?>" class="btn btn-secondary px-2">
                                Matricula
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>

<!-- Confirmar eliminación -->
<div class="modal fade modal-eliminar" id="confirmar-eliminacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-0" role="alert">
                    ¿Seguro quieres eliminar este grupo?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                <a href="#" data-href="/AppwebMVC/Grupos/Eliminar?id=" type="button" class="btn btn-danger btn-eliminar">Si, eliminar</a>
            </div>
        </div>
    </div>
</div>