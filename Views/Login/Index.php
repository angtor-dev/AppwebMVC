<?php
$_layout = "Login";
?>


<div class="container-fluid" id="loginMain">
    <div class="row h-100 d-flex justify-content-center">
        <div class="col-lg-5 col-md-8 col-sm-12 d-flex justify-content-center align-items-center flex-column">

            <div class="d-flex flex-column text-center mb-3">
                <h2 id="bienvenida1">INICIO DE SESION</h2>
                <h4 id="bienvenida2">Bienvenidos a Llamas de Fuego</h4>
            </div>

            <div id="cartaLogin">
                <div class="d-flex align-items-center justify-content-center p-3">
                    <div class="content-avatar">
                        <img src="./public/img/logo.png" alt="logo" class="avatar">
                    </div>
                </div>

                <form action="<?= LOCAL_DIR ?>Login" class="mt-4" method="post" id="loginForm">
                    <div class="d-grid gap-4 mb-5">
                        <div class="d-flex flex-column align-items-center">
                            <label class="form-label fw-bold text-white">Cedula</label>
                            <input type="text" class="inputLogin text-center" name="cedula" maxlength="8" required>
                        </div>

                        <div class="d-flex flex-column align-items-center">
                            <label class="form-label text-white fw-bold">Clave</label>
                            <input type="password" class="inputLogin text-center" name="clave" maxlength="10" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" id="botonLogin" class="btn w-auto text-white">
                            INICIAR SESION
                        </button>
                    </div>
                </form>

                <?php if ($loginFails): ?>
                    <div class="alert alert-light" role="alert">
                        <p class="text-danger text-center">La cédula o la clave son incorrectos</p>
                    </div>
                <?php endif ?>

                <div class="d-flex justify-content-center mt-3 gap-4">
                    <a class="text-center text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#modalRecovery">Recuperar contraseña</a>
                    <a class="text-center text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#modalRegister">Registrarse</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Recuperar password -->
<div class="modal fade" id="modalRecovery" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recuperar contraseña</h5>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="d-grid">
                        <label class="form-label fw-bold text-center">Ingrese su cedula</label>
                        <input type="text" class="form-control" name="cedulaRecovery" id="cedulaRecovery">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="verificarCedula">Verificar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPreguntaRecovery" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pregunta de seguridad</h5>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="d-grid">
                        <label class="form-label fw-bold text-center" id="preguntaRecovery"></label>
                        <input type="text" class="form-control" id="respuestaRecovery">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="enviarRecovery">Enviar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="modalRegister" tabindex="-1" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro de usuario</h5>
            </div>
            <div class="modal-body">
                <form method="post" id="registerForm">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="email" class="form-control" id="nombre" name="nombre">
                                <small id="nombreError" class="form-text text-danger d-none">Los caracteres ingresados
                                    son invalidos</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="form-label">Apellido</label>
                                <input type="email" class="form-control" id="apellido" name="apellido">
                                <small id="apellidoError" class="form-text text-danger d-none">Los caracteres ingresados
                                    son invalidos</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="form-label">Telefono</label>
                                <input type="number" class="form-control" id="telefono" name="telefono">
                                <small id="telefonoError" class="form-text text-danger d-none">Telefono ingresado
                                    invalido</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="form-label">Cedula</label>
                                <input type="number" class="form-control" id="cedula" name="cedula">
                                <small id="cedulaError" id="emailHelp" class="form-text text-danger d-none">Cedula
                                    ingresada invalida</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="form-label">Sexo</label>
                                <select class="form-control" id="sexo" name="sexo">
                                    <option value="" selected>Seleccione</option>
                                    <option value="hombre">Hombre</option>
                                    <option value="mujer">Mujer</option>
                                </select>
                                <small id="sexoError" class="form-text text-danger d-none">Seleccion invalida</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small id="passwordError" class="form-text text-danger d-none">Los caracteres ingresados
                                    son invalidos y no pueden estar vacios</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="form-label">Ingrese nuevamente la contraseña</label>
                                <input type="password" class="form-control" id="passwordRepeat" name="passwordRepeat">
                                <small id="passwordRepeatError" class="form-text text-danger d-none">La contraseña no
                                    coincide</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento">
                                <small id="fechaNacimientoError" class="form-text text-danger d-none">La fecha ingresada es
                                    invalida</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-2">
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
                    </div>

                    <div class="row mt-4 justify-content-center">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Direccion</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                                <small id="direccionError" class="form-text text-danger d-none">Los caracteres ingresados son
                                    invalidos y no puede estar vacio</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Pregunta de seguridad</label>
                                <input type="text" class="form-control" id="preguntaSecurity" name="preguntaSecurity">
                                <small id="preguntaSecurityError" class="form-text text-danger d-none">Los caracteres
                                    ingresados son invalidos y no puede estar vacio</small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Respuesta de la pregunta de seguridad</label>
                                <input type="email" class="form-control" id="respuestaSecurity"
                                    name="respuestaSecurity">
                                <small id="respuestaSecurityError" class="form-text text-danger d-none">Los caracteres
                                    ingresados son invalidos y no puede estar vacio</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="enviarRecovery">Enviar</button>
            </div>
        </div>
    </div>
</div>