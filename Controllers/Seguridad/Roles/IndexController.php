<?php
necesitaAutenticacion();

$roles = Rol::listar();

renderView();
?>