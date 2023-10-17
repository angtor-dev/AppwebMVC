<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "actualizar");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($_GET['id'])) {
        $_SESSION['errores'][] = "Se debe especificar una clase.";
        redirigir("/AppwebMVC/Clases/");
    }
    
    /** @var Clase */
    $clase = Clase::cargar($_GET['id']);
    
    if (is_null($clase) || $clase->getEstatus() == 0) {
        $_SESSION['errores'][] = "Estas intentando agregar un contenido a una clase que no existe.";
        redirigir("/AppwebMVC/Clases/");
    }
    
    renderView();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /** @var Clase */
    $clase = Clase::cargar($_POST['idClase']);
    $contenido = new Contenido();
    $contenido->mapFromPost();

    if (!$contenido->esValido()) {
        renderView();
    }

    try {
        $contenido->registrar();
    } catch (\Throwable $th) {
        if (DEVELOPER_MODE) {
            throw $th;
        }
        redirigir("/AppwebMVC/Clases/Contenidos/Registrar?id=".$clase->id);
    }

    $_SESSION['exitos'][] = "Contenido registrado con exito.";
    Bitacora::registrar("Registro de contenido para la clase ".$clase->getTitulo());
    redirigir("/AppwebMVC/Clases/Contenidos?id=".$clase->id);
}
?>