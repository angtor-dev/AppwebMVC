<?php
/** @var Array<Usuario> $usuarios */
$title = "Usuarios";
?>

<?php if (!empty($alertas['exito']) && count($alertas['exito']) > 0) : ?>
    <?php foreach ($alertas['exito'] as $alerta) : ?>
        <div class="alert alert-success fade show alert-dismissible">
            <?= $alerta ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach ?>
<?php endif ?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Usuarios</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-usuarios_search" class="form-control" placeholder="Buscar usuario">
        </div>
        <button class="btn btn-accent text-nowrap" onclick="abrirModalUsuario()">
            <i class="fa-solid fa-plus"></i>
            Nuevo usuario
        </button>
    </div>
</div>

<table class="table table-bordered table-rounded table-hover datatable" id="tabla-usuarios">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cedula</th>
            <th>Correo</th>
            <th>Roles</th>
            <th class="text-center" style="width: 90px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario) : ?>
            <tr>
                <td><?= $usuario->nombre . " " . $usuario->apellido ?></td>
                <td><?= $usuario->cedula ?></td>
                <td><?= $usuario->correo ?></td>
                <td></td>
                <td>
                    <div class="acciones">
                        <a role="button">
                            <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                        </a>
                        <a role="button" onclick="abrirModalUsuario(<?= $usuario->id ?>)">
                            <i class="fa-solid fa-pen" title="Modificar" data-bs-toggle="tooltip"></i>
                        </a>
                        <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion">
                            <i class="fa-solid fa-trash" title="Eliminar" data-bs-toggle="tooltip"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Confirmar eliminación -->
<div class="modal fade" id="confirmar-eliminacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-0" role="alert">
                    ¿Seguro quieres eliminar este usuario?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                <button type="button" class="btn btn-danger">Si, eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Registrar o actualizar usuario -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-usuario">
    <!-- Contenido cargado desde ajax -->
</div>

<script>
    function abrirModalUsuario(id = 0) {
        fetch('/AppwebMVC/Usuarios/Registrar?id=' + id)
            .then(res => res.text())
            .then(data => {
                const modalEl = document.getElementById('offcanvas-usuario')
                modalEl.innerHTML = data

                let modal = new bootstrap.Offcanvas(modalEl)
                modal.show()
            })
            .catch(error => console.error(error))
    }
</script>