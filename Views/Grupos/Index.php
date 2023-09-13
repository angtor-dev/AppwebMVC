<?php
/** @var Grupo[] $grupos */
$title = "Grupos";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Grupos</h4>
    <div class="d-flex gap-3">
        <?php if (true/*$usuario->tienePermiso("registrarGrupos")*/): ?>
            <a href="/AppwebMVC/Grupos/Registrar" class="btn btn-accent text-nowrap">
                <i class="fa-solid fa-plus"></i>
                Nuevo grupo
            </a>
        <?php endif ?>
    </div>
</div>

