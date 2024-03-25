<?php
require_once "Models/Grupo.php";
require_once "Models/Usuario.php";
require_once "Models/Eid.php";
require_once "Models/Moduloeid.php";
require_once "Models/Nivel.php";
require_once "Models/Sede.php";
require_once "Models/Nota.php";


necesitaAutenticacion();

requierePermiso("grupos", "consultar");

$usuarioSesion = $_SESSION['usuario'];

$Grupo = new Grupo();


if (isset($_POST['cargar_data'])) {

    requierePermiso("grupos", "actualizar");


    $tipo = $_POST['tipo'];

    $Lista = $Grupo->listarGrupos($tipo);
    //Variable json solamente para guardar el array de datos
    $json = array();

    if (!empty($Lista)) {
        foreach ($Lista as $key) {
            $json['data'][] = $key;
        }
    } else {

        $json['data'] = array();
    }

    echo json_encode($json);
    die();
}

if (isset($_POST['registrar_editar'])) {



    $accion = $_POST['accion'];
    $idNivel = $_POST['idNivel'];
    $id = $_POST['idGrupo'];
    $idMentor = $_POST['idMentor'];

    if ($accion == 'registrar') {

        requierePermiso("grupos", "registrar");
        $Grupo->registrarGrupo($idNivel, $idMentor);
        die();

    }

    if ($accion == 'editar') {

        requierePermiso("grupos", "actualizar");
        $Grupo->editarGrupo($id, $idNivel, $idMentor);
        die();

    }


}

if (isset($_POST['eliminar'])) {

    requierePermiso("grupos", "eliminar");

    $id = $_POST['id'];

    $Grupo->eliminarGrupo($id);

    die();
}

if (isset($_GET['ListarMentores'])) {

    requierePermiso("grupos", "actualizar");

    $Lista = $Grupo->listarMentores();

    echo json_encode($Lista);

    die();
}

if (isset($_GET['ListaNiveles'])) {

    requierePermiso("grupos", "actualizar");

    $Lista = $Grupo->listarNiveles();

    echo json_encode($Lista);

    die();
}

if (isset($_POST['cargarMatricula'])) {

    requierePermiso("grupos", "registrar");


    $idGrupo = $_POST['idGrupo'];

    $Lista = $Grupo->listarMatricula($idGrupo);
    //Variable json solamente para guardar el array de datos
    $json = array();

    if (!empty($Lista)) {
        foreach ($Lista as $key) {
            $json['data'][] = $key;
        }
    } else {

        $json['data'] = array();
    }

    echo json_encode($json);
    die();
}

if (isset($_POST['registroEstudiante'])) {


    $cedula = $_POST['cedula'];  
    $idGrupo = $_POST['idGrupo'];

        $Grupo->registrarMatricula($cedula, $idGrupo);
        die();
}

if (isset($_POST['eliminarMatricula'])) {

    requierePermiso("grupos", "eliminar");

    $id = $_POST['id'];
    $idGrupo = $_POST['idGrupo'];

    $Grupo->eliminarMatricula($id, $idGrupo);

    die();
}


if (isset($_POST['activarGrupo'])) {

    requierePermiso("grupos", "registrar");

    $id = $_POST['id'];

    $Grupo->activarGrupo($id);

    die();
}
renderView();
