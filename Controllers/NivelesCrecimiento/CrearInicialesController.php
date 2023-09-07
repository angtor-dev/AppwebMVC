<?php
require_once "Models/NivelCrecimiento.php";
necesitaAutenticacion();
// requierePermisos("registrarNivelesCrecimiento");

$idSede = $_SESSION['usuario']->idSede;
$escuela = Escuela::cargarRelaciones($usuario->idSede, "Sede")[0];

$nivelesCrecimiento = NivelCrecimiento::cargarRelaciones($escuela->id, "Escuela");
if (count($nivelesCrecimiento) > 0) {
    $_SESSION['errores'][] = "Ya existen niveles de crecimiento para esta sede.";
    redirigir("/AppwebMVC/NivelesCrecimiento/");
}

NivelCrecimiento::crearIniciales();
?>