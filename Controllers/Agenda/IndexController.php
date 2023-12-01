<?php
require_once "Models/Evento.php";
necesitaAutenticacion();

/** @var Usuario */
$usuario = $_SESSION['usuario'];
$Evento = new Evento();


if (isset($_GET['listarEventos'])) {



    $Eventos = $Evento->listar_Eventos();

    echo json_encode($Eventos);

    die();
}


renderView(); 
?>