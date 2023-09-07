<?php
require_once "Models/Escuela.php";
require_once "Models/NivelCrecimiento.php";
necesitaAutenticacion();
// requierePermisos('listarNivelesCrecimiento');

/** @var Usuario */
$usuario = $_SESSION['usuario'];
$escuelas = Escuela::cargarRelaciones($usuario->idSede, "Sede");

// Crea la escuela si no existe
if (count($escuelas) == 0) {
    $escuela = new Escuela();
    $escuela->idSede = $usuario->idSede;
    try {
        $escuela->registrar();
        redirigir("/AppwebMVC/NivelesCrecimiento");
    } catch (\Throwable $th) {
        redirigir("/AppwebMVC/Home/");
    }
}
$escuela = $escuelas[0];

$nivelesCrecimiento = NivelCrecimiento::cargarRelaciones($escuela->id, "Escuela");

renderView();
?>