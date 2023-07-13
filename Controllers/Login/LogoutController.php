<?php
necesitaAutenticacion();
session_destroy();

// TODO: Registrar cierre de sesión

header('location:'.LOCAL_DIR.'login');
?>