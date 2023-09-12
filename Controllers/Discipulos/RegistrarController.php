<?php
require_once "Models/Discipulo.php";

necesitaAutenticacion();

requierePermisos("registrarDiscipulos");

$Discipulo = new Discipulo();

if (isset($_POST['registrar'])) {

    $asisCrecimiento = $_POST['asisCrecimiento'];
    $asisFamiliar = $_POST['asisFamiliar'];
    $idConsolidador = $_POST['idConsolidador'];
    $idcelulaconsolidacion = $_POST['idcelulaconsolidacion'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $estadoCivil = $_POST['estadoCivil'];
    $motivo = $_POST['motivo'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $fechaConvercion = $_POST['fechaConvercion'];


    $Discipulo->registrar_discipulo(
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idcelulaconsolidacion,
        $cedula,
        $nombre,
        $apellido,
        $telefono,
        $direccion,
        $estadoCivil,
        $motivo,
        $fechaNacimiento,
        $fechaConvercion
    );

    die();
}


if (isset($_GET['listaConsolidador'])) {

    $ListaConsolidador = $Discipulo->listar_consolidador();

    echo json_encode($ListaConsolidador);

    die();
}



if (isset($_GET['listarcelulas'])) {

    $listacelulas = $Discipulo->listar_celulas();

    echo json_encode($listacelulas);

    die();
}


renderView();
