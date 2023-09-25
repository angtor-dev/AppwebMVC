<?php
require_once "Models/NivelCrecimiento.php";
necesitaAutenticacion();
requierePermiso("nivelesCrecimiento", "registrar");

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    renderView();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $idSede = $_SESSION['usuario']->idSede;
    $escuela = Escuela::cargarRelaciones($idSede, "Sede")[0];
    $nivelCrecimiento = new NivelCrecimiento();

    $nivelCrecimiento->mapFromPost();
    $nivelCrecimiento->setIdEscuela($escuela->id);
    
    if (!$nivelCrecimiento->esValido()) {
        renderView();
    }
    
    try {
        $nivelCrecimiento->registrar();
    } catch (\Throwable $th) {
        redirigir("/AppwebMVC/NivelesCrecimiento/Registrar");
    }

    $_SESSION['exitos'][] = "Nivel de Crecimiento registrado con exito.";
    header("Location: /AppwebMVC/NivelesCrecimiento/");
}
else
{
    http_response_code(405);
    die();
}
?>