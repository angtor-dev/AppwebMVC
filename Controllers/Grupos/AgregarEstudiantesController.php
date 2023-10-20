<?php
require_once "Models/Grupo.php";
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    /** @var Grupo */
    $grupo = Grupo::cargar($_GET['id']);
    $nivel = $grupo->subnivel->nivelCrecimiento;
    /** @var Usuario[] */
    $estudiantesTodos = Usuario::listarPorRoles('Estudiante');
    $estudiantes = array();
    foreach ($estudiantesTodos as $estudiante) {
        $grupoActivo = $estudiante->getGrupoActivo();
        if (is_null($grupoActivo) || $grupoActivo->id == $grupo->id) {
            $estudiantes[] = $estudiante;
        }
    }
    // TODO: filtrar estudiantes por sede

    require_once "Views/Grupos/_AgregarEstudiantes.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    /** @var Grupo */
    $grupo = Grupo::cargar($_POST['idGrupo']);

    foreach ($grupo->matriculas as $matricula) {
        $matricula->eliminar(false);
    }

    foreach ($_POST['estudiantes'] as $idEstudiante) {
        $matricula = new Matricula();
        $matricula->setIdGrupo($grupo->id);
        $matricula->setIdEstudiante($idEstudiante);
        $matricula->setEstado(0);

        try {
            $matricula->registrar();
            Notificacion::registrar($idEstudiante, "Inscrito en grupo", "Has sido inscrito en el grupo ".$grupo->getNombre());
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    $_SESSION['exitos'][] = "Estudiantes del grupo actuliados con exito.";
    Bitacora::registrar("Actualizo los estudiantes del grupo ".$grupo->getNombre());
    redirigir("/AppwebMVC/Grupos/Gestionar?id=".$grupo->id);
}
else {
    http_response_code(405);
    die();
}
?>