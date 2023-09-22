<?php
/** @var NivelCrecimiento[] $nivelesCrecimiento */
/** @var Usuario $usuario */
$title = "Niveles de Crecimiento";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Niveles de Crecimineto</h4>
    <div class="d-flex gap-3">
        <?php if (count($nivelesCrecimiento) != 0): ?>
            <div class="buscador">
                <input type="text" id="tabla-niveles_search" class="form-control" placeholder="Buscar Nivel">
            </div>
            <?php if ($usuario->tienePermiso("nivelesCrecimiento", "registrar")): ?>
                <a href="/AppwebMVC/NivelesCrecimiento/Registrar" class="btn btn-accent text-nowrap">
                    <i class="fa-solid fa-plus"></i>
                    Nuevo nivel
                </a>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>

<?php if (count($nivelesCrecimiento) == 0): ?>
    <div class="card" style="padding: 120px;">
        <div class="d-flex flex-column align-items-center text-center">
            <h2>Sin niveles para mostrar</h2>
            <h6 class="mb-4">Parece que aun no se han registrado niveles de crecimiento para esta sede</h6>
            <?php if ($usuario->tienePermiso("nivelesCrecimiento", "registrar")): ?>
                <a href="/AppwebMVC/NivelesCrecimiento/CrearIniciales" class="btn btn-accent">
                    Crear niveles iniciales
                </a>
            <?php endif ?>
        </div>
    </div>
<?php else: ?>
    <table class="table table-bordered table-rounded table-hover" id="tabla-niveles">
        <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>Nivel de crecimiento</th>
                <th class="text-center" style="width: 90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nivelesCrecimiento as $nivel) : ?>
                <tr data-nombres="<?= $nivel->subnivelesArray['nombres'] ?>"
                    data-niveles="<?= $nivel->subnivelesArray['niveles'] ?>"
                    data-ids="<?= $nivel->subnivelesArray['ids'] ?>">
                    <td></td>
                    <td><?= $nivel->nivel ?></td>
                    <td><?= $nivel->nombre ?></td>
                    <td>
                        <div class="acciones">
                            <a role="button">
                                <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                            </a>
                            <?php if ($usuario->tienePermiso("nivelesCrecimiento", "actualizar")): ?>
                                <a role="button" onclick="abrirModalNivelCrecimiento(<?= $nivel->id ?>)">
                                    <i class="fa-solid fa-pen" title="Modificar" data-bs-toggle="tooltip"></i>
                                </a>
                            <?php endif ?>
                            <?php if ($usuario->tienePermiso("nivelesCrecimiento", "eliminar")): ?>
                                <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                    data-id="<?= $nivel->id ?>">
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
                    <h5 class="modal-title">Eliminar nivel de crecimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-0" role="alert">
                        ¿Seguro quieres eliminar este nivel de crecimiento?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                    <a href="#" data-href="/AppwebMVC/NivelesCrecimiento/Eliminar?id=" type="button" class="btn btn-danger btn-eliminar">Si, eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let table = new DataTable('#tabla-niveles', {
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    { data: 'nivel' },
                    { data: 'nombre' },
                    { data: 'acciones' }
                ],
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
            });

            // Add event listener for opening and closing details
            table.on('click', 'td.dt-control', function (e) {
                let tr = e.target.closest('tr');
                let row = table.row(tr);
            
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                }
                else {
                    // Open this row
                    row.child(format(tr.dataset.niveles, tr.dataset.nombres, tr.dataset.ids)).show();
                }
            });
        })

        function format(niveles, nombres, ids) {
            niveles = niveles.split(',')
            nombres = nombres.split(',')
            ids = ids.split(',')
            let res =
                `<table class="table table-bordered mb-0">
                    <tr>
                        <th>#</th>
                        <th>Subnivel</th>
                        <th style="width: 100px;">Acciones</th>
                    </tr>`
            for (let i = 0; i < niveles.length; i++) {
                const nivel = niveles[i];
                const nombre = nombres[i];
                const id = ids[i];

                res +=
                    `<tr>
                        <td>${nivel}</td>
                        <td>${nombre}</td>
                        <td class="d-flex justify-content-center gap-3 border-bottom-0" style="color: var(--color-terceario);">
                            <?php if ($usuario->tienePermiso("nivelesCrecimiento", "actualizar")): ?>
                                <a role="button" onclick="abrirModalSubnivel(${id})">
                                    <i class="fa-solid fa-pen" title="Modificar" data-bs-toggle="tooltip"></i>
                                </a>
                                <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                    data-id="${id}">
                                    <i class="fa-solid fa-trash" title="Eliminar" data-bs-toggle="tooltip"></i>
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>`
            }
            res += `</table>`
            return res;
        }
    </script>
<?php endif ?>