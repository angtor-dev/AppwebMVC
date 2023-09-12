<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaFamiliar.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

if (!$usuarioSesion->tienePermiso("listarCelulaFamiliar")) {
    $_SESSION['errores'][] = "No seposee permiso para listar Celula Familiar.";
    redirigir("/AppwebMVC/Home/");
}

$CelulaFamiliar = new CelulaFamiliar();


if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $CelulaFamiliar->listar_CelulaFamiliar();
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

    if (!$usuarioSesion->tienePermiso("actualizarCelulaFamiliar")) {
        $_SESSION['errores'][] = "No posee permiso para editar Celula Familiar.";
        redirigir("/AppwebMVC/Home/");
    }

    $id = $_POST['id'];
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $idCoLider = trim($_POST['idCoLider']);
    $idTerritorio = trim($_POST['idTerritorio']);

    $CelulaFamiliar->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
    $CelulaFamiliar->validacion_existencia($nombre, $id);
    $CelulaFamiliar->validacion_accion($id, $accion = 'actualizar');
    $CelulaFamiliar->editar_CelulaFamiliar($id, $nombre, $idLider, $idCoLider, $idTerritorio);

    die();
}



if (isset($_POST['registroreunion'])) {

    $idCelulaFamiliar = $_POST['idCelulaFamiliar'];
    $fecha = $_POST['fecha'];
    $tematica = $_POST['tematica'];
    $semana = $_POST['semana'];
    $generosidad = $_POST['generosidad'];
    $infantil = $_POST['infantil'];
    $juvenil = $_POST['juvenil'];
    $adulto = $_POST['adulto'];
    $actividad = $_POST['actividad'];
    $observaciones = $_POST['observaciones'];

    $CelulaFamiliar->registrar_reunion($idCelulaFamiliar, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones);

    echo json_encode('Lo logramos!!');
    die();
}




if (isset($_POST['eliminar'])) {

    if (!$usuarioSesion->tienePermiso("eliminarCelulaFamiliar")) {
        $_SESSION['errores'][] = "No seposee permiso para elimianr Celula Familiar.";
        redirigir("/AppwebMVC/Home/");
    }

    $id = $_POST['id'];

    $CelulaFamiliar->validacion_accion($id, $accion = 'eliminar');
    $CelulaFamiliar->eliminar_CelulaFamiliar($id);

    echo json_encode('Lo logramos!!');
    die();
}


if (isset($_GET['listaLideres'])) {

    $ListaLideres = $CelulaFamiliar->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}

if (isset($_GET['listaTerritorio'])) {

    $Listaterritorio = $CelulaFamiliar->listar_territorios();

    echo json_encode($Listaterritorio);

    die();
}

renderView();
