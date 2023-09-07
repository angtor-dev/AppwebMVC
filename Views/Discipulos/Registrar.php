<?php 
global $viewStyles;
global $viewScripts;
$viewScripts = ["Discipulo-registrar.js"];
?>

<h2>Registro de Discipulos</h2>

<div class="container-fluid">

                <form id="formulario" class="row g-3 " novalidate>
                <div class="col-md-4">
                    <label for="nombre" class="form-label fw-bold">Nombre</label>
                    <input name="nombre" type="text" class="form-control" id="nombre" maxlength="50" aria-describedby="msj_nombre"  required>
                    <div id="msj_nombre" class="invalid-feedback">
                        Este campo no puede estar vacio y no acepta numeros.
                    </div>

                </div>
                <div class="col-md-4">
                    <label for="apellido" class="form-label fw-bold">Apellido</label>
                    <input type="text" class="form-control" id="apellido"   aria-describedby="msj_apellido" required>
                    <div id="msj_apellido" class="invalid-feedback">
                    este campo no puede estar vacio y no acepta numeros.
                    </div> 
                </div>
                <div class="col-md-4">
                    <label for="cedula" class="form-label fw-bold">Cedula de Identidad</label>
                    <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">CI</span>
                    <input type="text" class="form-control" id="cedula"  aria-describedby="msj_cedula" required>
                    <div id="msj_cedula" class="invalid-feedback">
                        este campo no puede estar vacio, escriba correctamente la cedula.
                    </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <label for="telefono" class="form-label fw-bold">Numero de telefono</label>
                    <input type="text" class="form-control" id="telefono" aria-describedby="msj_telefono" required>
                    <div id="msj_telefono" class="invalid-feedback">
                    escriba correctamente el numero de telefono ej.04145555555
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                    <select class="form-select" id="estadoCivil" aria-describedby="msj_estadoCivil" required>
                    <option selected disabled value="k">Choose...</option>
                    <option value="soltero">Soltero/a</option>
                    <option value="casado">Casado/a</option>
                    <option value="viudo">Viudo/a</option>
                    </select>
                    <div id="msj_estadoCivil" class="invalid-feedback">
                    Este campo es obligatorio.
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="fechaNacimiento" class="form-label fw-bold">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimiento" required>
                    <div id="msj_fechaNacimiento" class="invalid-feedback">
                    Este campo es obligatorio.
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="fechaConvercion" class="form-label fw-bold">Fecha de Conversión</label>
                    <input type="date" class="form-control" id="fechaConvercion" required>
                    <div id="msj_fechaConvercion" class="invalid-feedback">
                    este campo es obligatorio
                    </div>
                </div>

                <div class="row g-3 ">
                <div class="col-md-3 d-flex align-items-start bd-highlight">
                    <div class="form-check d-block p-2 bd-highlight">
                    <label class="form-check-label fw-bold" for="asisCrecimiento">
                        Asiste a Celula de Crecimiento
                    </label>
                    <input class="form-check-input" type="checkbox" value="si" id="asisCrecimiento">
                    </div>
                    <div class="form-check d-block p-2 bd-highlight">
                    <label class="form-check-label fw-bold" for="asisCrecimiento">
                        Asiste a Celula Familiar
                    </label>
                    <input class="form-check-input" type="checkbox" value="si" id="asisFamiliar">
                    </div>
                </div>

                <div class="col-md-5">
                    <label for="idConsolidador" class="form-label fw-bold">Consolidador</label>
                    <select class="form-select" id="idConsolidador" required>
                    <option selected disabled value="k">...</option>
                    </select>
                    <div id="msj_idConsolidador" class="invalid-feedback">
                    Escoja el Consolidador.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="idcelulaconsolidacion" class="form-label fw-bold">Celula de Consolidacion</label>
                    <select class="form-select" id="idcelulaconsolidacion" required>
                    <option selected disabled value="k">...</option>
                    </select>
                    <div id="msj_idcelulaconsolidacion" class="invalid-feedback">
                    Escoja una Celula de Consolidacion.
                    </div>
                </div>
                </div>

                
                    <div class="col-6">
                    <label for="direccion" class="form-label fw-bold">Dirección</label>
                    <textarea class="form-control" id="direccion" placeholder="" maxlength="100" required></textarea>
                    <div class="invalid-feedback">
                   Este campo no puede estar vacio.
                    </div>
                </div>
                <div class="col-6">
                    <label for="motivo" class="form-label fw-bold">Motivo</label>
                    <textarea class="form-control" id="motivo" placeholder="" maxlength="100" required></textarea>
                    <div class="invalid-feedback">
                    Este campo no puede estar vacio.
                    </div>
                </div>
                


                <div class="d-flex justify-content-end">
                             
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                </form>

</div>

