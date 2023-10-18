<?php
/** @var Usuario $usuario */
$title = "Detalles de Usuario"
?>

<div class="mb-2">
    <a href="javascript:history.back()">Volver</a>
</div>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Detalle de usuario</h4>
</div>

<div class="card" style="max-width: 512px;">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col">
                <h6>Nombre</h6>
                <input type="text" class="form-control" value="<?= $usuario->getNombre() ?>" disabled>
            </div>
            <div class="col">
                <h6>Apellido</h6>
                <input type="text" class="form-control" value="<?= $usuario->getApellido() ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-5">
                <h6>Cedula</h6>
                <input type="text" class="form-control" value="<?= $usuario->getCedula() ?>" disabled>
            </div>
            <div class="col-7">
                <h6>teléfono</h6>
                <input type="text" class="form-control" value="<?= $usuario->getTelefono() ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <h6>Correo</h6>
                <input type="text" class="form-control" value="<?= $usuario->getCorreo() ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <h6>Dirección</h6>
                <textarea class="form-control" disabled><?= $usuario->getDireccion() ?></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <h6>Nacimiento</h6>
                <input type="text" class="form-control" value="<?= $usuario->getFechaNacimiento() ?>" disabled>
            </div>
            <div class="col">
                <h6>Estado civil</h6>
                <input type="text" class="form-control" value="<?= EstadoCivil::tryFrom($usuario->getEstadoCivil())->name ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <h6>Sede</h6>
                <input type="text" class="form-control" value="<?= $usuario->sede->getNombre() ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <h6>Roles</h6>
                <input type="text" class="form-control" value="<?= implode(", ", array_map(fn($rol) => $rol->getNombre(), $usuario->roles)) ?>" disabled>
            </div>
        </div>
    </div>
</div>