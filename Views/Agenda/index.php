<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["agenda.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
$title = "Agenda"
?>

<style>

  #calendar {
    font-size: 14px;
    max-width: 1100px;
    margin: 0 auto;
  }

.fc-daygrid-day-number {
  color: black;
}

.fc-col-header-cell-cushion {
    color: black;
}

</style>  


<div id="calendar"></div>



   
<div class="modal fade" id="exampleModal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion del Territorio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                    <ul class="list-group">
                        <li class="list-group-item "><strong>Codigo:</strong>
                            <p id="inf_codigo"></p>
                        </li>
                        <li class="list-group-item "><strong>Nombre de Territorio:</strong>
                            <p id="inf_nombre"></p>
                        </li>
                        <li class="list-group-item "><strong>Lider a cargo:</strong>
                            <p id="inf_idLider"></p>
                        </li>
                        <li class="list-group-item ">
                            <h6><strong>Detalles:</strong></h6>
                            <p id="inf_detalles"></p>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

