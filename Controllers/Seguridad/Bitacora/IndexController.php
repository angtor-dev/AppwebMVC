<?php
necesitaAutenticacion();
requierePermisos("consultarBitacora");

$bitacoras = Bitacora::listar();

renderView();
?>