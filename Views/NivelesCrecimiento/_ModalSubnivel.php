<?php /** @var Subnivel $subnivel */ ?>

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title"> <?= empty($subnivel->id) ? "Crear subnivel nueva" : "Actualizar subnivel" ?> </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body text-secondary">
    <form action="/AppwebMVC/NivelesCrecimiento/Subniveles/<?= empty($subnivel->id) ? "Registrar" : "Actualizar" ?>" method="post" id="form-subnivel" class="row g-3 needs-validation" novalidate>
        <input type="hidden" name="id" id="input-id" value="<?= $subnivel->id ?? "0" ?>">
        <div class="col-sm-12">
            <label for="input-nombre">Nombre del subnivel</label>
            <input class="form-control" type="text" name="nombre" id="input-nombre" required maxlength="50"
                value="<?= $subnivel->getNombre() ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un nombre válido
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-nombre">Nivel (grado)</label>
            <input type="number" class="form-control" id="nivel" name="nivel" required="" min="1" max="99" value="<?= $subnivel->getNivel() ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un nombre válido
            </div>
        </div>
    </form>
</div>
<div class="p-3 border-top">
    <button type="submit" class="btn btn-accent w-100" form="form-subnivel">Guardar</button>
</div>