<?php /** @var Discipulo $discipulo */ ?>

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title">Información del discípulo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="row">
        <div class="col-6">
            <b>Nombre:</b>
            <p><?= $discipulo->getNombre() ?></p>
        </div>
        <div class="col-6">
            <b>Apellido:</b>
            <p><?= $discipulo->getApellido() ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <b>Cédula:</b>
            <p><?= $discipulo->getCedula() ?></p>
        </div>
        <div class="col-6">
            <b>Teléfono:</b>
            <p><?= $discipulo->getTelefono() ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <b>Nacimiento:</b>
            <p><?= $discipulo->getFechaNacimiento() ?></p>
        </div>
        <div class="col-6">
            <b>Fecha de Conversión:</b>
            <p><?= $discipulo->getFechaConvercion() ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <b>Asiste a Celula Familiar:</b>
            <p><?= $discipulo->getAsisFamiliar() ?></p>
        </div>
        <div class="col-6">
            <b>Asiste a Celula de Crecimiento:</b>
            <p><?= $discipulo->getAsisCrecimiento() ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <b>Motivo:</b>
            <p><?= $discipulo->getMotivo() ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <b>Dirección:</b>
            <p><?= $discipulo->getDireccion() ?></p>
        </div>
    </div>
</div>
<?php if ($discipulo->getAprobarUsuario() == 1): ?>
    <div class="p-3 border-top">
        <form action="/AppwebMVC/Inscripciones/InscribirDiscipulo" method="post">
            <input type="hidden" name="id" value="<?= $discipulo->id ?>">
            <button type="submit" class="btn btn-accent w-100">Inscribir discipulo</button>
        </form>
    </div>
<?php endif ?>