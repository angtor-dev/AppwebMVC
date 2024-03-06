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

                <div class="d-grid mt-3">
                    <a class="text-center text-white" href="#" data-bs-toggle="modal"
                        data-bs-target="#modalCorreoRecovery">Recuperar contraseña</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Recuperar password -->
<div class="modal fade" id="modalCedulaRecovery" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
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
                <button type="button" class="btn btn-primary" id="enviarRecovery">Verificar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPreguntaRecovery" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
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
