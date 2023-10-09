<?php
require_once "Models/Sede.php";
necesitaAutenticacion();
$sede = Sede::cargar($_SESSION['usuario']->idSede);
$estudiantes = Usuario::listarPorRoles("Estudiante");

renderView();
?>