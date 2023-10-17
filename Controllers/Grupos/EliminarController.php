<?php
require_once "Models/Grupo.php";
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("grupos", "eliminar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún grupo para eliminar.";
    redirigir("/AppwebMVC/Grupos/");
}

try {
    /** @var Grupo */
    $grupo = Grupo::cargar($_GET['id']);

    if ($grupo == null) {
        $_SESSION['errores'][] = "Estas intentando eliminar un grupo que no existe.";
        redirigir("/AppwebMVC/Grupos/");
    }

    if (count($grupo->matriculas) > 0) {
        $_SESSION['errores'][] = "No se puede eliminar un grupo con estudiantes asignados.";
        redirigir("/AppwebMVC/Grupos/");
    }

    $clases = Clase::cargarRelaciones($grupo->id, get_class($grupo), 1);
    if (count($clases) > 0) {
        $_SESSION['errores'][] = "No se puede eliminar un grupo con clases registradas.";
        redirigir("/AppwebMVC/Grupos/");
    }

    $grupo->eliminar();
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/Grupos/");
}

$_SESSION['exitos'][] = "Se ha eliminado el grupo correctamente.";
redirigir("/AppwebMVC/Grupos/");
?>