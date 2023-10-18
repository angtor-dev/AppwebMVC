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
            <b>Grupo actual:</b> <?= $grupo->getNombre() ?>
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
                                    <span><?= isset($nota) ? $nota->getCalificaion() : "0,00" ?></span>
                                    <i class="fa-solid fa-pen me-2" data-bs-toggle="tooltip" title="Actualizar"></i>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>