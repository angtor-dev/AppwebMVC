<?php
/** @var Grupo $grupo */
$title = "Registrar grupo";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Registrar grupo para la E.I.D.</h4>
</div>

<div class="container-fluid">
    <form action="/AppwebMVC/Grupos/Registrar" method="post" id="formRegistrarGrupo">
        <div class="mb-3">
            <label class="form-label fw-bold">Nombre del grupo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required
                value="<?= @$grupo?->nombre ?>">
        </div>
        <div class="row mb-3">
            <div class="col-lg-6">
                <label class="form-label fw-bold">Nivel de crecimiento</label>
                <select name="test" id="test" class="form-select">
                    <option value="">Test</option>
                    <option value="">Test</option>
                    <option value="">Test</option>
                </select>
            </div>
            <div class="col-lg-6">
                <label class="form-label fw-bold">Nivel (grado)</label>
                <select name="test" id="test" class="form-select">
                    <option value="">Test</option>
                    <option value="">Test</option>
                    <option value="">Test</option>
                </select>
            </div>
        </div>
    </form>
</div>