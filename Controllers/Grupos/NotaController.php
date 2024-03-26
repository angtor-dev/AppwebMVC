<?php
require_once "Models/Nota.php";
require_once "Models/Clase.php";



necesitaAutenticacion();


$usuarioSesion = $_SESSION['usuario'];

$Nota= new Nota();


if (isset($_POST['cargarNotas'])) {

   
    $idClase = $_POST['idClase'];


    $Lista = $Nota->listarNotas($idClase);
    //Variable json solamente para guardar el array de datos
    $json = array();

    if (!empty($Lista)) {
        foreach ($Lista as $key) {
            $json['data'][] = $key;
        }
    } else {

        $json['data'] = array();
    }

    echo json_encode($json);
    die();
}

if (isset($_POST['actualizarNota'])) {


    $idClase = $_POST['idClase'];  
    $idEstudiante = $_POST['idEstudiante'];
    $calificacion = $_POST['calificacion'];

        $Nota->editarNota($idClase, $idEstudiante, $calificacion);
        die();
}

renderView();