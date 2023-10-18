<?php
/** @var Usuario[] $estudiantes */
/** @var Usuario $usuario */
$title = "Notas";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Gestion de Notas</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-estudiantes_search" class="form-control" placeholder="Buscar estudiante">
        </div>
    </div>
</div>

<table class="table table-bordered table-rounded table-hover datatable" id="tabla-estudiantes">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cedula</th>
            <th>Grupo</th>
            <th class="text-center" style="width: 90px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($estudiantes as $estudiante) : ?>
            <?php
            $grupo = $estudiante->getGrupo();
            if (is_null($grupo)) continue;
            ?>
            <tr>
                <td><?= $estudiante->getNombreCompleto() ?></td>
                <td><?= $estudiante->getCedula() ?></td>
                <td><?= $grupo->getNombre() ?></td>
                <td>
                    <div class="acciones">
                        <a role="button" onclick="abrirModalNotas(<?= $estudiante->id ?>)">
                            <i class="fa-solid fa-list-ol" title="Gestionar notas" data-bs-toggle="tooltip"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Gestionar notas -->
<div class="modal modal-end" tabindex="-1" id="modal-notas">
    <!-- Contenido cargado desde ajax -->
</div>

<script>
    function abrirModalNotas(id) {
        fetch('/AppwebMVC/Notas/Consultar?id=' + id)
            .then(res => res.text())
            .then(data => {
                const modalEl = document.getElementById('modal-notas')
                modalEl.innerHTML = data

                let modal = new bootstrap.Modal(modalEl)
                modal.show()
            })
            .catch(error => console.error(error))
    }
</script>