<?php
require_once "Models/Grupo.php";
require_once "Models/Enums/EstadosGrupo.php";
necesitaAutenticacion();
/** @var Usuario */
$usuario = $_SESSION['usuario'];
/** @var array<Grupo> */
$grupos = array();

if ($usuario->tieneRol("Superusuario")) {
    $grupos = Grupo::listar(1);
} elseif (true/*$usuario->tienePermiso("Solo sus grupos")*/) {
    $grupos = Grupo::cargarRelaciones($usuario->id, "Profesor", 1);
}

renderView();
?>