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
                                <?= $usuario->nombre ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-key fa-fw me-2"></i>
                                        Cambiar clave
                                    </a>
                                </li>
                                <li>
                                    <?php if ($usuario->tienePermiso("cambiarSede")): ?>
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
    <aside id="sidebar" class="d-flex flex-column flex-shrink-0 py-3 px-2 bg-white
        position-fixed top-0 bottom-0 border-end">
        <div class="nav flex-column mb-auto pb-5">
            <a href="<?= LOCAL_DIR ?>" class="nav-link <?= empty($uriParts[0]) || strtolower($uriParts[0]) == "home" ? "active" : "" ?>">
                <i class="fa-solid fa-house-chimney fa-fw me-2"></i>
                <span>Inicio</span>
            </a>

            <div class="nav-link py-0 mt-3 text-uppercase">Iglesia</div>

            <!-- Anjhel -->
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-church fa-fw me-2"></i>
                    Sedes
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>Sedes/Registrar" class="nav-link">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                            Registrar
                        </a>
                        <a href="<?= LOCAL_DIR ?>Sedes/Listar" class="nav-link">
                            <i class="fa-solid fa-rectangle-list fa-fw"></i>
                            Listar
                        </a>
                    </div>
                </div>
            </div>
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-sharp fa-solid fa-earth-americas fa-fw me-2"></i>
                    Territorios
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>Territorios/Registrar" class="nav-link">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                            Registrar
                        </a>
                        <a href="<?= LOCAL_DIR ?>Territorios/Listar" class="nav-link">
                            <i class="fa-solid fa-rectangle-list fa-fw"></i>
                            Listar
                        </a>
                    </div>
                </div>
            </div>
            <a href="<?= LOCAL_DIR ?>Agenda/Index" class="nav-link">
                <i class="fa-solid fa-calendar fa-fw me-2"></i>
                Agenda
            </a>


            <div class="acordeon">
                <div class="nav-link py-0 mt-3 text-uppercase">Celulas</div>
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-people-roof fa-fw me-2"></i>
                    Familiar
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>CelulaFamiliar/Registrar" class="nav-link">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                            Registrar
                        </a>
                        <a href="<?= LOCAL_DIR ?>CelulaFamiliar/Listar" class="nav-link">
                            <i class="fa-solid fa-rectangle-list fa-fw"></i>
                            Listar
                        </a>
                        <a href="<?= LOCAL_DIR ?>CelulaFamiliar/Reunion" class="nav-link">
                            <i class="fa-solid fa-clipboard-user fa-fw"></i>
                            Listar Reuniones
                        </a>
                    </div>
                </div>
            </div>
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-seedling fa-fw me-2"></i>
                    Crecimiento
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>CelulaCrecimiento/Registrar" class="nav-link">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                            Registrar
                        </a>
                        <a href="<?= LOCAL_DIR ?>CelulaCrecimiento/Listar" class="nav-link">
                            <i class="fa-solid fa-rectangle-list fa-fw"></i>
                            Listar
                        </a>
                        <a href="<?= LOCAL_DIR ?>CelulaCrecimiento/Reunion" class="nav-link">
                            <i class="fa-solid fa-clipboard-user fa-fw"></i>
                            Listar Reuniones
                        </a>
                    </div>
                </div>
            </div>
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-people-line fa-fw me-2"></i>
                    Consolidación
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>CelulaConsolidacion/Registrar" class="nav-link">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                            Registrar
                        </a>
                        <a href="<?= LOCAL_DIR ?>CelulaConsolidacion/Listar" class="nav-link">
                            <i class="fa-solid fa-rectangle-list fa-fw"></i>
                            Listar
                        </a>
                        <a href="<?= LOCAL_DIR ?>CelulaConsolidacion/Reunion" class="nav-link">
                            <i class="fa-solid fa-clipboard-user fa-fw"></i>
                            Listar Reuniones
                        </a>
                    </div>
                </div>
            </div>
            <div class="acordeon">
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-clipboard-user fa-fw me-2"></i>
                    Discípulos
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <a href="<?= LOCAL_DIR ?>Discipulos/Registrar" class="nav-link">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                            Registrar
                        </a>
                        <a href="<?= LOCAL_DIR ?>Discipulos/Listar" class="nav-link">
                            <i class="fa-solid fa-rectangle-list fa-fw"></i>
                            Listar
                        </a>
                    </div>
                </div>
            </div>
            
            <?php if ($usuario->tienePermiso("consultarNivelesCrecimiento") || $usuario->tieneRol("Profesor")
                || $usuario->tieneRol("Estudiante")): ?>
                <div class="nav-link py-0 mt-3 text-uppercase">Escuela</div>
                <?php if ($usuario->tienePermiso("consultarNivelesCrecimiento")): ?>
                    <div class="acordeon <?= strtolower($uriParts[0]) == "nivelescrecimiento" ? "show" : "" ?>">
                        <a href="#" class="nav-link acordeon-toggle <?= strtolower($uriParts[0]) == "nivelescrecimiento" ? "active" : "" ?>">
                            <i class="fa-solid fa-graduation-cap fa-fw me-2"></i>
                            Niv. de crecimiento
                        </a>
                        <div class="acordeon-body">
                            <div class="acordeon-items">
                                <a href="<?= LOCAL_DIR ?>NivelesCrecimiento"
                                    class="nav-link <?= strtolower($uriParts[0]) == "nivelescrecimiento" && empty($uriParts[1]) ? "active" : "" ?>">
                                    <i class="fa-solid fa-rectangle-list fa-fw"></i>
                                    Listar
                                </a>
                                <a href="<?= LOCAL_DIR ?>NivelesCrecimiento/Registrar"
                                    class="nav-link <?= (strtolower($uriParts[0]) == "nivelescrecimiento" && strtolower($uriParts[1]) == "registrar") ? "active" : "" ?>">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    Registrar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($usuario->tieneRol("Profesor") || $usuario->tieneRol("Superusuario")): ?>
                    <div class="acordeon <?= strtolower($uriParts[0]) == "grupos" ? "show" : "" ?>">
                        <a href="#" class="nav-link acordeon-toggle <?= strtolower($uriParts[0]) == "grupos" ? "active" : "" ?>">
                            <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>
                            Grupos
                        </a>
                        <div class="acordeon-body">
                            <div class="acordeon-items">
                                <a href="<?= LOCAL_DIR ?>Grupos"
                                    class="nav-link <?= strtolower($uriParts[0]) == "grupos" && empty($uriParts[1]) ? "active" : "" ?>">
                                    <i class="fa-solid fa-rectangle-list fa-fw"></i>
                                    Listar
                                </a>
                                <a href="<?= LOCAL_DIR ?>Grupos/Registrar"
                                    class="nav-link <?= (strtolower($uriParts[0]) == "grupos" && strtolower($uriParts[1]) == "registrar") ? "active" : "" ?>">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    Registrar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($usuario->tieneRol("Superusuario")): ?>
                    <div class="acordeon <?= strtolower($uriParts[0]) == "inscripciones" ? "show" : "" ?>">
                        <a href="#" class="nav-link acordeon-toggle">
                            <i class="fa-solid fa-clipboard-list fa-fw me-2"></i>
                            Inscripciones
                        </a>
                        <div class="acordeon-body">
                            <div class="acordeon-items">
                                <a href="#"
                                    class="nav-link <?= strtolower($uriParts[0]) == "inscripciones" && empty($uriParts[1]) ? "active" : "" ?>">
                                    <i class="fa-solid fa-rectangle-list fa-fw"></i>
                                    Listar
                                </a>
                                <a href="#"
                                    class="nav-link <?= (strtolower($uriParts[0]) == "inscripciones" && strtolower($uriParts[1]) == "registrar") ? "active" : "" ?>">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    Registrar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($usuario->tieneRol("Superusuario") || $usuario->tieneRol("Estudiante")): ?>
                    <div class="acordeon <?= strtolower($uriParts[0]) == "inscripciones" ? "show" : "" ?>">
                        <a href="#" class="nav-link acordeon-toggle">
                            <i class="fa-solid fa-chalkboard-user fa-fw me-2"></i>
                            Clases
                        </a>
                        <div class="acordeon-body">
                            <div class="acordeon-items">
                                <a href="#"
                                    class="nav-link <?= strtolower($uriParts[0]) == "inscripciones" && empty($uriParts[1]) ? "active" : "" ?>">
                                    <i class="fa-solid fa-rectangle-list fa-fw"></i>
                                    Listar
                                </a>
                                <a href="#"
                                    class="nav-link <?= (strtolower($uriParts[0]) == "inscripciones" && strtolower($uriParts[1]) == "registrar") ? "active" : "" ?>">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    Registrar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($usuario->tieneRol("Superusuario")): ?>
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-book fa-fw me-2"></i>
                        Notas
                    </a>
                <?php endif ?>
            <?php endif ?>
            
            <?php if ($usuario->tienePermiso("consultarUsuarios") || $usuario->tienePermiso("consultarRoles")
                || $usuario->tienePermiso("consultarBitacora")): ?>
                <div class="nav-link py-0 mt-3 text-uppercase">Sistema</div>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-chart-pie fa-fw me-2"></i>
                    Estadisticas
                </a>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-file fa-fw me-2"></i>
                    Reportes
                </a>
                <?php if ($usuario->tienePermiso("consultarUsuarios")): ?>
                    <a href="<?= LOCAL_DIR ?>Usuarios" class="nav-link <?= strtolower($uriParts[0]) == "usuarios" ? "active" : "" ?>">
                        <i class="fa-solid fa-user fa-fw me-2"></i>
                        Usuarios
                    </a>
                <?php endif ?>
                <div class="acordeon <?= strtolower($uriParts[0]) == "seguridad" ? "show" : "" ?>">
                    <?php if ($usuario->tienePermiso("consultarRoles") || $usuario->tienePermiso("consultarBitacora")): ?>
                        <a href="#" class="nav-link acordeon-toggle">
                            <i class="fa-solid fa-lock fa-fw me-2"></i>
                            Seguridad
                        </a>
                        <div class="acordeon-body">
                            <div class="acordeon-items">
                                <?php if ($usuario->tienePermiso("consultarRoles")): ?>
                                    <a href="<?= LOCAL_DIR ?>Seguridad/Roles" class="nav-link <?= strtolower($uriParts[1] ?? "") == "roles" ? "active" : "" ?>">
                                        <i class="fa-solid fa-key fa-fw me-2"></i>
                                        Roles y permisos
                                    </a>
                                <?php endif ?>
                                <?php if ($usuario->tienePermiso("consultarBitacora")): ?>
                                    <a href="<?= LOCAL_DIR ?>Seguridad/Bitacora" class="nav-link <?= strtolower($uriParts[1] ?? "") == "bitacora" ? "active" : "" ?>">
                                        <i class="fa-solid fa-table-list fa-fw me-2"></i>
                                        Bitacora
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>
    </aside>

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