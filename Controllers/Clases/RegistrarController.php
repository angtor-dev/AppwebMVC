<?php
require_once "Models/Clase.php";
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $clase = empty($_GET['id']) || $_GET['id'] == '0' ? new Clase() : Clase::cargar($_GET['id']);
    $grupos = Grupo::listar(1);

    require_once "Views/Clases/_ModalClase.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    requierePermiso("clases", "registrar");
    $clase = new Clase();
    $clase->mapFromPost();
    
    if (!$clase->esValido()) {
        header("Location: /AppwebMVC/Clases/");
        exit();
    }
    /** @var Grupo */
    $clase->grupo = Grupo::cargar($clase->getIdGrupo());
    
    try {
        $clase->registrar();
    } catch (\Throwable $th) {
        header("Location: /AppwebMVC/Clases/");
        exit();
    }

    $_SESSION['exitos'][] = "Clase registrada con exito.";
    Bitacora::registrar("Registro la clase ".$clase->getTitulo()." del grupo ".$clase->grupo->getNombre());
    header("Location: /AppwebMVC/Clases/");
}
else {
    http_response_code(405);
    die();
}
?>