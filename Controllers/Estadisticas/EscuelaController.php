<?php
require_once "Models/NivelCrecimiento.php";
require_once "Models/Matricula.php";
require_once "Models/Nota.php";

if (isset($_GET['gruposPorNivel'])) {
    $resultado = NivelCrecimiento::gruposPorNivel();
    echo json_encode($resultado);
    exit;
}
if (isset($_GET['inscripcionesPorMes'])) {
    $resultado = Matricula::inscripcionesPorMes();
    echo json_encode($resultado);
    exit;
}
if (isset($_GET['estudiantesPorGrupo'])) {
    $resultado = Grupo::estudiantesPorGrupo();
    echo json_encode($resultado);
    exit;
}
if (isset($_GET['gruposPorSede'])) {
    $resultado = Grupo::gruposPorSede();
    echo json_encode($resultado);
    exit;
}
if (isset($_GET['promedioNotasPorGrupo'])) {
    $resultado = Nota::promedioNotasPorGrupo();
    echo json_encode($resultado);
    exit;
}

renderView();
?>