<?php
require_once "Models/Sede.php";
require_once "Models/Discipulo.php";
necesitaAutenticacion();
$usuarioSesion = $_SESSION['usuario'];

if (empty($_POST) || empty($_POST['cedula'])) {
    $res['code'] = -1;
    $res['msg'] = "Cedula invalida";
    echo json_encode($res);
    exit;
}

$cedula = trim($_POST['cedula']);
$usuario = Usuario::cargarPorCedula($cedula);

// Codigos:
// -3: Existe usuario sin rol estudiante
// -2: No existe discipulo ni usuario
// -1: Petición invalida
// 0: Discipulo no cumple asistencia
// 1: Se puede inscribir
// 2: El discipulo ya tiene usuario

// TODO: listar solo los de la sede actual, y enviar otro codigo si es de una sede diferente

if (is_null($usuario)) {
    $discipulo = Discipulo::cargarPorCedula($cedula);
    if (is_null($discipulo)) {
        $res['code'] = -2;
        $res['msg'] = "No existe un discipulo con la cédula ingresada.";
        echo json_encode($res);
        exit;
    } else {
        if ($discipulo->getAprobarUsuario() == 0) {
            $res['code'] = 0;
            $res['msg'] = "El discipulo no cumple con todas asistencias para inscribirse.";
            echo json_encode($res);
            exit;
        } elseif ($discipulo->getAprobarUsuario() == 2) {
            $res['code'] = 2;
            $res['msg'] = "Ya se encuentra inscrito un estudiante con la cedula ".$discipulo->getCedula().".";
            echo json_encode($res);
            exit;
        } else {
            $res['code'] = 1;
            $res['msg'] = "Discipulo apto para inscribirse.";
            $res['id'] = $discipulo->id;
            echo json_encode($res);
            exit;
        }
    }
} else {
    if ($usuario->tieneRol("Estudiante")) {
        $res['code'] = 2;
        $res['msg'] = "Ya se encuentra inscrito un estudiante con la cedula ".$usuario->getCedula().".";
        echo json_encode($res);
        exit;
    } else {
        $res['code'] = -3;
        $res['msg'] = "Ya existe un usuario con la cedula ingresada";
        echo json_encode($res);
        exit;
    }
}
?>