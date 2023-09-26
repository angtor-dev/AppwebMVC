<?php
require_once "Models/Discipulo.php";
// Logica del controlador

necesitaAutenticacion();

requierePermisos("listarDiscipulos");

$usuarioSesion = $_SESSION['usuario'];


$Discipulo = new Discipulo();

if (isset($_GET['cargar_data'])) {
    //Primero inicializamos las variables
    $Lista = $Discipulo->listar_discipulo();
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


if (isset($_GET['listaConsolidador'])) {

    requierePermisos("actualizarDiscipulos");

    $ListaConsolidador = $Discipulo->listar_consolidador();

    echo json_encode($ListaConsolidador);

    die();
}



if (isset($_GET['listarcelulas'])) {

    requierePermisos("actualizarDiscipulos");

    $listacelulas = $Discipulo->listar_celulas();


    echo json_encode($listacelulas);

    die();
}

if (isset($_POST['editar'])) {

    requierePermisos("actualizarDiscipulos");

    $id = $_POST['id'];
    $asisCrecimiento = trim(strtolower($_POST['asisCrecimiento']));
    $asisFamiliar = trim(strtolower($_POST['asisFamiliar']));
    $idConsolidador = $_POST['idConsolidador'];
    $idCelulaConsolidacion = $_POST['idCelulaConsolidacion'];
    $cedula = $_POST['cedula'];
    $nombre = trim(strtolower($_POST['nombre']));
    $apellido = trim(strtolower($_POST['apellido']));
    $telefono = $_POST['telefono'];
    $direccion = trim(strtolower($_POST['direccion']));
    $estadoCivil = trim(strtolower($_POST['estadoCivil']));
    $motivo = trim(strtolower($_POST['motivo']));
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $fechaConvercion = $_POST['fechaConvercion'];

    $Discipulo->validacion_datos(
        [$idConsolidador, $idCelulaConsolidacion, $cedula, $telefono],
        [$direccion, $motivo],
        [$nombre, $apellido],
        [$fechaNacimiento, $fechaConvercion],
        $fechaNacimiento,
        [$asisCrecimiento, $asisFamiliar],
        $cedula,
        $estadoCivil,
        $telefono
    );
    $Discipulo->validacion_existencia($cedula, $id);

    $Discipulo->editar_discipulo(
        $id,
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idCelulaConsolidacion,
        $cedula,
        $nombre,
        $apellido,
        $telefono,
        $direccion,
        $estadoCivil,
        $motivo,
        $fechaNacimiento,
        $fechaConvercion
    );
    die();
}


if (isset($_POST['eliminar'])) {

    requierePermisos("eliminarDiscipulos");

    $id = $_POST['id'];

    $Discipulo->validacion_accion($id, $accion='eliminar');
    $Discipulo->eliminar_discipulo($id);
    die();
}


if (isset($_POST['registrar'])) {

    $asisCrecimiento = trim(strtolower($_POST['asisCrecimiento']));
    $asisFamiliar = trim(strtolower($_POST['asisFamiliar']));
    $idConsolidador = $_POST['idConsolidador'];
    $idCelulaConsolidacion = $_POST['idcelulaconsolidacion'];
    $cedula = $_POST['cedula'];
    $nombre = trim(strtolower($_POST['nombre']));
    $apellido = trim(strtolower($_POST['apellido']));
    $telefono = $_POST['telefono'];
    $direccion = trim(strtolower($_POST['direccion']));
    $estadoCivil = trim(strtolower($_POST['estadoCivil']));
    $motivo = trim(strtolower($_POST['motivo']));
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $fechaConvercion = $_POST['fechaConvercion'];


    $Discipulo->validacion_datos(
        [$idConsolidador, $idCelulaConsolidacion, $cedula, $telefono],
        [$direccion, $motivo],
        [$nombre, $apellido],
        [$fechaNacimiento, $fechaConvercion],
        $fechaNacimiento,
        [$asisCrecimiento, $asisFamiliar],
        $cedula,
        $estadoCivil,
        $telefono
    );
    $Discipulo->validacion_existencia($cedula, $id='');
    $Discipulo->registrar_discipulo(
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idCelulaConsolidacion,
        $cedula,
        $nombre,
        $apellido,
        $telefono,
        $direccion,
        $estadoCivil,
        $motivo,
        $fechaNacimiento,
        $fechaConvercion
    );

    die();
}


if (isset($_GET['listaConsolidador'])) {

    $ListaConsolidador = $Discipulo->listar_consolidador();

    echo json_encode($ListaConsolidador);

    die();
}



if (isset($_GET['listarcelulas'])) {

    $listacelulas = $Discipulo->listar_celulas();

    echo json_encode($listacelulas);

    die();
}




renderView();
