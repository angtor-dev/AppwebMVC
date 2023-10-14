<?php
/** @var ?Grupo $grupo */
/** @var Usuario[] $profesores */
/** @var NivelCrecimiento[] $niveles */
/** @var Subnivel[] $subniveles */
$title = "Registrar grupo";
$actualizaGrupo = !empty($grupo->id);

$subnivelActual = empty($grupo->subnivel) ? "" : $grupo->subnivel->id;
$nivelCrecimientoActual = empty($grupo->subnivel) ? "" : $grupo->subnivel->nivelCrecimiento->id;
$profesorActual = empty($grupo->profesor) ? "" : $grupo->profesor->id;
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold"><?= $actualizaGrupo ? "Modificar" : "Eliminar"; ?> grupo para la E.I.D.</h4>
</div>

<div class="container-fluid">
    <?php $action = $actualizaGrupo ? "/AppwebMVC/Grupos/Actualizar" : "/AppwebMVC/Grupos/Registrar"; ?>

    <form action="<?= $action ?>" method="post" id="formRegistrarGrupo">
    <?php if (!empty($grupo->id)): ?>
        <input type="hidden" value="<?= $grupo->id ?>" name="id">
    <?php endif ?>
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
                        <option value="<?= $nivel->id ?>" <?= $nivel->id == $nivelCrecimientoActual ? "selected" : "" ?>>
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
                        <option value="<?= $subnivel->id ?>" <?= $subnivel->id == $subnivelActual ? "selected" : ""  ?>
                            class="<?= isset($grupo) && $subnivelActual == $subnivel->nivelCrecimiento->id
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
                        <option value="<?= $profesor->id ?>" <?= $profesor->id == $profesorActual ? "selected" : ""  ?>>
                            <?= $profesor->getCedula()." - ".$profesor->getNombreCompleto() ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <?php if (!empty($grupo?->id)) : ?>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Elige un estado</option>
                        <?php foreach (EstadosGrupo::cases() as $estado): ?>
                            <option value="<?= $estado->value ?>"
                                <?= $estado->value == ($grupo->getEstado()) ? "selected" : "" ?>>
                                <?= $estado->name ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        <?php endif ?>
        <div class="d-flex justify-content-end">
            <a href="/AppwebMVC/Grupos" class="btn btn-secondary me-3">
                Cancelar
            </a>
            <button type="submit" class="btn btn-accent">
                <?php if (empty($grupo?->id)) : ?>
                    Registrar
                <?php else : ?>
                    Guardar
                <?php endif ?>
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