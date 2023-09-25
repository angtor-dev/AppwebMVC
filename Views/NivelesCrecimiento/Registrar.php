<?php
/** @var NivelCrecimiento $nivelCrecimiento */
$title = "Registrar Nivel de Crecimineto";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Registrar Nivel de Crecimiento</h4>
</div>

<div class="container-fluid">
    <form action="/AppwebMVC/NivelesCrecimiento/Registrar" method="post" id="formRegistrarNivel">
        <div class="mb-3">
            <label class="form-label fw-bold">Nombre del nivel</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required
                value="<?= @$nivelCrecimiento?->getNombre() ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Nivel (grado)</label>
            <input type="number" class="form-control" id="nivel" name="nivel" required min="1" max="99"
                value="<?= @$nivelCrecimiento?->getNivel() ?>">
        </div>
        <div class="d-flex justify-content-end">
            <a href="/AppwebMVC/NivelesCrecimiento" class="btn btn-secondary me-3">
                Cancelar
            </a>
            <button type="submit" class="btn btn-accent">
                Registrar
            </button>
        </div>
    </form>
</div>