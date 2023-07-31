<?php
necesitaAutenticacion();
$usuarios = Usuario::listar();

renderView();
?>