<?php
require_once "Models/Evento.php";

$Evento = new Evento();

if (isset($_GET['listarEventos'])) {



    $Eventos = $Evento->listar_Eventos();

    echo json_encode($Eventos);

    die();
}

if (isset($_GET['listaSedes'])) {



    $Sedes = $Evento->listar_Sedes();

    echo json_encode($Sedes);

    die();
}


if (isset($_POST['registroEventos'])) {

    $titulo = $_POST['titulo'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFinal = $_POST['fechaFinal'];
    $descripcion = $_POST['descripcion'];
    $sedes = $_POST['sedes'];

     $Evento->registrar_eventos($titulo, $fechaInicio, $fechaFinal, $descripcion, $sedes);



    die();
}




renderView(); 
?>