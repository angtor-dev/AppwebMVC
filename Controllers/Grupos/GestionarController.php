<?php
require_once "Models/Grupo.php";
necesitaAutenticacion();
requierePermiso("grupos", "actualizar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "Se debe especificar un grupo.";
    redirigir("/AppwebMVC/Grupos");
}

$grupo = Grupo::cargar($_GET['id']);
if (!isset($grupo)) {
    $_SESSION['errores'][] = "El grupo indicado no existe.";
}

// $estudiantes = Usuario::listarPorRoles("Estudiante");

renderView();