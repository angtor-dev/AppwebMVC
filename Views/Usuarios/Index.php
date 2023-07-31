<?php
/** @var Array<Usuario> $usuarios */
$title = "Usuarios";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Usuarios</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-usuarios_search" class="form-control" placeholder="Buscar usuario">
        </div>
        <button class="btn btn-accent text-nowrap">
            <i class="fa-solid fa-plus"></i>
            Nuevo usuario
        </button>
    </div>
</div>

<table class="table table-bordered table-hover datatable" id="tabla-usuarios">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cedula</th>
            <th>Correo</th>
            <th>Roles</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario->nombre." ".$usuario->apellido ?></td>
                <td><?= $usuario->cedula ?></td>
                <td></td>
                <td></td>
                <td>
                    <div class="acciones">
                        <i class="fa-solid fa-circle-info"></i>
                        <i class="fa-solid fa-pen"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>