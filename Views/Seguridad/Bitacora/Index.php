<?php
/** @var Array<Bitacora> $bitacoras */
$title = "Bitacora";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Bitacora de usuarios</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-bitacora_search" class="form-control" placeholder="Buscar">
        </div>
    </div>
</div>

<table class="table table-bordered table-rounded table-hover datatable" id="tabla-bitacora">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Registro</th>
            <th>Ruta</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bitacoras as $bitacora) : ?>
            <tr>
                <td><?= ($bitacora->usuario?->cedula ?? "")." - ".($bitacora->usuario?->nombre ?? "") ?></td>
                <td><?= $bitacora->registro ?></td>
                <td><?= $bitacora->ruta ?></td>
                <td><?= date("d/m/Y H:i a", strtotime($bitacora->fecha)) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        datatables['tabla-bitacora'].order(3, 'desc')
    })
</script>