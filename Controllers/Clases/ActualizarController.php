<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "actualizar");

if (!$_SERVER['REQUEST_METHOD'] === 'POST')
{
    http_response_code(405);
    die();
}

$clase = new Clase();
$clase->mapFromPost();

if (Clase::cargar($clase->id) == null) {
    $_SESSION['errores'][] = "La clase que intentas actualizar no existe.";
    redirigir("/AppwebMVC/Clases/");
}
    
if (!$clase->esValido()) {
    redirigir("/AppwebMVC/Clases/");
}

try {
    $clase->actualizar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Clases/");
}

$_SESSION['exitos'][] = "Clase actualizado con exito.";
Bitacora::registrar("Actualizo la clase ".$clase->getTitulo());
header("Location: /AppwebMVC/Clases/");
?>