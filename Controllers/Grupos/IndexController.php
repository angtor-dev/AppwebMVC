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

    requierePermiso("grupos", "consultar");


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

if (isset($_POST['cargar_dataHistorial'])) {

    requierePermiso("estudiantes", "consultar");


    $id= $_POST['id'];

    $Lista = $Grupo->listarHistorialGrupos($id);
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
    $idSede = $_POST['idSede'];

    if ($accion == 'registrar') {

        requierePermiso("grupos", "registrar");
        $Grupo->registrarGrupo($idNivel, $idMentor, $idSede);
        die();

    }

    if ($accion == 'editar') {

        requierePermiso("grupos", "actualizar");
        $Grupo->editarGrupo($id, $idNivel, $idMentor, $idSede);
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

    requierePermiso("grupos", "registrar");

    $Lista = $Grupo->listarMentores();

    echo json_encode($Lista);

    die();
}

if (isset($_GET['ListaNiveles'])) {

    requierePermiso("grupos", "registrar");

    $Lista = $Grupo->listarNiveles();

    echo json_encode($Lista);

    die();
}

if (isset($_POST['cargarMatricula'])) {

    requierePermiso("grupos", "consultar");


    $idGrupo = $_POST['idGrupo'];
    $tipo = $_POST['tipo'];
    
    $Lista = $Grupo->listarMatricula($idGrupo, $tipo);
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

if (isset($_POST['cargarNotasEstudiante'])) {

    requierePermiso("grupos", "consultar");

    $idGrupo = $_POST['idGrupo'];
    $idEstudiante = $_POST['idEstudiante'];

    $Lista = $Grupo->cargarNotasEstudiante($idGrupo, $idEstudiante);
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
   
    requierePermiso("grupos", "registrar");

    $cedula = $_POST['cedula'];  
    $idGrupo = $_POST['idGrupo'];

        $Grupo->registrarMatricula($cedula, $idGrupo);
        die();
}

if (isset($_POST['eliminarMatricula'])) {

    requierePermiso("grupos", "actualizar");

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

if (isset($_POST['cerrarGrupo'])) {

    requierePermiso("grupos", "actualizar");

    $id = $_POST['id'];

    $Grupo->cerrarGrupo($id);

    die();
}

if (isset($_POST['validarAsignarRoles'])) {
     
    requierePermiso("grupos", "actualizar");
    $idGrupo = $_POST['idGrupo'];
    $idEid = $_POST['idEid'];

    $response = $Grupo->validarAsignarRolesAdqr($idGrupo, $idEid);

    echo json_encode($response);

    die();
}

if (isset($_POST['asignarRolesAdqr'])) {
   
    requierePermiso("grupos", "actualizar");
    $idGrupo = $_POST['idGrupo'];
    $idEid = $_POST['idEid'];

     $Grupo->asignarRolesAdqr($idGrupo, $idEid);

    die();
}
renderView();
