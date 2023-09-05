<?php
necesitaAutenticacion();
/** @var Usuario */
$usuarioSesion = $_SESSION['usuario'];

if (!$usuarioSesion->tienePermiso("eliminaUsuarios")) {
    $_SESSION['errores'][] = "No posees permiso para eliminar usuarios.";
    redirigir("/AppwebMVC/Usuarios/");
}
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
redirigir("/AppwebMVC/Usuarios/");
?>