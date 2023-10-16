<?php
require_once "Models/Clase.php";
necesitaAutenticacion();

$clases = Clase::listar();

renderView();
?>