
<?php

require_once "Models/Celulas.php";

necesitaAutenticacion();

requierePermiso("celulaFamiliar", "registrar");

$usuarioSesion = $_SESSION['usuario'];


$Celulas = new Celulas();


if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Celulas->listar_reunionesFamiliar();
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

    requierePermiso("celulaFamiliar", "actualizar");

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

    requierePermiso("celulaFamiliar", "actualizar");

    $id = $_POST['id'];

    $Celulas->eliminar_reuniones($id);

    die();
}


if (isset($_GET['listarcelulas'])) {

    requierePermiso("celulaFamiliar", "actualizar");

    $listaCelulas = $Celulas->listar_celulas();

    echo json_encode($listaCelulas);

    die();
}

renderView();
?>