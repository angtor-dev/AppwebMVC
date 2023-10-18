<?php
require_once "Models/Enums/EstadoCivil.php";
necesitaAutenticacion();
requierePermiso("usuarios", "consultar");

$usuario = Usuario::cargar($_GET['id']);

renderView();
?>