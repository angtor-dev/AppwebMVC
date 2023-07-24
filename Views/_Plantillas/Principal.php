<?php
/** @var ?Usuario */
$usuario = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "" ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/utilities.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/site.css">
</head>

<body>
    <!-- Header -->
    <header class="p-3 bg-dark text-white" id="header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a class="d-flex gap-2 align-items-center navbar-brand" href="#">
                    <img src="/AppwebMVC/public/img/logo-32.png" width="32" height="32" class="d-inline-block">
                    <span class="fs-4 fw-semibold"><?= APP_NAME ?></span>
                </a>
                <div class="text-end">
                    <?php if (isset($_SESSION['usuario'])) : ?>
                        <button class="btn btn-dark">
                            <i class="fa-solid fa-message"></i>
                            0
                        </button>
                        <button class="btn btn-dark me-2">
                            <i class="fa-solid fa-bell"></i>
                            0
                        </button>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-large me-1"></i>
                                <?= $usuario->nombre ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-regular fa-circle-user fa-fw me-2"></i>
                                        Cuenta
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-regular fa-heart fa-fw me-2"></i>
                                        Preferecias
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= LOCAL_DIR ?>login/logout">
                                        <i class="fa-solid fa-right-from-bracket fa-fw me-2"></i>
                                        Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?= LOCAL_DIR ?>login" class="btn btn-primary">Iniciar sesión</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </header>

    <aside class="d-flex flex-column flex-shrink-0 p-3 bg-light
        position-fixed top-0 bottom-0" style="width: 230px;">
        <a href="/AppwebMVC" class="d-flex align-items-center
            mb-0 me-md-auto link-dark text-decoration-none">
            <span class="fs-4" style="height: 37px;"><?= APP_NAME ?></span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?= LOCAL_DIR ?>" class="nav-link active" aria-current="page">
                    <i class="fa-solid fa-house-chimney fa-fw me-2"></i>
                    Inicio
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link link-dark">
                    <i class="fa-solid fa-lock fa-fw me-2"></i>
                    Seguridad
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link link-dark">
                    <i class="fa-solid fa-church fa-fw me-2"></i>
                    Sedes
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link link-dark">
                    <i class="fa-solid fa-people-group fa-fw me-2"></i>
                    Celulas
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link link-dark">
                    <i class="fa-solid fa-graduation-cap fa-fw me-2"></i>
                    E.I.D.
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link link-dark">
                    <i class="fa-solid fa-calendar fa-fw me-2"></i>
                    Agenda
                </a>
            </li>
        </ul>
    </aside>

    <main style="margin-left: 230px;">
        <?= $GLOBALS['view'] ?>
    </main>

    <script src="<?= LOCAL_DIR ?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/utilities.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/site.js"></script>
</body>
</html>