<?php $_layout = "Login" ?>

<div class="container-fluid" id="loginMain">
    <div class="row h-100">
        <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center align-items-center flex-column">
            <div class="d-flex flex-column text-center mb-3">
                <h1 id="bienvenida1">INICIO DE SESION DE USUARIO</h1>
                <h3 id="bienvenida2">Bienvenidos al sistema Llamas de Fuego</h3>
            </div>

            <div class="card" id="cartaLogin">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="content-avatar">
                            <img src="./public/img/logo.png" alt="logo" class="avatar">
                        </div>
                    </div>

                    <form action="<?= LOCAL_DIR ?>Login" class="mt-4" method="post" id="loginForm">
                        <div class="d-grid gap-4 mb-5">
                            <div class="d-flex flex-column align-items-center">
                                <label class="form-label text-white fw-bold">Cedula</label>
                                <input type="text" class="inputLogin" name="cedula" required>
                            </div>

                            <div class="d-flex flex-column align-items-center">
                                <label class="form-label text-white fw-bold">Clave</label>
                                <input type="password" class="inputLogin" name="clave" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" id="botonLogin" class="btn w-auto text-white">
                                INICIAR SESION
                            </button>
                        </div>
                    </form>

                    <?php if ($loginFails) : ?>
                        <div class="alert alert-light" role="alert">
                            <p class="text-danger text-center">La cédula o la clave son incorrectos</p>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>