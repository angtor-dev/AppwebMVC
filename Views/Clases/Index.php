<?php
/** @var Clase[] $clases*/
/** @var Usuario $usuario*/
$title = "Clases";
$usuario = $_SESSION['usuario'];
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Clases de la E.I.D.</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-clases_search" class="form-control" placeholder="Buscar clase">
        </div>
        <?php if ($usuario->tienePermiso("clases", "consultar")): ?>
            <button class="btn btn-accent text-nowrap" onclick="abrirModalClase()">
                <i class="fa-solid fa-plus"></i>
                Nueva clase
            </button>
        <?php endif ?>
    </div>
</div>

<table class="table table-bordered table-rounded table-hover datatable" id="tabla-clases">
    <thead>
        <tr>
            <th>Grupo</th>
            <th>Titulo</th>
            <th>Objetivo</th>
            <th>Contenidos</th>
            <th class="text-center" style="width: 90px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clases as $clase) : ?>
            <?php
            if ($clase->grupo->getEstado() == EstadosGrupo::Finalizado->value) {
                continue;
            }
            ?>
            <tr>
                <td><?= $clase->grupo->getNombre() ?></td>
                <td><?= $clase->getTitulo() ?></td>
                <td><?= $clase->getObjetivo() ?></td>
                <td><?= count($clase->contenidos) ?></td>
                <td>
                    <div class="acciones">
                        <a href="/AppwebMVC/Clases/Contenidos?id=<?= $clase->id ?>" style="color: inherit;">
                            <i class="fa-solid fa-file-lines" title="Ver contenidos" data-bs-toggle="tooltip"></i>
                        </a>
                        <a role="button">
                            <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                        </a>
                        <?php if ($usuario->tienePermiso("clases", "actualizar")): ?>
                            <a role="button" onclick="abrirModalClase(<?= $clase->id ?>)">
                                <i class="fa-solid fa-pen" title="Actualizar" data-bs-toggle="tooltip"></i>
                            </a>
                        <?php endif ?>
                        <?php if ($usuario->tienePermiso("clases", "eliminar")): ?>
                            <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                data-id="<?= $clase->id ?>">
                                <i class="fa-solid fa-trash" title="Eliminar" data-bs-toggle="tooltip"></i>
                            </a>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

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
                    ¿Seguro quieres eliminar esta clase?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                <a href="#" data-href="/AppwebMVC/Clases/Eliminar?id=" type="button" class="btn btn-danger btn-eliminar">Si, eliminar</a>
            </div>
        </div>
    </div>
</div>

<!-- Registrar o actualizar usuario -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-usuario">
    <!-- Contenido cargado desde ajax -->
</div>

<script>
    function abrirModalClase(id = 0) {
        fetch('/AppwebMVC/Clases/Registrar?id=' + id)
            .then(res => res.text())
            .then(data => {
                const modalEl = document.getElementById('offcanvas-usuario')
                modalEl.innerHTML = data
                modalEl.querySelectorAll('.needs-validation')
                    .forEach(agregarValidacionGenerica)

                let modal = new bootstrap.Offcanvas(modalEl)
                modal.show()
            })
            .catch(error => console.error(error))
    }
</script>