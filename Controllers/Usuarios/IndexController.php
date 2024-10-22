<?php

necesitaAutenticacion();
requierePermiso("usuarios", "consultar");

$usuarios = Usuario::listar(1);

renderView("Index");
?>