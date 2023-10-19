<?php
/** @var Usuario $estudiante */
/** @var Grupo $grupo */
/** @var Clase[] $clases */
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Notas de <?= $estudiante->getNombreCompleto() ?></h5>
        </div>
        <div class="modal-body">
            <div class="mb-2">
                <b>Grupo:</b> <?= $grupo->getNombre() ?>
            </div>
            <table class="table table-bordered table-rounded">
                <thead>
                    <tr>
                        <th class="text-center">Clase</th>
                        <th class="text-center">Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clases as $clase): ?>
                        <?php
                        /** @var ?Nota */
                        $nota = Nota::buscarNota($clase->id, $estudiante->id);
                        ?>
                        <tr>
                            <td><?= $clase->getTitulo() ?></td>
                            <td>
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <form action="">
                                        <input type="hidden" name="id" value="<?= isset($nota) ? $nota->id : "" ?>">
                                        <input type="hidden" name="idClase" value="<?= $clase->id ?>">
                                        <input type="hidden" name="idEstudiante" value="<?= $estudiante->id ?>">
                                        <input type="number" class="d-none" name="calificacion" id="input-nota"
                                            min="0" max="20" step="0.01" value="<?= isset($nota) ? $nota->getCalificacion() : "0" ?>">
                                    </form>
                                    <span class="nota"><?= isset($nota) ? $nota->getCalificacion()." / 20" : "<em>Sin nota</em>" ?></span>
                                    <div class="d-flex gap-2">
                                        <?php if (isset($nota)): ?>
                                            <button class="btn-small bg-primary" title="Actulizar" onclick="agregarNota(this)">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-small bg-danger" title="Eliminar" onclick="eliminarNota(this)">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-small bg-success" title="Agregar"
                                                onclick="agregarNota(this)">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-accent" data-bs-dismiss="modal">
                Cerrar
            </button>
        </div>
    </div>
</div>