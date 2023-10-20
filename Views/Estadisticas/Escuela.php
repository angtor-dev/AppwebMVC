<?php
$title = "Estadisticas EID";
agregarScript("estadisticas-escuela.js");
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos Niveles de Crecimiento</h5>
                    <button class="btn btn-primary" id="botonNivel1" data-bs-toggle="modal"
                        data-titulo="Grupos por Nivel de Crecimiento" data-bs-target="#modal-generico">Cantidad de grupos por niveles</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos de Grupos</h5>
                    <button class="btn btn-primary" id="botonGrupo1" data-bs-toggle="modal"
                        data-titulo="Cantidad de Inscripciones por mes" data-bs-target="#modal-generico">Inscripciones por mes</button>
                    <button class="btn btn-primary" id="botonGrupo2" data-bs-toggle="modal"
                        data-titulo="Cantidad de Estudiantes por grupo" data-bs-target="#modal-generico">Estudiantes por grupo</button>
                    <button class="btn btn-primary" id="botonGrupo3" data-bs-toggle="modal"
                        data-titulo="Cantidad de Grupos por Sede" data-bs-target="#modal-generico">Grupos por sede</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body d-flex justify-content-center flex-column gap-2">
                    <h5 class="text-center">Reportes estadisticos de Notas</h5>
                    <button class="btn btn-primary" id="botonNotas1" data-bs-toggle="modal"
                        data-titulo="Promedio de notas por grupo" data-bs-target="#modal-generico">Promedio de notas por grupo</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal generico -->
<div class="modal fade" id="modal-generico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
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