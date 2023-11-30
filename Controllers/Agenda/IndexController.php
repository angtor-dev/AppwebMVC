<?php
require_once "Models/Evento.php";

$Evento = new Evento();

if (isset($_GET['listarEventos'])) {



    $Eventos = $Evento->listar_Eventos();

    echo json_encode($Eventos);

    die();
}


renderView(); 
?>