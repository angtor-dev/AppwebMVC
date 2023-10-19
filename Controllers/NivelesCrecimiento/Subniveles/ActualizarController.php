<?php
require_once "Models/Subnivel.php";
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $subnivel = empty($_GET['id']) || $_GET['id'] == '0' ? new Subnivel() : Subnivel::cargar($_GET['id']);

    require_once "Views/NivelesCrecimiento/_ModalSubnivel.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    requierePermiso("nivelesCrecimiento", "actualizar");

    $subnivel = new Subnivel();
    $subnivel->mapFromPost();

    if (Subnivel::cargar($subnivel->id) == null) {
        $_SESSION['errores'][] = "El nivel que intentas actualizar no existe.";
        redirigir("/AppwebMVC/NivelesCrecimiento/");
    }

    try {
        $subnivel->actualizar();
    } catch (\Throwable $th) {
        redirigir("/AppwebMVC/NivelesCrecimiento/");
    }

    $_SESSION['exitos'][] = "Subnivel actualizado con exito.";
    Bitacora::registrar("Actualizo el subnivel ".$subnivel->getNombre());
    header("Location: /AppwebMVC/NivelesCrecimiento/");
}
else {
    http_response_code(405);
    die();
}
?>