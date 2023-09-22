<?php
necesitaAutenticacion();
requierePermiso("bitacora", "consultar");

$bitacoras = Bitacora::listar();

renderView();
?>