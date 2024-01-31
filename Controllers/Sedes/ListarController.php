<?php
require_once "Models/Sede.php";
// Logica del controlador

necesitaAutenticacion();

requierePermiso("sedes", "consultar");

$usuarioSesion = $_SESSION['usuario'];

$Sede = new Sede();

if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Sede->listar_Sede();
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


if (isset($_GET['listaPastores'])) {
    requierePermiso("sedes", "actualizar");

    $ListaPastores = $Sede->listar_Pastores();

    echo json_encode($ListaPastores);

    die();
}

if (isset($_POST['registrar'])) {
    requierePermiso("sedes", "registrar");

    $idPastor = $_POST['idPastor'];
    $nombre = trim(strtolower($_POST['nombre']));
    $direccion = trim(strtolower($_POST['direccion']));
    $estado = trim($_POST['estado']);

    $Sede->validacion_datos($idPastor, $nombre, $direccion, $estado);
    $Sede->validacion_existencia($nombre, $idSede = '');
    $Sede->valida_pastor($idPastor, $id = '');
    $Sede->registrar_Sede($idPastor, $nombre, $direccion, $estado);
    die();
}

if (isset($_POST['editar'])) {
    requierePermiso("sedes", "actualizar");

    $idSede = $_POST['id'];
    $idPastor = $_POST['idPastor'];
    $nombre = trim(strtolower($_POST['nombre']));
    $direccion = trim(strtolower($_POST['direccion']));
    $estado = trim($_POST['estado']);

    $Sede->validacion_datos($idPastor, $nombre, $direccion, $estado);
    $Sede->validacion_existencia($nombre, $idSede);
    $Sede->validacion_editar_estado($idSede, $estado);
    $Sede->valida_pastor($idPastor, $idSede);
    $Sede->editar_Sede($idSede, $idPastor, $nombre, $direccion, $estado);

    die();
}

if (isset($_POST['eliminar'])) {
    requierePermiso("sedes", "eliminar");

    $id = $_POST['id'];
    $Sede->validacion_eliminar($id);
    $Sede->eliminar_Sede($id);

    die();
}

if (isset($_POST['coincidencias'])){
    
    if (isset($_POST['id'])){
    
    $nombre = $_POST['nombre'];
    $id = $_POST['id'];

    $resultado = $Sede->valida_nombre($nombre, $id);

    } else {

    $nombre = $_POST['nombre'];
    $id = '';

    $resultado = $Sede->valida_nombre($nombre, $id);
    }

    echo json_encode($resultado);
    die();

}

renderView();
