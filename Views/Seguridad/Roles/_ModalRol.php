<?php /** @var Rol $rol */ ?>

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title"> <?= empty($rol->id) ? "Crear rol nuevo" : "Actualizar rol" ?> </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body text-secondary">
    <form action="/AppwebMVC/Seguridad/Roles/<?= empty($rol->id) ? "Registrar" : "Actualizar" ?>" method="post" id="form-rol" class="row g-3 needs-validation" novalidate>
        <input type="hidden" name="id" id="input-id" value="<?= $rol->id ?? "0" ?>">
        <div class="col-sm-12">
            <label for="input-nombre">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="input-nombre" required maxlength="30"
                value="<?= $rol->getNombre() ?>">
            <div class="invalid-feedback">
                Ingresa un nombre válido
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-descripcion">Descripción</label>
            <input class="form-control" type="text" name="descripcion" id="input-descripcion" required maxlength="100"
                value="<?= $rol->getDescripcion() ?>">
            <div class="invalid-feedback">
                Ingresa una descripcion válido
            </div>
        </div>
        <div class="col-sm-6">
            <label for="input-nivel">Nivel de rol</label>
            <input class="form-control" type="number" name="nivel" id="input-nivel" required min="0" max="999"
                value="<?= $rol->getNivel() ?? "" ?>">
            <div class="invalid-feedback">
                Cédula inválida
            </div>
        </div>
    </form>
</div>
<div class="p-3 border-top">
    <button type="submit" class="btn btn-accent w-100" form="form-rol">Guardar</button>
</div>