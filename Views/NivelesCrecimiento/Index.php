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
            <?php if ($usuario->tienePermiso("registrarNivelesCrecimiento")): ?>
                <button class="btn btn-accent text-nowrap" onclick="abrirModalNivelCrecimiento()">
                    <i class="fa-solid fa-plus"></i>
                    Nuevo nivel
                </button>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>

<?php if (count($nivelesCrecimiento) == 0): ?>
    <div class="card" style="padding: 120px;">
        <div class="d-flex flex-column align-items-center text-center">
            <h2>Sin niveles para mostrar</h2>
            <h6 class="mb-4">Parece que aun no se han registrado niveles de crecimiento para esta sede</h6>
            <?php if ($usuario->tienePermiso('registrarNivelesCrecimiento')): ?>
                <a href="/AppwebMVC/NivelesCrecimiento/CrearIniciales" class="btn btn-accent">
                    Crear niveles iniciales
                </a>
            <?php endif ?>
        </div>
    </div>
<?php else: ?>
    <table class="table table-bordered table-rounded table-hover datatable" id="tabla-niveles">
        <thead>
            <tr>
                <th>#</th>
                <th>Nivel de crecimiento</th>
                <th class="text-center" style="width: 90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nivelesCrecimiento as $nivel) : ?>
                <tr>
                    <td><?= $nivel->nivel ?></td>
                    <td><?= $nivel->nombre ?></td>
                    <td>
                        <div class="acciones">
                            <a role="button">
                                <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                            </a>
                            <?php if ($usuario->tienePermiso("actualizarNivelesCrecimiento")): ?>
                                <a role="button" onclick="abrirModalUsuario(<?= $usuario->id ?>)">
                                    <i class="fa-solid fa-pen" title="Modificar" data-bs-toggle="tooltip"></i>
                                </a>
                            <?php endif ?>
                            <?php if ($usuario->tienePermiso("eliminarNivelesCrecimiento")): ?>
                                <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                    data-id="<?= $usuario->id ?>">
                                    <i class="fa-solid fa-trash" title="Eliminar" data-bs-toggle="tooltip"></i>
                                </a>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>