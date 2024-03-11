<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["MiPerfil.js"];

?>

<div class="row">
    <div class="col">
        <h4>Mis datos</h4>
    </div>
</div>

<form class="mt-4" method="post" id="saveDatosForm">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" maxlength="50">
                <small id="nombreError" class="form-text text-danger d-none">Los caracteres ingresados
                    son invalidos</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" maxlength="50">
                <small id="apellidoError" class="form-text text-danger d-none">Los caracteres ingresados
                    son invalidos</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Telefono</label>
                <input type="number" class="form-control" id="telefono" name="telefono">
                <small id="telefonoError" class="form-text text-danger d-none">Telefono ingresado
                    invalido</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Cedula</label>
                <input type="number" class="form-control" id="cedula" name="cedula">
                <small id="cedulaError" id="emailHelp" class="form-text text-danger d-none">Cedula
                    ingresada invalida</small>
            </div>
        </div>

        <!-- <div class="col-sm-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="form-label">Sexo</label>
                                <select class="form-control" id="sexo" name="sexo">
                                    <option value="" selected>Seleccione</option>
                                    <option value="hombre">Hombre</option>
                                    <option value="mujer">Mujer</option>
                                </select>
                                <small id="sexoError" class="form-text text-danger d-none">Seleccion invalida</small>
                            </div>
                        </div> -->
    </div>

    <div class="row mt-4">
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento">
                <small id="fechaNacimientoError" class="form-text text-danger d-none">La fecha ingresada es
                    invalida</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Estado civil</label>
                <select class="form-control" id="estadoCivil" name="estadoCivil">
                    <option value="" selected>Seleccione</option>
                    <option value="S">Soltero/a</option>
                    <option value="C">Casado/a</option>
                    <option value="D">Divorviado/a</option>
                    <option value="V">Viudo/a</option>
                </select>
                <small id="estadoCivilError" class="form-text text-danger d-none">El estado civil es invalido</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo">
                <small id="correoError" class="form-text text-danger d-none">Los caracteres ingresados son
                    invalidos y no puede estar vacio</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="form-label">Sede</label>
                <input disabled type="text" class="form-control" id="idSede" name="idSede">
                <small id="idSedeError" class="form-text text-danger d-none">Debe seleccionar una sede valida</small>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="form-label">Direccion</label>
                <input type="text" class="form-control" id="direccion" name="direccion">
                <small id="direccionError" class="form-text text-danger d-none">Los caracteres ingresados son
                    invalidos y no puede estar vacio</small>
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary mt-4" id="saveDatos">Guardar</button>
</form>

<div class="my-5"></div>

<div class="row">
    <div class="col">
        <h4>Actualizar pregunta de seguridad</h4>
    </div>
</div>

<form class="mt-4" method="post" id="savePreguntaSecurityForm">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5">
            <div class="form-group">
                <label class="form-label">Pregunta de seguridad</label>
                <input type="text" class="form-control" id="preguntaSecurity" name="preguntaSecurity">
                <small id="preguntaSecurityError" class="form-text text-danger d-none">Los caracteres
                    ingresados son invalidos y no puede estar vacio</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-5">
            <div class="form-group">
                <label class="form-label">Respuesta de la pregunta de seguridad</label>
                <input type="text" class="form-control" id="respuestaSecurity" name="respuestaSecurity">
                <small id="respuestaSecurityError" class="form-text text-danger d-none">Los caracteres
                    ingresados son invalidos y no puede estar vacio</small>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-4" id="savePregunta">Guardar</button>
</form>