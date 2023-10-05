<?php
require_once "Models/Grupo.php";
require_once "Models/Enums/EstadosGrupo.php";
necesitaAutenticacion();
requierePermiso("grupos", "actualizar");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    die;
}

$grupo = new Grupo();
$grupo->mapFromPost();

if (Grupo::cargar($grupo->id) == null) {
    $_SESSION['errores'][] = "El grupo que intentas actualizar no existe.";
    redirigir("/AppwebMVC/Grupos/");
}

if (!$grupo->esValido()) {
    redirigir("/AppwebMVC/Grupos/");
}

try {
    $grupo->actualizar();
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/Grupos/");
}

$_SESSION['exitos'][] = "Grupo actualizado con exito.";
Bitacora::registrar("Actualizo el grupo ".$grupo->getNombre());
header("Location: /AppwebMVC/Grupos/");
?>