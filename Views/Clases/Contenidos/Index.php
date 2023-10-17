<?php
/** @var Clase $clase */
$title = "Contenidos";
/** @var Usuario */
$usuarioSesion = $_SESSION['usuario'];
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h2 class="mb-0 fw-bold"><?= $clase->getTitulo() ?> - <?= $clase->grupo->getNombre() ?></h2>
    <div class="d-flex gap-3">
        <?php if ($usuarioSesion->tienePermiso("clases", "actualizar")): ?>
            <a href="/AppwebMVC/Clases/Contenidos/Registrar?id=<?= $clase->id ?>" class="btn btn-accent text-nowrap">
                <i class="fa-solid fa-plus"></i>
                Registrar contenido
            </a>
        <?php endif ?>
    </div>
</div>

<div class="mb-4">
    <h5 class="mb-0">Objetivo: <?= $clase->getObjetivo() ?></h5>
</div>

<?php if (count($clase->contenidos) > 0): ?>
    <?php foreach ($clase->contenidos as $contenido): ?>
        <div class="card border shadow mb-5">
            <div class="card-body py-4 px-4">
                <div class="mb-2 pt-2 pb-3 border-bottom">
                    <h3 class="fw-bold mb-0"><?= $contenido->getTitulo() ?></h3>
                </div>
                <div class="pt-2">
                    <?= $contenido->getContenido() ?>
                </div>
            </div>
            <div class="float-buttons position-absolute d-flex gap-2 end-0 p-4">
                <a href="/AppwebMVC/Clases/Contenidos/Actualizar?id=<?= $contenido->id ?>" class="btn btn-primary px-2" title="Actulizar">
                    <i class="fa-solid fa-pen fa-fw"></i>
                </a>
                <button class="btn btn-danger px-2" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion" data-id="<?= $contenido->id ?>">
                    <i class="fa-solid fa-trash fa-fw"></i>
                </button>
            </div>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="card">
        <div class="card-body py-5 text-center">
            <div class="my-5 text-center w-100">
                <h4 class="fw-bold">¡Vaya, esta clase aun no posee contenidos!</h4>
                <?php if ($usuarioSesion->tienePermiso("clases", "actualizar")): ?>
                    <button class="btn btn-accent mt-3">
                        <i class="fa-solid fa-plus"></i>
                        Agregar
                    </button>
                <?php endif ?>
            </div>
        </div>
    </div>
<?php endif ?>

<!-- Confirmar eliminación -->
<div class="modal fade modal-eliminar" id="confirmar-eliminacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar clase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-0" role="alert">
                    ¿Seguro quieres eliminar esta contenido?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                <a href="#" data-href="/AppwebMVC/Clases/Contenidos/Eliminar?idClase=<?= $clase->id ?>&id=" type="button" class="btn btn-danger btn-eliminar">Si, eliminar</a>
            </div>
        </div>
    </div>
</div>