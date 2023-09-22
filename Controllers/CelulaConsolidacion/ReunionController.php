<?php
require_once "Models/CelulaConsolidacion.php";

necesitaAutenticacion();

requierePermiso("celulaConsolidacion", "registrar");

$CelulaConsolidacion = new CelulaConsolidacion();



if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variablelistar_reunioness
    $Lista = $CelulaConsolidacion->listar_reuniones();
    //Variable json solamente para guardar el array de datos
    $json = array();

    //Comrpobamos primero que la lista no este vacia
    if (!empty($Lista)) {
        //Si no esta vacia, empezara a recorrer cada columna de la consulta sql, es decir, los datos obtenidos
        foreach ($Lista as $key) {
            //ese data es un indice que estoy creando para datatables, ya que ese es el indice que leera, fijate en el datatables para que veas que llama a los datos asi {data: 'example'}   
            $json['data'][] = $key;
        }
    } else {
        //Si el listado esta vacio, hara esto
        //Aqui esta guardando en esa variable llamada json un arreglo vacio porque obvio no hay nada si cayo aqui ok?
        //Si esto no se hace, el datatables dara error porque no se le esta enviado nada. Esto es como un feedback para el datatables
        $json['data'] = array();
    }
    //Finalmente, aqui enviamos el listado
    echo json_encode($json);
    die();
}



if (isset($_POST['editar'])) {

    requierePermiso("celulaConsolidacion", "actualizar");

    $id = $_POST['id'];
    $idCelulaConsolidacion = trim($_POST['idCelulaConsolidacion']);
    $fecha = $_POST['fecha'];
    $tematica = trim(strtolower($_POST['tematica']));
    $semana = trim($_POST['semana']);
    $generosidad = trim($_POST['generosidad']);
    $actividad = trim(strtolower($_POST['actividad']));
    $observaciones = trim(strtolower($_POST['observaciones']));

    $arrayAccion = array('id'=>$id, 'idCelulaConsolidacion'=>$idCelulaConsolidacion, 'accion'=>'actualizar');

    $CelulaConsolidacion->validacion_datos_reunion([$idCelulaConsolidacion, $semana, $generosidad, $id], [$tematica, $actividad, $observaciones], $fecha);
    $CelulaConsolidacion->validacion_accion_reunion($arrayAccion);
    $CelulaConsolidacion->editar_reuniones($id, $idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones);

    die();
}


if (isset($_POST['eliminar'])) {

    requierePermiso("celulaConsolidacion", "eliminar");

    $id = $_POST['id'];

    $arrayAccion = array('id'=>$id, 'idCelulaConsolidacion'=>'', 'accion'=>'eliminar');

    $CelulaConsolidacion->validacion_accion_reunion($arrayAccion);
    $CelulaConsolidacion->eliminar_reuniones($id);

    die();
}


if (isset($_GET['listarcelulas'])) {

    requierePermiso("celulaConsolidacion", "actualizar");
    
    $listaCelulas = $CelulaConsolidacion->listar_celulas();

    echo json_encode($listaCelulas);

    die();
}

if (isset($_GET['cargar_discipulos_reunion'])) {

    requierePermiso("celulaConsolidacion", "actualizar");

    $idCelulaConsolidacion = $_GET['idCelulaConsolidacion'];
    $idReunion = $_GET['idReunion'];
    $resultado = $CelulaConsolidacion->listarAsistencia_reunion($idCelulaConsolidacion, $idReunion);

    echo json_encode($resultado);
    die();
}

if (isset($_GET['cargar_data_asistencia'])) {

    requierePermiso("celulaConsolidacion", "actualizar");

    //Primero inicializamos las variablelistar_reunioness
    $idReunion = $_GET['idReunion'];
    $Lista = $CelulaConsolidacion->listar_asistencia($idReunion);
    //Variable json solamente para guardar el array de datos
    $json = array();

    //Comrpobamos primero que la lista no este vacia
    if (!empty($Lista)) {
        //Si no esta vacia, empezara a recorrer cada columna de la consulta sql, es decir, los datos obtenidos
        foreach ($Lista as $key) {
            //ese data es un indice que estoy creando para datatables, ya que ese es el indice que leera, fijate en el datatables para que veas que llama a los datos asi {data: 'example'}   
            $json['data'][] = $key;
        }
    } else {
        //Si el listado esta vacio, hara esto
        //Aqui esta guardando en esa variable llamada json un arreglo vacio porque obvio no hay nada si cayo aqui ok?
        //Si esto no se hace, el datatables dara error porque no se le esta enviado nada. Esto es como un feedback para el datatables
        $json['data'] = array();
    }
    //Finalmente, aqui enviamos el listado
    echo json_encode($json);
    die();
}

if (isset($_POST['eliminarAsistencia'])) {

    requierePermiso("celulaConsolidacion", "actualizar");

    $id = $_POST['id'];
    $CelulaConsolidacion->eliminar_asistenciaReunion($id);
    
    die();
}

if (isset($_POST['actualizarAsistencia'])) {
    
    $idReunion = $_POST['idReunion'];
    $discipulos = $_POST['discipulos'];

    $CelulaConsolidacion->actualizar_asistenciaReunion($idReunion, $discipulos);
    die();
}





renderView();
