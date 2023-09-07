<?php
/** @var NivelCrecimiento[] $nivelesCrecimiento */
$title = "Niveles de Crecimiento";
?>

<div class="d-flex align-items-end justify-content-between mb-2">
    <h4 class="mb-0 fw-bold">Niveles de Crecimineto</h4>
    <div class="d-flex gap-3">
        <?php if (count($nivelesCrecimiento) != 0): ?>
            <div class="buscador">
                <input type="text" id="tabla-usuarios_search" class="form-control" placeholder="Buscar Nivel de Crecimiento">
            </div>
            <button class="btn btn-accent text-nowrap" onclick="abrirModalNivelCrecimiento()">
                <i class="fa-solid fa-plus"></i>
                Nuevo nivel
            </button>
        <?php endif ?>
    </div>
</div>

<?php if (count($nivelesCrecimiento) == 0): ?>
    <div class="card" style="padding: 120px;">
        <div class="d-flex flex-column align-items-center text-center">
            <h2>Sin niveles para mostrar</h2>
            <h6 class="mb-4">Parece que aun no se han registrado niveles de crecimiento para esta sede</h6>
            <?php if (true/* $usuario->tienePermiso('registrarNivelesCrecimiento') */): ?>
                <a href="/AppwebMVC/NivelesCrecimiento/CrearInicialesController" class="btn btn-accent">
                    Crear niveles iniciales
                </a>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>