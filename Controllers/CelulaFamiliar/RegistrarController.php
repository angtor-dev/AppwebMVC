<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaFamiliar.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

if (!$usuarioSesion->tienePermiso("registrarCelulaFamiliar")) {
    $_SESSION['errores'][] = "No posee permiso para registrar Celula Familiar.";
    redirigir("/AppwebMVC/Home/");
}

$CelulaFamiliar = new CelulaFamiliar();

if (isset($_POST['registrar'])) {

    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $idCoLider = $_POST['idCoLider'];
    $idTerritorio = $_POST['idTerritorio'];

    $CelulaFamiliar->registrar_CelulaFamiliar($nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}


if (isset($_GET['listaLideres'])) {

    $ListaLideres = $CelulaFamiliar->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}

if (isset($_GET['listaTerritorio'])) {

    $Listaterritorio = $CelulaFamiliar->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

renderView();
