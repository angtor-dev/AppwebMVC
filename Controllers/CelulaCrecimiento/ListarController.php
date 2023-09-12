<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaCrecimiento.php";

necesitaAutenticacion();

requierePermisos("listarCelulaCrecimiento");

$usuarioSesion = $_SESSION['usuario'];

$CelulaCrecimiento = new CelulaCrecimiento();


if (isset($_GET['cargar_data'])) {

    //Primero inicializamos las variables
    $Lista = $CelulaCrecimiento->listar_CelulaCrecimiento();
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

    requierePermisos("actualizarCelulaCrecimiento");

    $id = $_POST['id'];
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);

    $CelulaCrecimiento->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $CelulaCrecimiento->validacion_existencia($nombre, $id);
    $CelulaCrecimiento->validacion_accion($id, $accion = 'actualizar');
    $CelulaCrecimiento->editar_CelulaCrecimiento($id, $nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}



if (isset($_POST['registroreunion'])) {

    requierePermisos("actualizarCelulaCrecimiento");


    $idCelulaCrecimiento = $_POST['idCelulaCrecimiento'];
    $fecha = $_POST['fecha'];
    $tematica = trim(strtolower($_POST['tematica']));
    $semana = trim($_POST['semana']);
    $generosidad = $_POST['generosidad'];
    $infantil = trim($_POST['infantil']);
    $juvenil = trim($_POST['juvenil']);
    $adulto = trim($_POST['adulto']);
    $actividad = trim(strtolower($_POST['actividad']));
    $observaciones = trim(strtolower($_POST['observaciones']));

    $CelulaCrecimiento->validacion_datos_reunion([$idCelulaCrecimiento, $semana, $generosidad, $infantil, $juvenil, $adulto], [$tematica, $actividad, $observaciones], $fecha);
    $CelulaCrecimiento->registrar_reunion($idCelulaCrecimiento, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones);

    die();
}




if (isset($_POST['eliminar'])) {

    requierePermisos("eliminarCelulaCrecimiento");

    $id = $_POST['id'];

    $CelulaCrecimiento->validacion_accion($id, $accion = 'eliminar');
    $CelulaCrecimiento->eliminar_CelulaCrecimiento($id);

    die();
}


if (isset($_GET['listaLideres'])) {

    requierePermisos("actualizarCelulaCrecimiento");

    $ListaLideres = $CelulaCrecimiento->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}

if (isset($_GET['listaTerritorio'])) {

    requierePermisos("actualizarCelulaCrecimiento");

    $Listaterritorio = $CelulaCrecimiento->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

renderView();
