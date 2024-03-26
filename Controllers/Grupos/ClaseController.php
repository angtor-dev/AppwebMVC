<?php
require_once "Models/Clase.php";
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

$Clase = new Clase();


if (isset($_POST['registrar_editar'])) {



    $accion = $_POST['accion'];
    $idGrupo = $_POST['idGrupo'];
    $idClase = $_POST['idClase'];
    $titulo = trim(strtolower($_POST['titulo']));
    $Objetivo = trim(strtolower($_POST['Objetivo']));
    $ponderacion = $_POST['ponderacion'];

    if ($accion == 'Registrar') {

        requierePermiso("clases", "registrar");
        $Clase->registrarClase($idGrupo, $titulo, $Objetivo, $ponderacion);
        die();

    }

    if ($accion == 'Editar') {

        requierePermiso("clases", "actualizar");
        $Clase->editarClase($idClase, $idGrupo, $titulo, $Objetivo, $ponderacion);
        die();

    }


}

if (isset($_POST['cargarClase'])) {

    requierePermiso("clases", "registrar");


    $idGrupo = $_POST['idGrupo'];

    $Lista = $Clase->listarClases($idGrupo);
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

if (isset($_POST['eliminar'])) {

    requierePermiso("clases", "eliminar");

    $id = $_POST['id'];

    $Clase->eliminarClase($id);

    die();
}


renderView();