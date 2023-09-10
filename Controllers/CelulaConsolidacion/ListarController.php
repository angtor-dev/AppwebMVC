<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaConsolidacion.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

$CelulaConsolidacion = new CelulaConsolidacion();
///hola



if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $CelulaConsolidacion->listar_CelulaConsolidacion();
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
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);

    $CelulaConsolidacion->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $CelulaConsolidacion->validacion_existencia($nombre, $id);
    $CelulaConsolidacion->validacion_accion($id, $accion = 2);
    $CelulaConsolidacion->editar_CelulaConsolidacion($id, $nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}



if (isset($_POST['registroreunion'])) {

    $idCelulaConsolidacion = $_POST['idCelulaConsolidacion'];
    $fecha = $_POST['fecha'];
    $tematica = $_POST['tematica'];
    $semana = $_POST['semana'];
    $generosidad = $_POST['generosidad'];
    $actividad = $_POST['actividad'];
    $observaciones = $_POST['observaciones'];
    $asistencias = $_POST['asistencias'];


    $CelulaConsolidacion->registrar_reunion($idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones, $asistencias);
    die();
}


if (isset($_POST['eliminar'])) {

    $id = $_POST['id'];

    $CelulaConsolidacion->validacion_accion($id, $accion = 1);
    $CelulaConsolidacion->eliminar_CelulaConsolidacion($id);

    echo json_encode('Lo logramos!!');
    die();
}


if (isset($_GET['listaLideres'])) {

    $ListaLideres = $CelulaConsolidacion->listar_lideres();

    echo json_encode($ListaLideres);
    die();
}

if (isset($_GET['listaTerritorio'])) {

    $Listaterritorio = $CelulaConsolidacion->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

if (isset($_GET['cargar_discipulos_celula'])) {
    $idCelulaConsolidacion = $_GET['idCelulaConsolidacion'];
    $resultado = $CelulaConsolidacion->listarDiscipulados_celula($idCelulaConsolidacion);

    echo json_encode($resultado);
    die();
}

renderView();
