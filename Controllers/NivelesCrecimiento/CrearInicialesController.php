<?php
require_once "Models/NivelCrecimiento.php";
require_once "Models/Escuela.php";
necesitaAutenticacion();
// requierePermisos("registrarNivelesCrecimiento");

$idSede = $_SESSION['usuario']->idSede;
$escuela = Escuela::cargarRelaciones($idSede, "Sede")[0];

$nivelesCrecimiento = NivelCrecimiento::cargarRelaciones($escuela->id, "Escuela");
if (count($nivelesCrecimiento) > 0) {
    $_SESSION['errores'][] = "Ya existen niveles de crecimiento para esta sede.";
    redirigir("/AppwebMVC/NivelesCrecimiento/");
}

try {
    NivelCrecimiento::crearIniciales($escuela->id);
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
}

redirigir("AppwebMVC/NivelesCrecimiento");
?>