<?php
require_once "Models/Grupo.php";
necesitaAutenticacion();
//requierePermisos("registrarGrupos");

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    renderView();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $grupo = new Grupo();

    $grupo->mapFromPost();
    
    if (!$grupo->esValido()) {
        renderView();
    }
    
    try {
        $grupo->registrar();
    } catch (\Throwable $th) {
        redirigir("/AppwebMVC/Grupos/Registrar");
    }

    $_SESSION['exitos'][] = "Nivel de Crecimiento registrado con exito.";
    header("Location: /AppwebMVC/Grupos/");
}
else
{
    http_response_code(405);
    die();
}
?>