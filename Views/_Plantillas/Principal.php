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
    <link rel="shortcut icon" href="<?= LOCAL_DIR ?>public/img/logo-32.png" type="image/x-icon">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/utilities.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/site.css">
    <?php if (!empty($viewStyles)): ?>
        <?php foreach ($viewStyles as $css): ?>
            <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/<?= $css ?>">
        <?php endforeach ?>
    <?php endif ?>

    <?php if (!empty($title)): ?>
        <title><?= $title ?> - <?= APP_NAME ?></title>
    <?php else: ?>
        <title><?= APP_NAME ?></title>
    <?php endif ?>
</head>

<body>
    <!-- Header -->
    <header class="p-3 bg-dark text-white" id="header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a class="d-flex gap-2 align-items-center navbar-brand" href="<?= LOCAL_DIR ?>">
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

    <!-- Menu lateral -->
    <aside id="sidebar" class="d-flex flex-column flex-shrink-0 py-3 px-2 bg-white
        position-fixed top-0 bottom-0 border-end">
        <div class="nav flex-column mb-auto">
            <a href="<?= LOCAL_DIR ?>"
                class="nav-link <?= empty($uriParts[0]) || strtolower($uriParts[0]) == "home" ? "active" : "" ?>">
                <i class="fa-solid fa-house-chimney fa-fw me-2"></i>
                <span>Inicio</span>
            </a>

            <div class="nav-link py-0 mt-3 text-uppercase">Iglesia</div>
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-church fa-fw me-2"></i>    
                    Sedes
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-list fa-fw me-2"></i>
                            Listar
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-earth-americas fa-fw me-2"></i>
                            Territorios
                        </a>
                    </div>
                </div>
            </div>
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-people-group fa-fw me-2"></i>    
                    Celulas
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-people-line fa-fw me-2"></i>
                            de Consolidación
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-seedling fa-fw me-2"></i>
                            de Crecimiento
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-people-roof fa-fw me-2"></i>
                            Familiares
                        </a>
                    </div>
                </div>
            </div>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-users fa-fw me-2"></i>
                Discipulos
            </a>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-calendar fa-fw me-2"></i>
                Calendario Anual
            </a>
            
            <div class="nav-link py-0 mt-3 text-uppercase">Escuela</div>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-graduation-cap fa-fw me-2"></i>
                Niveles de crecimiento
            </a>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>
                Mis grupos
            </a>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-chalkboard-user fa-fw me-2"></i>
                Mis clases
            </a>
            
            <div class="nav-link py-0 mt-3 text-uppercase">Sistema</div>
            <a href="<?= LOCAL_DIR ?>Usuarios"
                class="nav-link <?= strtolower($uriParts[0]) == "usuarios" ? "active" : "" ?>">
                <i class="fa-solid fa-user fa-fw me-2"></i>
                Usuarios
            </a>
            <div class="acordeon <?= strtolower($uriParts[0]) == "seguridad" ? "show" : "" ?>">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-lock fa-fw me-2"></i>    
                    Seguridad
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>Seguridad/Roles"
                            class="nav-link <?= strtolower($uriParts[1]??"") == "roles" ? "active" : "" ?>">
                            <i class="fa-solid fa-key fa-fw me-2"></i>
                            Roles y permisos
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-table-list fa-fw me-2"></i>
                            Bitacora
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main id="main">
        <!-- Imprime alertas de exito o error -->
        <?php if (!empty($_SESSION['exitos'])): ?>
            <?php foreach ($_SESSION['exitos'] as $alerta): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $alerta ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach ?>
            <?php unset($_SESSION['exitos']) ?>
        <?php endif ?>
        <?php if (!empty($_SESSION['errores'])): ?>
            <?php foreach ($_SESSION['errores'] as $alerta): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $alerta ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach ?>
            <?php unset($_SESSION['errores']) ?>
        <?php endif ?>

        <!-- Imprime la vista -->
        <?= $GLOBALS['view'] ?>
    </main>

    <script src="<?= LOCAL_DIR ?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/datatables/datatables.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/utilities.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/site.js"></script>
    <?php if (!empty($viewScripts)): ?>
        <?php foreach ($viewScripts as $script): ?>
            <script src="<?= LOCAL_DIR ?>public/js/<?= $script ?>"></script>
        <?php endforeach ?>
    <?php endif ?>
</body>
</html>