<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "consultar");

$clases = Clase::listar(1);

renderView();
?>