<?php
require_once "Models/Moduloeid.php";
require_once "Models/Eid.php";


necesitaAutenticacion();

requierePermiso("eid", "consultar");

$usuarioSesion = $_SESSION['usuario'];

$Moduloeid = new Moduloeid();


if (isset($_POST['cargar_data'])) {
    requierePermiso("eid", "actualizar");


    $idEid = $_POST['idEid'];
    
    $Lista = $Moduloeid->listarModulos($idEid);
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

if (isset($_POST['registrar'])) {

    requierePermiso("eid", "registrar");

    $idEid = $_POST['idEid'];
    $nombre = trim(strtolower($_POST['nombre']));
   

    $Moduloeid->registrarModuloEid($nombre, $idEid);

    die();
}

if (isset($_POST['eliminar'])) {

    requierePermiso("eid", "eliminar");

    $id = $_POST['id'];
    $idEid = $_POST['idEid'];
    
    $Moduloeid->eliminarModuloeid($id, $idEid);

    die();
}

if (isset($_POST['editar'])) {

    requierePermiso("eid", "actualizar");

   
    $id = $_POST['idModulo'];
    $nombre = trim(strtolower($_POST['nombre']));
 
    $Moduloeid->editarModuloEid($id, $nombre);

    die();
}



renderView();
?>