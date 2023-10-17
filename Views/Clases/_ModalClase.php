<?php /** @var Clase $clase */ ?>
<?php /** @var Grupo[] $grupos */ ?>

<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title"> <?= empty($clase->id) ? "Crear clase nueva" : "Actualizar clase" ?> </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body text-secondary">
    <form action="/AppwebMVC/Clases/<?= empty($clase->id) ? "Registrar" : "Actualizar" ?>" method="post" id="form-clase" class="row g-3 needs-validation" novalidate>
        <input type="hidden" name="id" id="input-id" value="<?= $clase->id ?? "0" ?>">
        <div class="col-sm-12">
            <label for="input-idGrupo">Grupo</label>
            <select class="form-select" name="idGrupo" id="input-idGrupo" required>
                <option value=""></option>
                <?php foreach ($grupos as $grupo): ?>
                    <?php if ($grupo->getEstado() == EstadosGrupo::Finalizado->value) continue; ?>
                    <option value="<?= $grupo->id ?>"
                        <?= $grupo->id == (!empty($clase->grupo) && $clase->grupo->id) ? "selected" : "" ?>>
                        <?= $grupo->getNombre() ?>
                    </option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback">
                Elige una opción
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-titulo">Título</label>
            <input class="form-control" type="text" name="titulo" id="input-titulo" required maxlength="50"
                value="<?= $clase->getTitulo() ?? "" ?>">
            <div class="invalid-feedback">
                Ingresa un título válido
            </div>
        </div>
        <div class="col-sm-12">
            <label for="input-objetivo">Objetivo</label>
            <textarea class="form-control" type="text" name="objetivo" id="input-objetivo" required maxlength="200"><?= $clase->getObjetivo() ?? "" ?></textarea>
            <div class="invalid-feedback">
                Objetivo inválido
            </div>
        </div>
    </form>
</div>
<div class="p-3 border-top">
    <button type="submit" class="btn btn-accent w-100" form="form-clase">Guardar</button>
</div>