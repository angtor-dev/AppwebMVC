<?php
necesitaAutenticacion();
requierePermisos("consultarRoles");

$roles = Rol::listar(1);

renderView();
?>