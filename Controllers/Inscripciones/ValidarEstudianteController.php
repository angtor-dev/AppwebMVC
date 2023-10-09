<?php
require_once "Models/Sede.php";
necesitaAutenticacion();
$usuarioSesion = $_SESSION['usuario'];

if (empty($_POST) || empty($_POST['cedula'])) {
    $res['code'] = -1;
    $res['msg'] = "Cedula invalida";
    echo json_encode($res);
    exit;
}

$cedula = trim($_POST['cedula']);
$estudiante = Usuario::cargarPorCedula($cedula);

if (is_null($estudiante)) {
    $discipulo = Discipulo::cargarPorCedula($cedula);
} else {
    // Valida si tiene rol estudiante
}
?>