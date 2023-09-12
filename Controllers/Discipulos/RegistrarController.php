<?php
require_once "Models/Discipulo.php";

necesitaAutenticacion();

requierePermisos("registrarDiscipulos");

$Discipulo = new Discipulo();

if (isset($_POST['registrar'])) {

    $asisCrecimiento = trim(strtolower($_POST['asisCrecimiento']));
    $asisFamiliar = trim(strtolower($_POST['asisFamiliar']));
    $idConsolidador = $_POST['idConsolidador'];
    $idCelulaConsolidacion = $_POST['idcelulaconsolidacion'];
    $cedula = $_POST['cedula'];
    $nombre = trim(strtolower($_POST['nombre']));
    $apellido = trim(strtolower($_POST['apellido']));
    $telefono = $_POST['telefono'];
    $direccion = trim(strtolower($_POST['direccion']));
    $estadoCivil = trim(strtolower($_POST['estadoCivil']));
    $motivo = trim(strtolower($_POST['motivo']));
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $fechaConvercion = $_POST['fechaConvercion'];


    $Discipulo->validacion_datos(
        [$idConsolidador, $idCelulaConsolidacion, $cedula, $telefono],
        [$direccion, $motivo],
        [$nombre, $apellido],
        [$fechaNacimiento, $fechaConvercion],
        $fechaNacimiento,
        [$asisCrecimiento, $asisFamiliar],
        $cedula,
        $estadoCivil,
        $telefono
    );
    $Discipulo->validacion_existencia($cedula, $id='');
    $Discipulo->registrar_discipulo(
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idCelulaConsolidacion,
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
