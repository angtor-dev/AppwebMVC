<?php
require_once "Models/Evento.php";
/** @var Evento[] */
$eventos = Evento::listar();

renderView(); 
?>