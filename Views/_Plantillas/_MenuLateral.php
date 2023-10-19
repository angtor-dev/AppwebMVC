<?php

/** @var Usuario $usuario */
?>
<aside id="sidebar" class="d-flex flex-column flex-shrink-0 py-3 px-2 bg-white
    position-fixed top-0 bottom-0 border-end">
    <div class="nav flex-column mb-auto pb-5">
        <a href="<?= LOCAL_DIR ?>" class="nav-link <?= empty($uriParts[0]) || strtolower($uriParts[0]) == "home" ? "active" : "" ?>">
            <i class="fa-solid fa-house-chimney fa-fw me-2"></i>
            <span>Inicio</span>
        </a>

        <div class="nav-link py-0 mt-3 text-uppercase">Iglesia</div>

        <!-- Anjhel -->
        <?php if ($usuario->tienePermiso("sedes", "consultar")) : ?>
            <a href="<?= LOCAL_DIR ?>Sedes/Listar" class="nav-link">
                <i class="fa-solid fa-church fa-fw me-2"></i>
                Sedes
            </a>
        <?php endif ?>

        <?php if ($usuario->tienePermiso("territorio", "consultar")) : ?>
            <a href="<?= LOCAL_DIR ?>Territorios/Listar" class="nav-link">
                <i class="fa-sharp fa-solid fa-earth-americas fa-fw me-2"></i>
                Territorios
            </a>
        <?php endif ?>

        <a href="<?= LOCAL_DIR ?>Agenda/Index" class="nav-link">
            <i class="fa-solid fa-calendar fa-fw me-2"></i>
            Agenda
        </a>

        <div class="acordeon">
            <div class="nav-link py-0 mt-3 text-uppercase">Celulas</div>
            <?php if ($usuario->tienePermiso("celulaFamiliar", "consultar")) : ?>
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-people-roof fa-fw me-2"></i>
                    Familiar
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <?php if ($usuario->tienePermiso("celulaFamiliar", "registrar")) : ?>
                            <a href="<?= LOCAL_DIR ?>CelulaFamiliar" class="nav-link">
                                <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                Registrar
                            </a>
                        <?php endif ?>

                        <?php if ($usuario->tienePermiso("celulaFamiliar", "actualizar")) : ?>
                            <a href="<?= LOCAL_DIR ?>CelulaFamiliar/Reunion" class="nav-link">
                                <i class="fa-solid fa-clipboard-user fa-fw"></i>
                                Listar Reuniones
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="acordeon">
            <?php if ($usuario->tienePermiso("celulaCrecimiento", "consultar")) : ?>
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-seedling fa-fw me-2"></i>
                    Crecimiento
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <?php if ($usuario->tienePermiso("celulaCrecimiento", "registrar")) : ?>
                            <a href="<?= LOCAL_DIR ?>CelulaCrecimiento" class="nav-link">
                                <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                Registrar
                            </a>
                        <?php endif ?>

                        <?php if ($usuario->tienePermiso("celulaCrecimiento", "actualizar")) : ?>
                            <a href="<?= LOCAL_DIR ?>CelulaCrecimiento/Reunion" class="nav-link">
                                <i class="fa-solid fa-clipboard-user fa-fw"></i>
                                Listar Reuniones
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="acordeon">
            <?php if ($usuario->tienePermiso("celulaConsolidacion", "consultar")) : ?>
                <a href="#" class="nav-link acordeon-toggle">
                    <i class="fa-solid fa-people-line fa-fw me-2"></i>
                    Consolidación
                </a>
                <div class="acordeon-body">
                    <div class="acordeon-items">
                        <?php if ($usuario->tienePermiso("celulaConsolidacion", "registrar")) : ?>
                            <a href="<?= LOCAL_DIR ?>CelulaConsolidacion" class="nav-link">
                                <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                Registrar
                            </a>
                        <?php endif ?>
                        <?php if ($usuario->tienePermiso("celulaConsolidacion", "actualizar")) : ?>
                            <a href="<?= LOCAL_DIR ?>CelulaConsolidacion/Reunion" class="nav-link">
                                <i class="fa-solid fa-clipboard-user fa-fw"></i>
                                Listar Reuniones
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <?php if ($usuario->tienePermiso("celulaFamiliar", "consultar")) : ?>
            <a href="<?= LOCAL_DIR ?>Discipulos" class="nav-link">
                <i class="fa-solid fa-clipboard-user fa-fw me-2"></i>
                Discípulos
            </a>
        <?php endif ?>

        <?php if (
            $usuario->tienePermiso("nivelesCrecimiento", "consultar") || $usuario->tieneRol("Profesor")
            || $usuario->tieneRol("Estudiante")
        ) : ?>
            <div class="nav-link py-0 mt-3 text-uppercase">Escuela</div>
            <?php if ($usuario->tienePermiso("nivelesCrecimiento", "consultar")) : ?>
                <a href="<?= LOCAL_DIR ?>NivelesCrecimiento" class="nav-link <?= strtolower($uriParts[0]) == "nivelescrecimiento" ? "active" : "" ?>">
                    <i class="fa-solid fa-graduation-cap fa-fw me-2"></i>
                    Niv. de crecimiento
                </a>
            <?php endif ?>
            <?php if ($usuario->tieneRol("Profesor") || $usuario->tieneRol("Superusuario")) : ?>
                <a href="<?= LOCAL_DIR ?>Grupos" class="nav-link <?= strtolower($uriParts[0]) == "grupos" ? "active" : "" ?>">
                    <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>
                    Grupos
                </a>
            <?php endif ?>
            <?php if ($usuario->tienePermiso("inscripciones", "consultar")) : ?>
                <a href="<?= LOCAL_DIR ?>Inscripciones" class="nav-link <?= strtolower($uriParts[0]) == "inscripciones" ? "active" : "" ?>">
                    <i class="fa-solid fa-clipboard-list fa-fw me-2"></i>
                    Inscripciones
                </a>
            <?php endif ?>
            <?php if ($usuario->tieneRol("Superusuario") || $usuario->tieneRol("Estudiante")) : ?>
                <a href="<?= LOCAL_DIR ?>Clases" class="nav-link <?= strtolower($uriParts[0]) == "clases" ? "active" : "" ?>">
                    <i class="fa-solid fa-chalkboard-user fa-fw me-2"></i>
                    Clases
                </a>
            <?php endif ?>
            <?php if ($usuario->tieneRol("Superusuario")) : ?>
                <a href="<?= LOCAL_DIR ?>Notas" class="nav-link <?= strtolower($uriParts[0]) == "notas" ? "active" : "" ?>">
                    <i class="fa-solid fa-book fa-fw me-2"></i>
                    Notas
                </a>
            <?php endif ?>
        <?php endif ?>

        <?php if (
            $usuario->tienePermiso("usuarios", "consultar") || $usuario->tienePermiso("roles", "consultar")
            || $usuario->tienePermiso("bitacora", "consultar")
        ) : ?>
            <div class="nav-link py-0 mt-3 text-uppercase">Sistema</div>
            <?php if ($usuario->tieneRol("Superusuario")) : ?>
                <div class="acordeon">
                    <a href="#" class="nav-link acordeon-toggle">
                        <i class="fa-solid fa-chart-pie fa-fw me-2"></i>
                        Estadistica
                    </a>
                    <div class="acordeon-body">
                        <div class="acordeon-items">
                            <a href="<?= LOCAL_DIR ?>Estadisticas/Iglesia" class="nav-link">
                                <i class="fa-solid fa-chart-pie fa-fw me-2"></i>
                                Iglesia
                            </a>
                            <a href="<?= LOCAL_DIR ?>Estadisticas/Escuela" class="nav-link">
                                <i class="fa-solid fa-chart-pie fa-fw me-2"></i>
                                EID
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-file fa-fw me-2"></i>
                Reportes
            </a>
            <?php if ($usuario->tienePermiso("usuarios", "consultar")) : ?>
                <a href="<?= LOCAL_DIR ?>Usuarios" class="nav-link <?= strtolower($uriParts[0]) == "usuarios" ? "active" : "" ?>">
                    <i class="fa-solid fa-user fa-fw me-2"></i>
                    Usuarios
                </a>
            <?php endif ?>
            <div class="acordeon <?= strtolower($uriParts[0]) == "seguridad" ? "show" : "" ?>">
                <?php if ($usuario->tienePermiso("roles", "consultar") || $usuario->tienePermiso("bitacora", "consultar")) : ?>
                    <a href="#" class="nav-link acordeon-toggle">
                        <i class="fa-solid fa-lock fa-fw me-2"></i>
                        Seguridad
                    </a>
                    <div class="acordeon-body">
                        <div class="acordeon-items">
                            <?php if ($usuario->tienePermiso("roles", "consultar")) : ?>
                                <a href="<?= LOCAL_DIR ?>Seguridad/Roles" class="nav-link <?= strtolower($uriParts[1] ?? "") == "roles" ? "active" : "" ?>">
                                    <i class="fa-solid fa-key fa-fw me-2"></i>
                                    Roles y permisos
                                </a>
                            <?php endif ?>
                            <?php if ($usuario->tienePermiso("bitacora", "consultar")) : ?>
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