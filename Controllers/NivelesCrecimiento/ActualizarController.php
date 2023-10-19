<?php
require_once "Models/NivelCrecimiento.php";
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $nivel = empty($_GET['id']) || $_GET['id'] == '0' ? new NivelCrecimiento() : NivelCrecimiento::cargar($_GET['id']);

    require_once "Views/NivelesCrecimiento/_ModalNivelCrecimiento.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    requierePermiso("nivelesCrecimiento", "actualizar");

    $nivel = new NivelCrecimiento();
    $nivel->mapFromPost();

    if (NivelCrecimiento::cargar($nivel->id) == null) {
        $_SESSION['errores'][] = "El nivel que intentas actualizar no existe.";
        redirigir("/AppwebMVC/NivelesCrecimiento/");
    }

    if (!$nivel->esValido()) {
        redirigir("/AppwebMVC/NivelesCrecimiento/");
    }

    try {
        $nivel->actualizar();
    } catch (\Throwable $th) {
        redirigir("/AppwebMVC/NivelesCrecimiento/");
    }

    $_SESSION['exitos'][] = "Subnivel actualizado con exito.";
    Bitacora::registrar("Actualizo el nivel ".$nivel->getNombre());
    header("Location: /AppwebMVC/NivelesCrecimiento/");
}
else {
    http_response_code(405);
    die();
}
?>