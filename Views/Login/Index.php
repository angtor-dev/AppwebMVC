<?php $_layout = "Login" ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4 mx-auto">
            <div class="card mt-5">
                <h5 class="card-header">Iniciar Sesión</h5>
                <div class="card-body">
                    <?php if ($loginFails): ?>
                        <div class="text-danger mb-3">
                            El correo o la clave son incorrectos
                        </div>
                    <?php endif ?>
                    <form action="/AppwebMVC/Login" method="post" id="loginForm">
                        <div class="row gap-3">
                            <div class="col-12">
                                <input type="email" class="form-control" placeholder="correo" name="correo" required>
                            </div>
                            <div class="col-12">
                                <input type="password" class="form-control" placeholder="clave" name="clave" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>