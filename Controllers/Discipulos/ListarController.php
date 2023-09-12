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
    $asisCrecimiento = $_POST['asisCrecimiento'];
    $asisFamiliar = $_POST['asisFamiliar'];
    $idConsolidador = $_POST['idConsolidador'];
    $idcelulaconsolidacion = $_POST['idcelulaconsolidacion'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $estadoCivil = $_POST['estadoCivil'];
    $motivo = $_POST['motivo'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $fechaConvercion = $_POST['fechaConvercion'];




    $Discipulo->editar_discipulo(
        $id,
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idcelulaconsolidacion,
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

    $Sede->editar_discipulo($id, $idPastor, $nombre, $direccion, $estado);

    echo json_encode('Lo logramos!!');
    die();
}

if (isset($_POST['eliminar'])) {

    requierePermisos("eliminarDiscipulos");

    $id = $_POST['id'];

    $Discipulo->eliminar_discipulo($id);

    echo json_encode('Lo logramos!!');
    die();
}




renderView();
