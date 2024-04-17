<?php
require_once "Models/Eid.php";
require_once "Models/Nivel.php";
require_once "Models/Grupo.php";
require_once "Models/Moduloeid.php";


necesitaAutenticacion();

requierePermiso("eid", "consultar");

$usuarioSesion = $_SESSION['usuario'];

$Eid = new Eid();


if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Eid->listarEid();
    //Variable json solamente para guardar el array de datos
    $json = array();

    //Comrpobamos primero que la lista no este vacia
    if (!empty($Lista)) {
        //Si no esta vacia, empezara a recorrer cada columna de la consulta sql, es decir, los datos obtenidos
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

    $nombre = $_POST['nombre'];
    $edadMinima = empty($_POST['edadMinima']) ? '' : $_POST['edadMinima'];
    $edadMaxima = empty($_POST['edadMaxima']) ? '' : $_POST['edadMaxima'];
    $selectedEid = empty($_POST['selectedEid']) ? '' : $_POST['selectedEid'];
    $selectedRolR = empty($_POST['selectedRolR']) ? '' : $_POST['selectedRolR'];
    $selectedRolA = empty($_POST['selectedRolA']) ? '' : $_POST['selectedRolA'];

    
    // $Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    // $Celulas->validacion_existencia($nombre, $id='', $tipo, $idTerritorio);
    // $Celulas->valida_celulascantidad($idLider, $tipo, $id='');
    $Eid->registrarEid($nombre, $edadMinima, $edadMaxima, $selectedEid, $selectedRolR, $selectedRolA);

    die();
}

if (isset($_POST['eliminar'])) {

    requierePermiso("eid", "eliminar");

    $id = $_POST['id'];
    
    $Eid->eliminarEid($id);

    die();
}

if (isset($_POST['editar'])) {

    requierePermiso("eid", "actualizar");

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $edadMinima = empty($_POST['edadMinima']) ? '' : $_POST['edadMinima'];
    $edadMaxima = empty($_POST['edadMaxima']) ? '' : $_POST['edadMaxima'];
 
    $Eid->editarEid($id, $nombre, $edadMinima, $edadMaxima);

    die();
}

if (isset($_GET['listarEid'])) {

    requierePermiso("eid", "registrar");

   $Lista = $Eid->listarEid();

    echo json_encode($Lista);

    die();
}

if (isset($_GET['listarRoles'])) {

    requierePermiso("eid", "registrar");

   $roles = $Eid->listarRolesEid();


    echo json_encode($roles);

    die();
}



if (isset($_POST['cargarControlEid'])) {

    requierePermiso("eid", "actualizar");


    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $Lista = $Eid->listarEidV($id, $tipo);
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

if (isset($_POST['eliminarControlEid'])) {

    requierePermiso("eid", "actualizar");

    $idRequisito = $_POST['idRequisito'];
    $idEid = $_POST['idEid'];
    $tipo = $_POST['tipo'];

    $Eid->eliminarControlEid($idRequisito, $idEid, $tipo);

    die();
}

if (isset($_POST['listadoSV'])) {

    requierePermiso("eid", "actualizar");

    $id = $_POST['id'];
    $tipo = $_POST['tipo'];

   $roles = $Eid->listadoSV($id, $tipo);


    echo json_encode($roles);

    die();
}

if (isset($_POST['registrarControlEid'])) {

    requierePermiso("eid", "actualizar");

    $id = $_POST['id'];
    $array = $_POST['array'];
    $tipo = $_POST['tipo'];

    $Eid->registrarControlEid($id, $array, $tipo);

    die();
}

renderView();
?>