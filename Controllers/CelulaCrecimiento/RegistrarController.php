<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaCrecimiento.php";

necesitaAutenticacion();

requierePermisos("registrarCelulaCrecimiento");

$usuarioSesion = $_SESSION['usuario'];

$CelulaCrecimiento = new CelulaCrecimiento();

if (isset($_POST['registrar'])) {

    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);

    $CelulaCrecimiento->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $CelulaCrecimiento->validacion_existencia($nombre, $id = '');
    $CelulaCrecimiento->registrar_CelulaCrecimiento($nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}


if (isset($_GET['listaLideres'])) {

    $ListaLideres = $CelulaCrecimiento->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}

if (isset($_GET['listaTerritorio'])) {

    $Listaterritorio = $CelulaCrecimiento->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

renderView();
