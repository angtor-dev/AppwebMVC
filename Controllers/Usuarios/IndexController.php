<?php
necesitaAutenticacion();
requierePermisos("consultarUsuarios");

$usuarios = Usuario::listar(1);

renderView("Index");
?>