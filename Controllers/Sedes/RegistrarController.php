<?php
require_once "Models/Sede.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

$Sede = new Sede();


if (!$usuarioSesion->tienePermiso("registrarSede")) {
    $_SESSION['errores'][] = "No seposee permiso para registrar Sede.";
    redirigir("/AppwebMVC/Sede/");
}

if (isset($_POST['registrar'])) {

    $idPastor = $_POST['idPastor'];
    $nombre = trim(strtolower($_POST['nombre']));
    $direccion = trim(strtolower($_POST['direccion']));
    $estado = trim($_POST['estado']);

    $Sede->validacion_datos($idPastor, $nombre, $direccion, $estado);
    $Sede->validacion_existencia($nombre, $idSede = '');
    $Sede->registrar_Sede($idPastor, $nombre, $direccion, $estado);
    die();
}


if (isset($_GET['listaPastores'])) {

    $ListaPastores = $Sede->listar_Pastores();

    echo json_encode($ListaPastores);

    die();
}

renderView();
