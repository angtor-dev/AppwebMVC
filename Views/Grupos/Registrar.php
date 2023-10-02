<?php
/** @var ?Grupo $grupo */
/** @var Usuario[] $profesores */
/** @var NivelCrecimiento[] $niveles */
/** @var Subnivel[] $subniveles */
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
                value="<?= @$grupo?->getNombre() ?>">
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Nivel de crecimiento</label>
                <select name="idNivelCrecimiento" id="idNivelCrecimiento" class="form-select" onchange="selectNivelHandler(this)">
                    <option value="0">Elige un nivel de crecimiento</option>
                    <?php foreach ($niveles as $nivel): ?>
                        <option value="<?= $nivel->id ?>" <?= $nivel->id == @$grupo?->subnivel->nivelCrecimiento->id ? "selected" : "" ?>>
                            <?= $nivel->getNombre() ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Subnivel</label>
                <select name="idSubnivel" id="idSubnivel" class="form-select">
                    <option value="0">Elige un subnivel</option>
                    <?php foreach ($subniveles as $subnivel): ?>
                        <option value="<?= $subnivel->id ?>" <?= $subnivel->id == @$grupo?->subnivel->id ? "selected" : ""  ?>
                            class="<?= isset($grupo) && $grupo->subnivel->nivelCrecimiento->id == $subnivel->nivelCrecimiento->id
                            ? "" : "d-none" ?>" data-id-nivel="<?= $subnivel->nivelCrecimiento->id ?>">
                            <?= $subnivel->getNombre() ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label fw-bold">Profesor</label>
                <select name="idProfesor" id="idProfesor" class="form-select">
                    <option value="0">Elige un profesor</option>
                    <?php foreach ($profesores as $profesor): ?>
                        <option value="<?= $profesor->id ?>" <?= $profesor->id == @$grupo?->profesor->id ? "selected" : ""  ?>>
                            <?= $profesor->getCedula()." - ".$profesor->getNombreCompleto() ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <a href="/AppwebMVC/Grupos" class="btn btn-secondary me-3">
                Cancelar
            </a>
            <button type="submit" class="btn btn-accent">
                Registrar
            </button>
        </div>
    </form>
</div>

<script>
    function selectNivelHandler(nivelEl) {
        const subniveles = document.querySelectorAll("#idSubnivel option")
        subniveles.forEach(subnivelEl => {
            if (subnivelEl.dataset.idNivel == nivelEl.value) {
                subnivelEl.classList.remove('d-none')
            } else {
                subnivelEl.classList.add('d-none')
            }
        });
    }
</script>