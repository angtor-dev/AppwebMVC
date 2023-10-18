<?php
/** @var Grupo $grupo */
/** @var Usuario[] $estudiantes */
/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<div class="d-flex justify-content-between mb-3">
    <a href="/AppwebMVC/Grupos" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
    <?php if ($usuario->tienePermiso("inscripciones", "registrar")): ?>
        <button class="btn btn-accent" onclick="abrirModalEstudiantes(<?= $grupo->id ?>)">
            <i class="fa-solid fa-pen"></i>
            Actualizar matricula
        </button>
    <?php endif ?>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="d-flex gap-2">
            <div class="d-flex flex-column pe-2 border-end">
                <span>Grupo</span>
                <h5 class="mb-0"><?= $grupo->getNombre() ?></h5>
            </div>
            <div class="d-flex flex-column">
                <span>Nivel</span>
                <h5 class="mb-0"><?= $grupo->subnivel->nivelCrecimiento->getNombre() ?></h5>
            </div>
        </div>
        <div class="d-flex flex-column text-end">
            <span>Estado</span>
            <h5 class="mb-0"><?= EstadosGrupo::tryFrom($grupo->getEstado())->name ?></h5>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (count($grupo->estudiantes) > 0): ?>
            <table class="table table-bordered table-rounded mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Correo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grupo->estudiantes as $estudiante): ?>
                        <tr>
                            <td><?= $estudiante->getNombreCompleto() ?></td>
                            <td><?= $estudiante->getCedula() ?></td>
                            <td><?= $estudiante->getCorreo() ?></td>
                            <td class="acciones">
                                <a role="button">
                                    <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                                </a>
                                <a role="button" onclick="abrirModalNotas(<?= $estudiante->id ?>)">
                                    <i class="fa-solid fa-list-ol" title="Gestionar notas" data-bs-toggle="tooltip"></i>
                                </a>
                            </td>
                        </tr>
                        
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="p-3 text-center">
                <h5 class="mb-0">¡Este grupo aun no tiene estudiantes!</h5>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- Agregar estudiante -->
<div class="modal fade" id="modal-estudiantes">
    <!-- Contenido cargado con ajax -->
</div>

<!-- Gestionar notas -->
<div class="modal fade" tabindex="-1" id="modal-notas">
    <!-- Contenido cargado desde ajax -->
</div>

<script>
    function abrirModalEstudiantes(id = 0) {
        fetch('/AppwebMVC/Grupos/AgregarEstudiantes?id=' + id)
            .then(res => res.text())
            .then(data => {
                const modalEl = document.getElementById('modal-estudiantes')
                modalEl.innerHTML = data
                modalEl.querySelectorAll('.needs-validation')
                    .forEach(agregarValidacionGenerica)

                let modal = new bootstrap.Modal(modalEl)
                modal.show()

                var tabla = new DataTable('#tabla-estudiantes', {
                    info: false,
                    lengthChange: false,
                    pageLength: 15,
                    language: {
                        url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
                    },
                    // Muestra paginacion solo si hay mas de una pagina
                    drawCallback: function (settings) {
                        var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                        pagination.toggle(this.api().page.info().pages > 1);
                    }
                })
            })
            .catch(error => console.error(error))
    }

    function abrirModalNotas(id) {
        fetch('/AppwebMVC/Grupos/Notas/Consultar?id=' + id)
            .then(res => res.text())
            .then(data => {
                const modalEl = document.getElementById('modal-notas')
                modalEl.innerHTML = data

                modalEl.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipEl => {
                    new bootstrap.Tooltip(tooltipEl)
                })

                let modal = new bootstrap.Modal(modalEl)
                modal.show()
            })
            .catch(error => console.error(error))
    }
</script>