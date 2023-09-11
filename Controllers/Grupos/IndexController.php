<?php
require_once "Models/Grupo.php";
necesitaAutenticacion();
/** @var Usuario */
$usuario = $_SESSION['usuario'];
/** @var array<Grupo> */
$grupos = array();

if ($usuario->tieneRol("Administrador")) {
    $grupos = Grupo::listar(1);
} elseif (true/*$usuario->tienePermiso("Solo sus grupos")*/) {
    $grupos = Grupo::cargarRelaciones();
}
?>