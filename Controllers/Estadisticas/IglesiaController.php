<?php
require_once "Models/Territorio.php";
require_once "Models/Celulas.php";
require_once "Models/Sede.php";
require_once "Models/Discipulo.php";

necesitaAutenticacion();

$Territorio = new Territorio();
$Celulas = new Celulas();
$Sede = new Sede();
$Discipulo = new Discipulo();


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

if (isset($_GET['cantidad_celulas_territorio'])) {
    $resultado = $Territorio->cantidad_celulas_territorios();
    echo json_encode($resultado);
    die();
}

if (isset($_GET['lideres_cantidad_celulas'])) {
    $tipo = trim(strtolower($_GET['tipo']));
    $resultado = $Celulas->lideres_cantidad_celulas($tipo);
    echo json_encode($resultado);
    die();
}

if (isset($_GET['listar_celulas'])) {
    $tipo = trim(strtolower($_GET['tipo']));
    $resultado = $Celulas->listar_celulas($tipo);
    echo json_encode($resultado);
    die();
}

if (isset($_GET['asistencias_reuniones_celulas'])) {
    $idCelula = trim($_GET['idCelula']);
    $tipo = trim(strtolower($_GET['tipo']));
    $resultado = $Celulas->asistencias_reuniones_celulas($idCelula, $tipo);
    echo json_encode($resultado);
    die();
}

if (isset($_GET['discipulos_consolidados_fecha'])) {
    
    $resultado = $Discipulo->discipulos_consolidados_fecha();
    echo json_encode($resultado);
    die();
}



renderView();
