<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "eliminar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ninguna clase para eliminar.";
    redirigir("/AppwebMVC/Clases/");
}

try {
    $clase = new Clase();
    $clase->id = $_GET['id'];

    $clase->eliminar();
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/Clases/");
}

$_SESSION['exitos'][] = "Se ha eliminado la clase correctamente.";
Bitacora::registrar("Elimino la clase ".$clase->getTitulo());
redirigir("/AppwebMVC/Clases/");
?>