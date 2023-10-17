<?php
global $viewScripts;
global $viewStyles;

/** @var ?Usuario */
$usuario = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= LOCAL_DIR ?>public/lib/fullcalendar/fullcalendar.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="<?= LOCAL_DIR ?>public/lib/fullcalendar/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= LOCAL_DIR ?>public/lib/fullcalendar/home.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/utilities.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/site.css">
    <?php if (!empty($viewStyles)) : ?>
        <?php foreach ($viewStyles as $css) : ?>
            <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/<?= $css ?>">
        <?php endforeach ?>
    <?php endif ?>

    <?php if (!empty($title)) : ?>
        <title><?= $title ?> - <?= APP_NAME ?></title>
    <?php else : ?>
        <title><?= APP_NAME ?></title>
    <?php endif ?>
</head>

<body>
    <!-- Header -->
    <header class="p-3 bg-dark text-white sticky-top" id="header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a class="d-flex gap-2 align-items-center navbar-brand" href="<?= LOCAL_DIR ?>">
                    <img src="/AppwebMVC/public/img/logo-32.png" width="32" height="32" class="d-inline-block">
                    <span class="fs-4 fw-semibold"><?= APP_NAME ?></span>
                </a>
                <div class="text-end">
                    <?php if (isset($_SESSION['usuario'])) : ?>
                        <!-- <button class="btn btn-dark">
                            <i class="fa-solid fa-message"></i>
                            0
                        </button> -->
                        <button class="btn btn-dark me-2">
                            <i class="fa-solid fa-bell"></i>
                            0
                        </button>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-large me-1"></i>
                                <?= $usuario->getNombre() ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-key fa-fw me-2"></i>
                                        Cambiar clave
                                    </a>
                                </li>
                                <li>
                                    <?php if (true/*$usuario->tienePermiso("cambiarSede")*/): ?>
                                        <a class="dropdown-item" href="#">
                                            <i class="fa-solid fa-arrows-rotate fa-fw me-2"></i>
                                            Elegir sede
                                        </a>
                                    <?php endif ?>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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

    <!-- Menu lateral -->
    <?php require_once "Views/_Plantillas/_MenuLateral.php" ?>

    <!-- Contenido principal -->
    <main id="main">
        <!-- Imprime alertas de exito o error -->
        <div id="alerts-section">
            <?php if (!empty($_SESSION['exitos'])) : ?>
                <?php foreach ($_SESSION['exitos'] as $alerta) : ?>
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <?= $alerta ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach ?>
                <?php unset($_SESSION['exitos']) ?>
            <?php endif ?>
            <?php if (!empty($_SESSION['errores'])) : ?>
                <?php foreach ($_SESSION['errores'] as $alerta) : ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        <?= $alerta ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach ?>
                <?php unset($_SESSION['errores']) ?>
            <?php endif ?>
        </div>

        <!-- Imprime la vista -->
        <?= $GLOBALS['view'] ?>
    </main>

    
    <script src ="<?= LOCAL_DIR ?>public/lib/fullcalendar/jquery-3.0.0.min.js"> </script>
    <script src="<?= LOCAL_DIR ?>public/lib/fullcalendar/popper.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/fullcalendar/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?= LOCAL_DIR ?>public/lib/fullcalendar/moment.min.js"></script>	
    <script type="text/javascript" src="<?= LOCAL_DIR ?>public/lib/fullcalendar/fullcalendar.min.js"></script>
    <script src='<?= LOCAL_DIR ?>public/lib/fullcalendar/es.js'></script>
    <script src="<?= LOCAL_DIR ?>public/js/utilities.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/site.js"></script>
    <?php if (!empty($viewScripts)) : ?>
        <?php foreach ($viewScripts as $script) : ?>
            <script src="<?= LOCAL_DIR ?>public/js/<?= $script ?>"></script>
        <?php endforeach ?>
    <?php endif ?>
</body>

</html>