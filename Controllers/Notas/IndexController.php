<?php
require_once "Models/Nota.php";
necesitaAutenticacion();
requierePermiso("notas", "consultar");

/** @var Usuario */
$usuario = $_SESSION['usuario'];
$estudiantes = Usuario::listarPorRoles("Estudiante");

// Si es profe, lista solo sus estudiantes
if ($usuario->tieneRol("Profesor")) {

}

renderView();
?>