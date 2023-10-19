<?php
necesitaAutenticacion();
session_destroy();

Bitacora::registrar("Cierre de sesión");

header('location:'.LOCAL_DIR.'Login');
?>