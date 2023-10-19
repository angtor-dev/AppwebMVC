<?php
global $viewScripts;
global $viewStyles;

$usuario = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
/** @var ?Usuario */
$usuario = is_null($usuario) ? null : Usuario::cargar($usuario->id);
$cantNotif = 0;
if (isset($_SESSION['usuario'])) {
    foreach ($usuario->notificaciones as $notif) {
        if (!$notif->getVisto()) {
            $cantNotif++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= LOCAL_DIR ?>public/img/logo-32.png" type="image/x-icon">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/datatables/choicesjs/choices.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/quill/quill.snow.css">
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
                        <div class="dropdown-center d-inline-block">
                            <?php
                            $classBtn = $cantNotif > 0
                                ? "btn btn-accent me-2 dropdown-toggle"
                                : "btn btn-dark me-2 dropdown-toggle";
                            ?>
                            <button class="<?= $classBtn ?>" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false" id="btn-notif">
                                <i class="fa-solid fa-bell"></i>
                                <span id="contadorNotif"><?= $cantNotif ?></span>
                            </button>
                            <div class="dropdown-menu" id="notificaciones" style="width: 350px;">
                                <div class="px-3" style="font-size: 14px;"><h5>Notificaciones</h5></div>
                                <?php if (count($usuario->notificaciones) == 0): ?>
                                    <div class="py-4 px3 text-center">
                                        <h6>No tienes notificaciones nuevas.</h6>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($usuario->notificaciones as $notif): ?>
                                        <div class="notificacion px-3 py-2 border-top"
                                            <?php if (!$notif->getVisto()): ?>
                                                onclick="marcarNotificacion(<?= $notif->id ?>, this)"
                                            <?php endif ?>>
                                            <div class="d-flex justify-content-between">
                                                <h6><?= $notif->getTitulo() ?></h6>
                                                <?php if ($notif->getVisto()): ?>
                                                    <span class="tiempo text-secondary" style="font-size: 14px;"><?= $notif->getTiempo() ?></span>
                                                <?php else: ?>
                                                    <span class="tiempo text-primary fw-bold" style="font-size: 14px;"><?= $notif->getTiempo() ?></span>
                                                <?php endif ?>
                                            </div>
                                            <div>
                                                <span>
                                                    <?= $notif->getMensaje() ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-large me-1"></i>
                                <?= $usuario->getNombre() ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="<?= LOCAL_DIR ?>Usuarios/CambiarClave">
                                        <i class="fa-solid fa-key fa-fw me-2"></i>
                                        Cambiar clave
                                    </a>
                                </li>
                                <li>
                                    <!-- Ocultado por ahora -->
                                    <?php if ($usuario->tieneRol("Superusuario")) : ?>
                                        <!-- <a class="dropdown-item" href="#">
                                            <i class="fa-solid fa-arrows-rotate fa-fw me-2"></i>
                                            Elegir sede
                                        </a> -->
                                    <?php endif ?>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= LOCAL_DIR ?>Login/Logout">
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

    <script src="<?= LOCAL_DIR ?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/datatables/datatables.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/datatables/sweetalert2.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/datatables/choicesjs/choices.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/chartJs/chart.umd.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/quill/quill.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/utilities.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/site.js"></script>
    <?php if (!empty($viewScripts)) : ?>
        <?php foreach ($viewScripts as $script) : ?>
            <script src="<?= LOCAL_DIR ?>public/js/<?= $script ?>"></script>
        <?php endforeach ?>
    <?php endif ?>
</body>

</html>