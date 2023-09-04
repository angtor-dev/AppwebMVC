<?php
necesitaAutenticacion();

$usuarios = Usuario::listar(1);

renderView("Index");
?>