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
        <button class="btn btn-accent text-nowrap" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-usuario">
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
                <td></td>
                <td></td>
                <td>
                    <div class="acciones">
                        <a role="button">
                            <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                        </a>
                        <a role="button">
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
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title"> Crear usuario nuevo </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body text-secondary">
        <form action="/AppwebMVC/Usuarios/Registrar" method="post" id="form-usuario" class="row g-3 needs-validation" novalidate>
            <div class="col-sm-6">
                <label for="input-nombre">Nombre</label>
                <input class="form-control" type="text" name="nombre" id="input-nombre" required maxlength="50">
                <div class="invalid-feedback">
                    Ingresa un nombre válido
                </div>
            </div>
            <div class="col-sm-6">
                <label for="input-apellido">Apellido</label>
                <input class="form-control" type="text" name="apellido" id="input-apellido" required maxlength="50">
                <div class="invalid-feedback">
                    Ingresa un apellido válido
                </div>
            </div>
            <div class="col-sm-5">
                <label for="input-cedula">Cédula</label>
                <input class="form-control" type="text" name="cedula" id="input-cedula" required maxlength="15">
                <div class="invalid-feedback">
                    Cédula inválida
                </div>
            </div>
            <div class="col-sm-7">
                <label for="input-telefono">Teléfono</label>
                <input class="form-control" type="text" name="telefono" id="input-telefono" required maxlength="20">
                <div class="invalid-feedback">
                    Ingresa un teléfono válido
                </div>
            </div>
            <div class="col-sm-12">
                <label for="input-correo">Correo</label>
                <input class="form-control" type="email" name="correo" id="input-correo" required maxlength="255">
                <div class="invalid-feedback">
                    Ingresa un correo válido
                </div>
            </div>
            <div class="col-sm-12">
                <label for="input-roles">Roles</label>
                <input class="form-control" type="text" name="roles" id="input-roles" required>
                <div class="invalid-feedback">
                    Ingresa un rol válido
                </div>
            </div>
            <div class="col-sm-6">
                <label for="input-clave">Contraseña</label>
                <input class="form-control" type="password" name="clave" id="input-clave" required maxlength="20">
                <div class="invalid-feedback">
                    Ingresa una contraseña válida
                </div>
            </div>
            <div class="col-sm-6">
                <label for="input-confirmacion">Repetir contraseña</label>
                <input class="form-control" type="password" name="confirmacion" id="input-confirmacion" required maxlength="20">
                <div class="invalid-feedback">
                    Las contraseñas deben coincidir
                </div>
            </div>
        </form>
    </div>
    <div class="p-3 border-top">
        <button type="submit" class="btn btn-accent w-100" form="form-usuario">Guardar</button>
    </div>
</div>