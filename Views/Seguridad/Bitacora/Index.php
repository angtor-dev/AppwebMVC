<?php
global $viewScripts;
$viewScripts = ["bitacora.js"];
$title = "Bitacora";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Bitacora de usuarios</h4>
    <div class="d-flex gap-3">
    <div class="buscador">
            <input type="text" id="search" class="form-control" placeholder="Buscar...">
        </div>
    </div>
</div>

<div class="table-responsive">
<table id="tabla-bitacora" class="table table-bordered table-rounded table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Registro</th>
            <th>Ruta</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
</div>
