<?php
global $viewStyles;
global $viewScripts;

$title = "Estadisticas Iglesia";

$viewScripts = ["estadisticas-iglesia.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-4">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos sedes</h5>
                    <button class="btn btn-primary" id="botonSede1" data-bs-toggle="modal" data-bs-target="#modal1">Cantidad de celulas por sedes</button>
                    <button class="btn btn-primary" id="botonSede2" data-bs-toggle="modal" data-bs-target="#modal1">Cantidad de territorios por sedes</button>
                    <button class="btn btn-primary" id="botonSede3" data-bs-toggle="modal" data-bs-target="#modal1">Cantidad de sedes creadas</button>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos territorios</h5>
                    <button class="btn btn-primary" id="botonTerritorio1" data-bs-toggle="modal" data-bs-target="#modal1">Cantidad de celulas por territorios</button>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos celula familiar</h5>
                    <button class="btn btn-primary" id="botonCelulaFamiliar1" data-bs-toggle="modal" data-bs-target="#modal1">Lideres con mas celulas familiares</button>
                    <button class="btn btn-primary" id="botonCelulaFamiliar3" data-bs-toggle="modal" data-bs-target="#modal2">Asistencias de reuniones por celula</button>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos celula crecimiento</h5>
                    <button class="btn btn-primary" id="botonCelulaCrecimiento1" data-bs-toggle="modal" data-bs-target="#modal1">Lideres con mas celulas de crecimiento</button>
                    <button class="btn btn-primary" id="botonCelulaCrecimiento2" data-bs-toggle="modal" data-bs-target="#modal2">Asistencias de reuniones por celula</button>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos celula consolidacion</h5>
                    <button class="btn btn-primary" id="botonCelulaConsolidacion1" data-bs-toggle="modal" data-bs-target="#modal1">Lideres con mas celulas de consolidacion</button>
                    <button class="btn btn-primary" id="botonCelulaConsolidacion2" data-bs-toggle="modal" data-bs-target="#modal2">Asistencias de reuniones por celula</button>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center" data-bs-toggle="modal" data-bs-target="#modal1">Reportes estadisticos discipulos</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal1">Cantidad de sedes por año</button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="modal1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nombreReporte"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <canvas id="estadistica1"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal consulta celulas-->
<div class="modal fade" id="modal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="form-label mb-4 text-center" id="nombreSeleccionador"></h5>
                <select name="selectorCelulas" id="selectorCelulas">

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="button">Consultar</button>
            </div>
        </div>
    </div>
</div>