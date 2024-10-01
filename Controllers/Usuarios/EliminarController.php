<?php
necesitaAutenticacion();
requierePermiso("usuarios", "eliminar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún usuario para eliminar.";
    redirigir("/AppwebMVC/Usuarios/");
}

try {
    /** @var Usuario */
    $usuario = Usuario::cargar($_GET['id']);

    if (!isset($usuario)) {
        $_SESSION['errores'][] = "El usuario que intenta eliminar no existe.";
        redirigir("/AppwebMVC/Usuarios/");
    }

    $usuario->eliminar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Usuarios/");
}
Bitacora::registrar("Elimino al usuario ".$usuario->getNombreCompleto());
redirigir("/AppwebMVC/Usuarios/");
?>