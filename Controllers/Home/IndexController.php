<?php
necesitaAutenticacion();
$sede = Sede::cargar($_SESSION['usuario']->idSede);

renderView();
?>