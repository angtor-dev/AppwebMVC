<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "actualizar");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($_GET['id'])) {
        $_SESSION['errores'][] = "Se debe especificar un contenido para actualizar.";
        redirigir("/AppwebMVC/Clases/");
    }
    
    /** @var Contenido */
    $contenido = Contenido::cargar($_GET['id']);
    /** @var Clase */
    $clase = Clase::cargar($contenido->getIdClase());
    
    if (is_null($contenido) || $contenido->getEstatus() == 0) {
        $_SESSION['errores'][] = "El contenido que intentas actualizar no existe.";
        redirigir("/AppwebMVC/Clases/");
    }
    
    renderView("Registrar");
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /** @var Contenido */
    $contenido = Contenido::cargar($_POST['id']);
    $contenido->mapFromPost();

    if (!$contenido->esValido()) {
        renderView("Registrar");
    }

    try {
        $contenido->actualizar();
    } catch (\Throwable $th) {
        if (DEVELOPER_MODE) {
            throw $th;
        }
        redirigir("/AppwebMVC/Clases/Contenidos/Actualizar?id=".$contenido->id);
    }

    $_SESSION['exitos'][] = "Contenido actualizado con exito.";
    Bitacora::registrar("Actualizo el contenido ".$contenido->getTitulo());
    redirigir("/AppwebMVC/Clases/Contenidos?id=".$contenido->getIdClase());
}
?>