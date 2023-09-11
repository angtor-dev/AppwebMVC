<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaConsolidacion.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

$CelulaConsolidacion = new CelulaConsolidacion();


if (!$usuarioSesion->tienePermiso("registrarCelulaConsolidacion")) {
    $_SESSION['errores'][] = "No seposee permiso para registrar Sede.";
    redirigir("/AppwebMVC/Home/");
}

if (isset($_POST['registrar'])) {

    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);

    $CelulaConsolidacion->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $CelulaConsolidacion->validacion_existencia($nombre, $id = '');
    $CelulaConsolidacion->registrar_CelulaConsolidacion($nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}


if (isset($_GET['listaLideres'])) {

    $ListaLideres = $CelulaConsolidacion->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}

if (isset($_GET['listaTerritorio'])) {

    $Listaterritorio = $CelulaConsolidacion->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

renderView();
