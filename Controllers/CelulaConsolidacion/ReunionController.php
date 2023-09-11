<?php
require_once "Models/CelulaConsolidacion.php";


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

    $id = $_POST['id'];
    $idCelulaConsolidacion = $_POST['idCelulaConsolidacion'];
    $fecha = $_POST['fecha'];
    $tematica = $_POST['tematica'];
    $semana = $_POST['semana'];
    $generosidad = $_POST['generosidad'];
    $actividad = $_POST['actividad'];
    $observaciones = $_POST['observaciones'];


    $CelulaConsolidacion->editar_reuniones($id, $idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones);

    echo json_encode('Lo logramos!!');
    die();
}


if (isset($_POST['eliminar'])) {

    $id = $_POST['id'];

    $CelulaConsolidacion->eliminar_reuniones($id);

    echo json_encode('Lo logramos!!');
    die();
}


if (isset($_GET['listarcelulas'])) {
    
    $listaCelulas = $CelulaConsolidacion->listar_celulas();

    echo json_encode($listaCelulas);

    die();
}

if (isset($_GET['cargar_discipulos_reunion'])) {
    $idCelulaConsolidacion = $_GET['idCelulaConsolidacion'];
    $idReunion = $_GET['idReunion'];
    $resultado = $CelulaConsolidacion->listarAsistencia_reunion($idCelulaConsolidacion, $idReunion);

    echo json_encode($resultado);
    die();
}

if (isset($_GET['cargar_data_asistencia'])) {
    //Primero inicializamos las variablelistar_reunioness
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





renderView();