<?php
require_once "Models/Enums/EstadoCivil.php";
necesitaAutenticacion();

$usuarios = Usuario::listar();

renderView("Index");
?>