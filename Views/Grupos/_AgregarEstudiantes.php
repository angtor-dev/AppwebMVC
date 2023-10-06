<?php
/** @var Grupo $grupo */
/** @var Usuario[] $estudiantes */
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar estudiantes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/AppwebMVC/Grupos/AgregarEstudiantes" method="post" id="form-estudiantes">
                <input type="hidden" name="idGrupo" value="<?= $grupo->id ?>">
                <table class="table table-striped" id="tabla-estudiantes">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th style="width: 62px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td><?= $estudiante->getNombreCompleto() ?></td>
                                <td><?= $estudiante->getCedula() ?></td>
                                <td><?= $estudiante->getCorreo() ?></td>
                                <td class="text-end">
                                    <label class="check-toggle">
                                        <input type="checkbox" name="estudiantes[]" value="<?= $estudiante->id ?>"
                                            <?= $grupo->tieneEstudiante($estudiante->id) ? "checked" : "" ?>>
                                        <a role="button" class="btn">
                                            <i class="fa-solid fa-user-plus fa-fw"></i>
                                            <i class="fa-solid fa-user-minus fa-fw"></i>
                                        </a>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
            <button class="btn btn-accent" type="submit" form="form-estudiantes">Guardar</button>
        </div>
    </div>
</div>