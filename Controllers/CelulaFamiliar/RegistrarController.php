<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaFamiliar.php";

necesitaAutenticacion();

requierePermisos("registrarCelulaFamiliar");

$usuarioSesion = $_SESSION['usuario'];


$CelulaFamiliar = new CelulaFamiliar();

if (isset($_POST['registrar'])) {

    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);

    $CelulaFamiliar->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $CelulaFamiliar->validacion_existencia($nombre, $id='');
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
