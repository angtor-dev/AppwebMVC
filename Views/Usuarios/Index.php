<?php
/** @var Array<Usuario> $usuarios */
$title = "Usuarios";
/** @var Usuario */
$usuarioSesion = $_SESSION['usuario'];
?>

<?php if (!empty($alertas['exito']) && count($alertas['exito']) > 0) : ?>
    <?php foreach ($alertas['exito'] as $alerta) : ?>
        <div class="alert alert-success fade show alert-dismissible">
            <?= $alerta ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach ?>
<?php endif ?>

<div class="page-top d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Usuarios</h4>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="tabla-usuarios_search" class="form-control" placeholder="Buscar usuario">
        </div>
        <?php if ($usuarioSesion->tienePermiso("usuarios", "registrar")): ?>
            <button class="btn btn-accent text-nowrap" onclick="abrirModalUsuario()">
                <i class="fa-solid fa-plus"></i>
                Nuevo usuario
            </button>
        <?php endif ?>
    </div>
</div>

<div class="table-responsive">
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
                    <td><?= $usuario->getNombreCompleto() ?></td>
                    <td><?= $usuario->getCedula() ?></td>
                    <td><?= $usuario->getCorreo() ?></td>
                    <td>
                        <?=
                        implode(", ", array_map(function(Rol $rol) {
                            return $rol->getNombre();
                        }, $usuario->roles));
                        ?>
                    </td>
                    <td>
                        <div class="acciones">
                            <a href="/AppwebMVC/Usuarios/Detalles?id=<?= $usuario->id ?>">
                                <i class="fa-solid fa-circle-info" title="Ver detalles" data-bs-toggle="tooltip"></i>
                            </a>
                            <?php if ($usuarioSesion->tienePermiso("usuarios", "actualizar")): ?>
                                <a role="button" onclick="abrirModalUsuario(<?= $usuario->id ?>)">
                                    <i class="fa-solid fa-pen" title="Actualizar" data-bs-toggle="tooltip"></i>
                                </a>
                            <?php endif ?>
                            <?php if ($usuarioSesion->tienePermiso("usuarios", "eliminar") && $usuario->id != $usuarioSesion->id): ?>
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
</div>

<!-- Confirmar eliminación -->
<div class="modal fade modal-eliminar" id="confirmar-eliminacion" tabindex="-1" aria-hidden="true">
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
                <a href="#" data-href="/AppwebMVC/Usuarios/Eliminar?id=" type="button" class="btn btn-danger btn-eliminar">Si, eliminar</a>
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
                let forms = modalEl.querySelectorAll('.needs-validation')
                forms.forEach(agregarValidacionGenerica)
                forms.forEach(agregarValidacionUsuario)

                let modal = new bootstrap.Offcanvas(modalEl)
                modal.show()
            })
            .catch(error => console.error(error))
    }

    function agregarValidacionUsuario(form) {
        const correoRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
        const claveRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/
        form.querySelectorAll('#input-nombre, #input-apellido, #input-direccion').forEach(input => {
            input.addEventListener('blur', e => {
                let value = e.target.value
                if (value == "" || /\d/.test(value) || value.length > 50) {
                    input.classList.add('is-invalid')
                }
            })
        })
        form.querySelectorAll('#input-cedula, #input-telefono').forEach(input => {
            input.addEventListener('blur', e => {
                let value = e.target.value
                if (value == "" || !/^\d*$/.test(value)
                    || (input.id == "input-cedula" && value.length > 9 && value.length < 7)
                    || (input.id == "input-telefono" && value.length != 11)) {
                    input.classList.add('is-invalid')
                }
            })
        })
        form.querySelector('#input-cedula').addEventListener('blur', async e => {
            let value = e.target.value
            let res = await fetch("/AppwebMVC/Usuarios/Buscar?tipo=cedula&valor="+value)
            if (await res.text() === 'true') {
                e.target.classList.add('is-invalid')
                e.target.nextElementSibling.textContent = "La cédula ya existe"
            }
        })
        form.querySelector('#input-correo').addEventListener('blur', async e => {
            let value = e.target.value
            if (!correoRegex.test(value)) {
                e.target.classList.add('is-invalid')
            }

            let res = await fetch("/AppwebMVC/Usuarios/Buscar?tipo=correo&valor="+value)
            if (await res.text() === 'true' && (!e.target.dataset.valor || e.target.dataset.valor != value)) {
                e.target.classList.add('is-invalid')
                e.target.nextElementSibling.textContent = "Ya existe un usuario con este correo"
            }
        })
        form.querySelectorAll('select').forEach(select => {
            select.addEventListener('blur', e => {
                if (e.target.value == '') {
                    select.classList.add('is-invalid')
                }
            })
        })
        form.querySelector('#input-clave')?.addEventListener('blur', e => {
            let value = e.target.value
            if (value == "" || !claveRegex.test(value)) {
                e.target.classList.add('is-invalid')
            }
        })
        form.querySelector('#input-confirmacion')?.addEventListener('blur', e => {
            let value = e.target.value
            let claveValue = form.querySelector('#input-clave').value
            if (value != claveValue) {
                e.target.classList.add('is-invalid')
            }
        })
        form.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('focus', () => {
                input.classList.remove('is-invalid')
                if (input.dataset.feedback) {
                    input.nextElementSibling.textContent = input.dataset.feedback
                }
            })
        })
    }
</script>