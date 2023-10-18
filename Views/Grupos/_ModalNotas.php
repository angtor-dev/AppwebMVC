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
                                    <span class="nota"><?= isset($nota) ? $nota->getCalificaion() : "<em>Sin nota</em>" ?></span>
                                    <!-- <input type="number" name="calificación" id="input-nota" min="0" max="20" step="0.01"> -->
                                    <div class="d-flex gap-2">
                                        <?php if (isset($nota)): ?>
                                            <button class="btn-small bg-primary" data-bs-toggle="tooltip" title="Actulizar">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-small bg-danger" data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-small bg-success" data-bs-toggle="tooltip" title="Agregar"
                                                onclick="agregarNota()">
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