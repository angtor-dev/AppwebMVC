<?php
/** @var Array<Rol> $roles */
$title = "Roles y Permisos";
/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Roles</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-roles_search" class="form-control" placeholder="Buscar rol">
        </div>
        <?php if ($usuario->tienePermiso("roles", "registrar")): ?>
            <button class="btn btn-accent text-nowrap" onclick="abrirModalRol()">
                <i class="fa-solid fa-plus"></i>
                Nuevo Rol
            </button>
        <?php endif ?>
    </div>
</div>

<table class="table table-bordered table-rounded table-hover datatable" id="tabla-roles">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th class="text-center" style="width: 90px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $rol): ?>
            <tr>
                <td><?= $rol->getNombre() ?></td>
                <td><?= $rol->getDescripcion() ?></td>
                <td>
                    <div class="acciones">
                        <?php if (
                            $usuario->tienePermiso("roles", "actualizar") && $rol->getNombre() != "Superusuario"
                            && $rol->getNombre() != "Usuario" && $rol->getNombre() != "Pastor" && $rol->getNombre() != "Mentor"
                            && $rol->getNombre() != "Estudiante" && $rol->getNombre() != "Administrador" && $rol->getNombre() != "LiderCelula" && $rol->getNombre() != "LiderTerritorio"
                        ): ?>
                            <a role="button" onclick="abrirModalRol(<?= $rol->id ?>)">
                                <i class="fa-solid fa-pen" title="Actualizar" data-bs-toggle="tooltip"></i>
                            </a>
                        <?php endif ?>
                        <?php if (
                            $usuario->tienePermiso("roles", "eliminar") && $rol->getNombre() != "Superusuario"
                            && $rol->getNombre() != "Usuario" && $rol->getNombre() != "Pastor" && $rol->getNombre() != "Mentor"
                            && $rol->getNombre() != "Estudiante" && $rol->getNombre() != "Administrador" && $rol->getNombre() != "LiderCelula" && $rol->getNombre() != "LiderTerritorio"
                        ): ?>
                            <a role="button" data-bs-toggle="modal" data-bs-target="#confirmar-eliminacion"
                                data-id="<?= $rol->id ?>">
                                <i class="fa-solid fa-trash" title="Eliminar" data-bs-toggle="tooltip"></i>
                            </a>
                        <?php endif ?>
                        <?php if ($usuario->tienePermiso("permisos", "actualizar") && $rol->getNombre() != "Superusuario"): ?>
                            <a role="button" onclick="abrirModalPermisos(<?= $rol->id ?>)">
                                <i class="fa-solid fa-key" title="Gestionar permisos" data-bs-toggle="tooltip"></i>
                            </a>
                        <?php endif ?>
                        <?php if ($rol->getNombre() == "Superusuario"): ?>
                            <em>Rol integrado</em>
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
                <h5 class="modal-title">Eliminar rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-0" role="alert">
                    ¿Seguro quieres eliminar este rol?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancelar</button>
                <a href="#" data-href="/AppwebMVC/Seguridad/Roles/Eliminar?id=" type="button"
                    class="btn btn-danger btn-eliminar">Si, eliminar</a>
            </div>
        </div>
    </div>
</div>

<?php if ($usuario->tienePermiso("roles", "registrar") || $usuario->tienePermiso("roles", "actualizar")): ?>
    <!-- Registrar o actualizar rol -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-rol">
        <!-- Contenido cargado desde ajax -->
    </div>
<?php endif ?>

<?php if ($usuario->tienePermiso("permisos", "actualizar")): ?>
    <!-- Actualizar permisos -->
    <div class="modal fade" tabindex="-1" id="modal-permisos">
        <!-- Contenido cargado desde ajax -->
    </div>
<?php endif ?>

<script>
    function abrirModalRol(id = 0) {
        fetch('/AppwebMVC/Seguridad/Roles/Registrar?id=' + id)
            .then(res => res.text())
            .then(data => {
                const modalEl = document.getElementById('offcanvas-rol')
                modalEl.innerHTML = data
                modalEl.querySelectorAll('.needs-validation')
                    .forEach(agregarValidacionGenerica)

                let modal = new bootstrap.Offcanvas(modalEl)
                modal.show()
            })
            .catch(error => console.error(error))
    }

    function abrirModalPermisos(id) {
        fetch('/AppwebMVC/Seguridad/Roles/Permisos?id=' + id)
            .then(res => res.text())
            .then(data => {
                if (data.includes("ajaxError")) throw data

                const modalEl = document.getElementById('modal-permisos')
                modalEl.innerHTML = data

                let modal = new bootstrap.Modal(modalEl)
                modal.show()
            })
            .catch(error => {
                if (error.includes("ajaxError")) alerta(error)
                console.error(error)
            })
    }
</script>