<?php
require_once "Models/NivelCrecimiento.php";
necesitaAutenticacion();
requierePermisos("eliminarNivelesCrecimiento");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún nivel de crecimiento para eliminar.";
    redirigir("/AppwebMVC/NivelesCrecimiento/");
}

try {
    $nivelCrecimiento = NivelCrecimiento::cargar($_GET['id']);

    if ($nivelCrecimiento == null) {
        $_SESSION['errores'][] = "El nivel de crecimiento que intentas eliminar ya no existe.";
        redirigir("/AppwebMVC/NivelCrecimiento/");
    }

    $nivelCrecimiento->eliminar();
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/NivelesCrecimiento/");
}

$_SESSION['exitos'][] = "Se ha eliminado al nivel de crecimiento correctamente.";
redirigir("/AppwebMVC/NivelesCrecimiento/");
?>