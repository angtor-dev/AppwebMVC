<?php
necesitaAutenticacion();
// TODO: Validar metodo, si es GET redirigir a Usuarios/Index
// TODO: Validar datos antes de registrar y toda esa vaina
// TODO: Registrar usuario

$alertas['exito'][] = "Usuario registrado con exito."; // Mensaje de prueba
require_once "Controllers/Usuarios/IndexController.php"; // Redirige a otro controlador
?>