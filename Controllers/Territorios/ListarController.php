<?php
require_once "Models/Territorio.php";

// Logica del controlador

necesitaAutenticacion();

requierePermiso("territorios", "registrar");

$usuarioSesion = $_SESSION['usuario'];

$Territorio = new Territorio();

if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Territorio->listar_territorio();
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

    $idSede = trim($_POST['idSede']);
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $detalles = trim(strtolower($_POST['detalles']));
    
    $Territorio->validacion_datos($idSede, $nombre, $idLider, $detalles);
    $Territorio->validacion_existencia($nombre, $idSede, $idTerritorio = '');
    $Territorio->valida_lider($idLider, $id = '');
    $Territorio->registrar_territorio($idSede, $nombre, $idLider, $detalles);

    echo json_encode('Lo logramos!!');
    die();
}


if (isset($_GET['listaLideres'])) {

    requierePermiso("territorios", "actualizar");

    $ListaLideres = $Territorio->listar_lideres();

    echo json_encode($ListaLideres);

    die();
}


if (isset($_GET['listaSedes'])) {

    requierePermiso("territorios", "actualizar");

    $ListaSedes = $Territorio->listar_Sedes();

    echo json_encode($ListaSedes);

    die();
}

if (isset($_POST['editar'])) {

    requierePermiso("territorios", "actualizar");

    $id = trim($_POST['id']);
    $idSede = trim($_POST['idSede']);
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $detalles = trim(strtolower($_POST['detalles']));

    $Territorio->validacion_datos($idSede, $nombre, $idLider, $detalles);
    $Territorio->validacion_existencia($nombre, $idSede, $id);
    $Territorio->valida_lider($idLider, $id);
    $Territorio->editar_territorio($id, $idSede, $nombre, $idLider, $detalles);

    die();
}

if (isset($_POST['eliminar'])) {

    requierePermiso("territorios", "eliminar");

    $id = $_POST['id'];

    $Territorio->validacion_accion($id, $accion = 'eliminar');
    $Territorio->eliminar_territorio($id);

    die();
}


if (isset($_POST['coincidencias'])){
    

    $nombre = empty($_POST['nombre']) ? '' : $_POST['nombre'];
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $idSede = empty($_POST['idSede']) ? '' : $_POST['idSede'];

    $resultado = $Territorio->valida_nombre($nombre, $id, $idSede);

    echo json_encode($resultado);
    die();

}

renderView();
