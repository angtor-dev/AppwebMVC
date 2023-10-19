<?php
require_once "Models/Nota.php";
necesitaAutenticacion();
requierePermiso("notas", "actualizar");

$nota = new Nota();
$id = empty($_POST['id']) ? 0 : $_POST['id'];
$idClase = $_POST['idClase'];
$idEstudiante = $_POST['idEstudiante'];
$calificacion = floatval($_POST['calificacion']);

$nota->id = $id;
$nota->setIdClase($idClase);
$nota->setIdEstudiante($idEstudiante);
$nota->setCalificacion($calificacion);

try {
    if ($nota->id != 0) {
        $nota->actualizar();
    } else {
        $nota->id = $nota->registrar();
    }
} catch (\Throwable $th) {
    http_response_code(500);
    throw $th;
}

echo json_encode(['calificacion' => $nota->getCalificacion(), 'id' => $nota->id]);
?>