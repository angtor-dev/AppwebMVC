<?php
require_once "Models/Grupo.php";
require_once "Models/Enums/EstadosGrupo.php";
necesitaAutenticacion();
//requierePermiso("grupos", "registrar");

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    /** @var Usuario */
    $usuario = $_SESSION['usuario'];
    $escuela = $usuario->sede->getEscuela();

    $niveles = NivelCrecimiento::cargarRelaciones($escuela->id, "Escuela", 1);
    $profesores = Usuario::listarPorRoles("Profesor");

    renderView();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{

    $grupo = new Grupo();

    $grupo->mapFromPost();
    $grupo->estado = EstadosGrupo::Activo->value;
    
    if (!$grupo->esValido()) {
        /** @var Usuario */
        $usuario = $_SESSION['usuario'];
        $escuela = $usuario->sede->getEscuela();

        $niveles = NivelCrecimiento::cargarRelaciones($escuela->id, "Escuela", 1);
        $profesores = Usuario::listarPorRoles("Profesor");
        
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