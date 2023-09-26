<?php

require_once "Models/Celulas.php";

necesitaAutenticacion();

requierePermisos("listarCelulaCrecimiento");

$usuarioSesion = $_SESSION['usuario'];


$Celulas = new Celulas();


if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Celulas->listar_reunionesCrecimiento();
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
        
        $json['data'] = array();
    }
    //Finalmente, aqui enviamos el listado
    echo json_encode($json);
    die();
}


if (isset($_POST['editar'])) {

    requierePermisos("actualizarCelulaCrecimiento");

    $id = $_POST['id'];
    $idCelula = $_POST['idCelula'];
    $fecha = $_POST['fecha'];
    $tematica = trim(strtolower($_POST['tematica']));
    $semana = trim($_POST['semana']);
    $generosidad = $_POST['generosidad'];
    $infantil = trim($_POST['infantil']);
    $juvenil = trim($_POST['juvenil']);
    $adulto = trim($_POST['adulto']);
    $actividad = trim(strtolower($_POST['actividad']));
    $observaciones = trim(strtolower($_POST['observaciones']));

    $Celulas->validacion_datos_reunion([$id, $idCelula, $semana, $generosidad, $infantil, $juvenil, $adulto], [$tematica, $actividad, $observaciones], $fecha);
    $Celulas->editar_reuniones($id, $idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones);

    die();
}


if (isset($_POST['eliminar'])) {

    requierePermisos("actualizarCelulaCrecimiento");

    $id = $_POST['id'];

    $Celulas->eliminar_reuniones($id);

    die();
}


if (isset($_GET['listarcelulas'])) {

    requierePermisos("actualizarCelulaCrecimiento");

    $listaCelulas = $Celulas->listar_CelulaCrecimiento();

    echo json_encode($listaCelulas);

    die();
}





renderView();
?>