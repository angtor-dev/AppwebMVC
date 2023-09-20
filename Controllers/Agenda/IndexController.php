<?php
require_once "Models/Evento.php";
$eventos = Evento::listar();

renderView(); 
?>