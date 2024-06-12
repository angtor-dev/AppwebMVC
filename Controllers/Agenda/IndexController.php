<?php
require_once "Models/Evento.php";
necesitaAutenticacion();

/** @var Usuario */
$usuario = $_SESSION['usuario'];
$Evento = new Evento();


if (isset($_GET['listarEventos'])) {

    $Eventos = $Evento->listar_Eventos();

    echo json_encode($Eventos);

    die();
}

if (isset($_GET['listaSedes'])) {

    $Sedes = $Evento->listar_Sedes();

    echo json_encode($Sedes);

    die();
}

if (isset($_POST['sedes_sin_agregar'])) {

    $idEvento = $_POST['idEvento'];
    $Sedes = $Evento->sedes_sin_agregar($idEvento);

    echo json_encode($Sedes);

    die();
}


if (isset($_POST['registroEventos'])) {

    $titulo = $_POST['titulo'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFinal = $_POST['fechaFinal'];
    $descripcion = $_POST['descripcion'];
    $sedes = $_POST['sedes'];

    $Evento->registrar_eventos($titulo, $fechaInicio, $fechaFinal, $descripcion, $sedes);

    die();
}

if (isset($_POST['editarEvento'])) {

    $titulo = $_POST['titulo'];
    $idEvento = $_POST['idEvento'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFinal = $_POST['fechaFinal'];
    $descripcion = $_POST['descripcion'];

    $Evento->Editar_evento($titulo, $idEvento, $fechaInicio, $fechaFinal, $descripcion);

    die();
}

if (isset($_POST['cargar_data_sedes'])) {
    $idEvento = trim($_POST['idEvento']);
    $sedes = $Evento->cargar_data_sedes($idEvento);

    //Variable json solamente para guardar el array de datos
    $json = array();

    //Comrpobamos primero que la lista no este vacia
    if (!empty($sedes)) {
        //Si no esta vacia, empezara a recorrer cada columna de la consulta sql, es decir, los datos obtenidos
        foreach ($sedes as $key) {
            $json['data'][] = $key;
        }
    } else {
        $json['data'] = array();
    }

    echo json_encode($json);
    die();
}

if (isset($_POST['coincidencias'])) {

    if (isset($_POST['id'])) {

        $titulos = $_POST['titulo'];
        $id = $_POST['id'];

        $resultado = $Evento->valida_titulo_evento($titulos, $id);

    } else {

        $titulos = $_POST['titulo'];
        $id = '';

        $resultado = $Evento->valida_titulo_evento($titulos, $id);
    }

    echo json_encode($resultado);
    die();

}


if (isset($_POST['eliminarEventoSede'])) {


    $id = $_POST['id'];


    $Evento->eliminar_evento_sede($id);

    echo json_encode($resultado);

    die();
}

if (isset($_POST['eliminar'])) {

    $id = $_POST['id'];

    $Evento->eliminar_evento($id);

    die();
}

if (isset($_POST['actualizarSedes'])) {

    $arraySedes = $_POST['arraySedes'];
    $idEvento = $_POST['idEvento'];

    $Evento->actualizarSedes($arraySedes, $idEvento);


    die();
}

if (isset($_POST['actualizarComentario'])) {

    $comentario = $_POST['comentario'];
    $id = $_POST['id'];

    $Evento->actualizarComentario($comentario, $id);


    die();
}



renderView();
