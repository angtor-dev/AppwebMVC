<?php
require_once "Models/Territorio.php";
require_once "Models/Celulas.php";

necesitaAutenticacion();

//requierePermisos("listarCelulaCrecimiento");

$usuarioSesion = $_SESSION['usuario'];

$Celulas = new Celulas();


if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Celulas->listar_CelulaCrecimiento();
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

if (isset($_POST['registrar'])) {

    $tipo =  trim(strtolower('crecimiento'));
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);
    
    $Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $Celulas->validacion_existencia($nombre, $id='');
    $Celulas->registrar_Celula($tipo, $nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}


if (isset($_POST['editar'])) {

    //requierePermisos("actualizarCelula");

    $id = $_POST['id2'];
    $tipo =  trim(strtolower('crecimiento'));
    $nombre = trim(strtolower($_POST['nombre2']));
    $idLider = trim($_POST['idLider2']);
    $idCoLider = trim($_POST['idCoLider2']);
    $idTerritorio = trim($_POST['idTerritorio2']);

    $Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $Celulas->validacion_existencia($nombre, $id);
    $Celulas->validacion_accion($id, $accion = 'actualizar');
    $Celulas->editar_Celula($id, $tipo, $nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}



if (isset($_POST['registroreunion'])) {

   // requierePermisos("actualizarCelula");

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
    $arrayAsistencias = '';

    $Celulas->validacion_datos_reunion([$idCelula, $semana, $generosidad, $infantil, $juvenil, $adulto], [$tematica, $actividad, $observaciones], $fecha);
    $Celulas->registrar_reunion($idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones, $arrayAsistencias);

    die();
}




if (isset($_POST['eliminar'])) {


    $id = $_POST['id'];

    $Celulas->validacion_accion($id, $accion = 'eliminar');
    $Celulas->eliminar_Celula($id);

    die();
}


if (isset($_GET['listaLideres'])) {

    requierePermisos("actualizarCelulaFamiliar");

    $ListaLideres = $Celulas->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}

if (isset($_GET['listaTerritorio'])) {


    $Listaterritorio = $Celulas->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

if (isset($_GET['cargar_discipulos_celula'])) {

    requierePermisos("actualizarCelulaConsolidacion");

    $idCelula = $_GET['idCelula'];
    $resultado = $Celulas->listarDiscipulados_celula($idCelula);

    echo json_encode($resultado);
    die();
}



renderView();
