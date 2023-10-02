<?php
require_once "Models/Grupo.php";
necesitaAutenticacion();
requierePermiso("grupos", "eliminar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún grupo para eliminar.";
    redirigir("/AppwebMVC/Grupos/");
}

try {
    $grupo = Grupo::cargar($_GET['id']);

    if ($grupo == null) {
        $_SESSION['errores'][] = "Estas intentando eliminar un grupo que no existe.";
        redirigir("/AppwebMVC/Grupos/");
    }

    // Validar si tiene estudiantes

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