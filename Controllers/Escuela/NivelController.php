<?php
require_once "Models/Nivel.php";
require_once "Models/Moduloeid.php";



necesitaAutenticacion();

requierePermiso("eid", "consultar");

$usuarioSesion = $_SESSION['usuario'];

$Nivel = new Nivel(); 


if (isset($_POST['cargar_data'])) {
    requierePermiso("eid", "actualizar");


    $idModuloEid = $_POST['idModulo'];
    
    $Lista = $Nivel->listarNiveles($idModuloEid);
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

    $idModuloEid = $_POST['idModulo'];
    $nombre = trim(strtolower($_POST['nombre']));
   

    $Nivel->registrarNivel($nombre, $idModuloEid);

    die();
}

if (isset($_POST['eliminar'])) {

    requierePermiso("eid", "eliminar");

    $id = $_POST['id'];
    $idModuloEid = $_POST['idModulo'];
    
    $Nivel->eliminarNivel($id, $idModuloEid);

    die();
}

if (isset($_POST['editar'])) {

    requierePermiso("eid", "actualizar");

   
    $id = $_POST['idNivel'];
    $nombre = trim(strtolower($_POST['nombre']));
 
    $Nivel->editarNivel($id, $nombre);

    die();
}



renderView();
?>