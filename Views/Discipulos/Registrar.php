<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["Discipulo-registrar.js"];
?>

<h2>Registro de Discipulos</h2>

<div class="container-fluid">

    <form id="formulario" class="row" novalidate>
        <div class="row my-2">
            <div class="col-lg-4 col-md-4">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input name="nombre" type="text" class="form-control" id="nombre" maxlength="50" aria-describedby="msj_nombre" required>
                <div id="msj_nombre" class="invalid-feedback">
                    Este campo no puede estar vacio y no acepta numeros.
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <label for="apellido" class="form-label fw-bold">Apellido</label>
                <input type="text" class="form-control" id="apellido" aria-describedby="msj_apellido" maxlength="50">
                <div id="msj_apellido" class="invalid-feedback">
                    este campo no puede estar vacio y no acepta numeros.
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <label for="cedula" class="form-label fw-bold">Cedula de Identidad</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">CI</span>
                    <input type="number" class="form-control" id="cedula" aria-describedby="msj_cedula">
                    <div id="msj_cedula" class="invalid-feedback">
                        este campo no puede estar vacio, escriba correctamente la cedula.
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-lg-3">
                <label for="telefono" class="form-label fw-bold">Numero de telefono</label>
                <input type="number" class="form-control" id="telefono" aria-describedby="msj_telefono">
                <div id="msj_telefono" class="invalid-feedback">
                    escriba correctamente el numero de telefono ej.04145555555
                </div>
            </div>

            <div class="col-lg-3">
                <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                <select class="form-select" id="estadoCivil" aria-describedby="msj_estadoCivil">
                    <option selected value="">Seleccione...</option>
                    <option value="soltero">Soltero/a</option>
                    <option value="casado">Casado/a</option>
                    <option value="viudo">Viudo/a</option>
                </select>
                <div id="msj_estadoCivil" class="invalid-feedback">
                    Este campo es obligatorio.
                </div>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-lg-3">
                <label for="fechaNacimiento" class="form-label fw-bold">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento">
                <div id="msj_fechaNacimiento" class="invalid-feedback">
                    Este campo es obligatorio.
                </div>
            </div>
            <div class="col-lg-3">
                <label for="fechaConvercion" class="form-label fw-bold">Fecha de Conversión</label>
                <input type="date" class="form-control" id="fechaConvercion">
                <div id="msj_fechaConvercion" class="invalid-feedback">
                    este campo es obligatorio
                </div>
            </div>
        </div>

        <div class="row my-4">
            <div class="col-lg-3">
                <div class="form-check">
                    <label class="form-check-label fw-bold" for="asisCrecimiento">
                        Asiste a Celula de Crecimiento
                    </label>
                    <input class="form-check-input" type="checkbox" value="si" id="asisCrecimiento">
                </div>

            </div>

            <div class="col-lg-3">
                <div class="form-check">
                    <label class="form-check-label fw-bold" for="asisCrecimiento">
                        Asiste a Celula Familiar
                    </label>
                    <input class="form-check-input" type="checkbox" value="si" id="asisFamiliar">
                </div>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-lg-4">
                <label for="idConsolidador" class="form-label fw-bold">Consolidador</label>
                <select class="form-select" id="idConsolidador">
                    <option selected value="">Selecciona un consolidador</option>
                </select>
                <div id="msj_idConsolidador" class="text-danger d-none">
                    Escoja el Consolidador.
                </div>
            </div>
            <div class="col-lg-4">
                <label for="idcelulaconsolidacion" class="form-label fw-bold">Celula de Consolidacion</label>
                <select class="form-select" id="idcelulaconsolidacion">
                    <option selected value="">Selecciona una celula</option>
                </select>
                <div id="msj_idcelulaconsolidacion" class="text-danger d-none">
                    Escoja una Celula de Consolidacion.
                </div>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-lg-6">
                <label for="direccion" class="form-label fw-bold">Dirección</label>
                <textarea class="form-control" id="direccion" placeholder="" maxlength="100"></textarea>
                <div class="invalid-feedback">
                    Este campo no puede estar vacio.
                </div>
            </div>
            <div class="col-lg-6">
                <label for="motivo" class="form-label fw-bold">Motivo</label>
                <textarea class="form-control" id="motivo" placeholder="" maxlength="100"></textarea>
                <div class="invalid-feedback">
                    Este campo no puede estar vacio.
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">

            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>

</div>