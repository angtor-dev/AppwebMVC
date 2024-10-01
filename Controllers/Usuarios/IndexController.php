<?php
$_SESSION['usuario'] = Usuario::cargar(1);
necesitaAutenticacion();
requierePermiso("usuarios", "consultar");

$usuarios = Usuario::listar(1);

renderView("Index");
?>