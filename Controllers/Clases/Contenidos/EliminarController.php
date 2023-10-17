<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "eliminar");

if (empty($_GET['id']) || empty($_GET['idClase'])) {
    $_SESSION['errores'][] = "Se debe especificar un contenido a eliminar.";
    redirigir("/AppwebMVC/Clases/");
}

$idClase = $_GET['idClase'];
$idContenido = $_GET['id'];

try {
    /** @var Contenido */
    $contenido = Contenido::cargar($idContenido);

    if (is_null($contenido)) {
        $_SESSION['errores'][] = "El contenido que intentas eliminar no existe.";
        redirigir("/AppwebMVC/Clases/Contenidos?id=$idClase");
    }
    
    $contenido->eliminar();
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/Clases/Contenidos?id=$idClase");
}

$_SESSION['exitos'][] = "Contenido eliminado correctamente.";
Bitacora::registrar("Elimino el contenido ".$contenido->getTitulo());
redirigir("/AppwebMVC/Clases/Contenidos?id=$idClase");
?>