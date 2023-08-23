<?php /** @var Usuario $usuario */ ?>

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title"> <?= empty($usuario->id) ? "Crear usuario nuevo" : "Modificar usuario" ?> </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body text-secondary">
    <form action="/AppwebMVC/Usuarios/Registrar" method="post" id="form-usuario" class="row g-3 needs-validation" novalidate>
        <div class="col-sm-6">
            <label for="input-nombre">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="input-nombre" required maxlength="50"
                value="<?= $usuario->nombre ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un nombre válido
            </div>
        </div>
        <div class="col-sm-6">
            <label for="input-apellido">Apellido</label>
            <input class="form-control" type="text" name="apellido" id="input-apellido" required maxlength="50"
                value="<?= $usuario->apellido ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un apellido válido
            </div>
        </div>
        <div class="col-sm-5">
            <label for="input-cedula">Cédula</label>
            <input class="form-control" type="text" name="cedula" id="input-cedula" required maxlength="15"
                value="<?= $usuario->cedula ?? "" ?>">
            <div class="invalid-feedback">
                Cédula inválida
            </div>
        </div>
        <div class="col-sm-7">
            <label for="input-telefono">Teléfono</label>
            <input class="form-control" type="text" name="telefono" id="input-telefono" required maxlength="20"
                value="<?= $usuario->telefono ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un teléfono válido
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-correo">Correo</label>
            <input class="form-control" type="email" name="correo" id="input-correo" required maxlength="255"
                value="<?= $usuario->correo ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un correo válido
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-direccion">Dirección</label>
            <input class="form-control" type="text" name="direccion" id="input-direccion" required maxlength="50"
                value="<?= $usuario->direccion ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa una dirección válida
            </div>
        </div>
        <div class="col-sm-6">
            <label for="input-fechaNacimiento">Nacimiento</label>
            <input class="form-control" type="date" name="fechaNacimiento" id="input-fechaNacimiento" required
                value="<?= $usuario->fechaNacimiento ?? "" ?>">
            <div class="invalid-feedback">
                Fecha invalida
            </div>
        </div>
        <div class="col-sm-6">
            <label for="input-estadoCivil">Estado civil</label>
            <select class="form-select" name="estadoCivil" id="estadoCivil" required>
                <option value=""></option>
                <?php foreach (EstadoCivil::cases() as $estado): ?>
                    <option value="<?= $estado->value ?>" <?= $estado->value == ($usuario->estadoCivil ?? null) ? "selected" : "" ?>><?= $estado->name ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback">
                Elige una opción
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-roles">Roles</label>
            <input class="form-control" type="text" name="roles" id="input-roles" required>
            <div class="invalid-feedback">
                Ingresa un rol válido
            </div>
        </div>
        <?php if (empty($usuario->id)): ?>
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
        <?php endif ?>
    </form>
</div>
<div class="p-3 border-top">
    <button type="submit" class="btn btn-accent w-100" form="form-usuario">Guardar</button>
</div>