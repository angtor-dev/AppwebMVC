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
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title fw-medium mb-0"><?= $grupo->nombre ?></div>
                    </div>
                    <div class="card-body">
                        <b>Nivel:</b> <?= $grupo->nivelCrecimiento->nombre ?> <br>
                        <b>Profesor:</b> <?= $grupo->profesor->getNombreCompleto() ?> <br>
                        <b>Participantes:</b> 0
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-sm btn-secondary">Estudiantes</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                        <button class="btn btn-sm btn-primary">Gestionar</button>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<div class="card border-0 shadow p-3">
    <h5 class="border-bottom pb-2 mb-3">Grupos finalizados</h5>
    <div class="row gy-3">
    </div>
</div>