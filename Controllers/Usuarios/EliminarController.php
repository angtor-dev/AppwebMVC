<?php
necesitaAutenticacion();
requierePermisos("eliminarUsuarios");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún usuario para eliminar.";
    redirigir("/AppwebMVC/Usuarios/");
}

try {
    $usuario = new Usuario();
    $usuario->id = $_GET['id'];

    $usuario->eliminar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Usuarios/");
}

$_SESSION['exitos'][] = "Se ha eliminado al usuario correctamente.";
Bitacora::registrar("Elimino al usuario ".$usuario->getNombreCompleto());
redirigir("/AppwebMVC/Usuarios/");
?>