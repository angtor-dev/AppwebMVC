<?php
require_once "Models/Territorio.php";
require_once "Models/Celulas.php";
require_once "Models/Sede.php";

necesitaAutenticacion();

$Territorio = new Territorio();
$Celulas = new Celulas();
$Sede = new Sede();


if (isset($_GET['cantidad_celulas_sede'])) {
    $resultado = $Sede->cantidad_celulas_sedes();
    echo json_encode($resultado);
    die();
}

if (isset($_GET['cantidad_territorios_sede'])) {
    $resultado = $Sede->cantidad_territorios_sedes();
    echo json_encode($resultado);
    die();
}

if (isset($_GET['cantidad_sedes_fecha'])) {
    $resultado = $Sede->cantidad_sedes_fecha();
    echo json_encode($resultado);
    die();
}



renderView();
