<?php

require_once "Models/Usuario.php";
require_once "Models/Discipulo.php";





necesitaAutenticacion();

requierePermiso("estudiantes", "consultar");

$usuarioSesion = $_SESSION['usuario'];

$estudiantes = new Usuario();


if (isset($_GET['cargar_data'])) {
    requierePermiso("estudiantes", "consultar");

    
    $Lista = $estudiantes->listarEstudiantes(); 
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

if (isset($_POST['validarRegistrar'])) {

    requierePermiso("estudiantes", "registrar");

    $cedula = $_POST['cedula'];
   

    $json = $estudiantes->validarRegistrarEstudiante($cedula);  

    echo json_encode($json);

    die();
}

if (isset($_POST['registrar'])) {

    requierePermiso("estudiantes", "registrar");

    $id = $_POST['id'];
    $tipo = $_POST['tipo'];  

   $estudiantes->RegistrarEstudiante($id, $tipo);  

    die();
}



renderView();
?>