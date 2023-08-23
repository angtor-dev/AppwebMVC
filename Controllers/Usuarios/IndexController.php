<?php
necesitaAutenticacion();

$usuarios = Usuario::listar();

renderView("Index");
?>