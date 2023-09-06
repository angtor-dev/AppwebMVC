<?php
necesitaAutenticacion();
requierePermisos("eliminarRoles");

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún rol para eliminar.";
    redirigir("/AppwebMVC/Seguridad/Roles/");
}

try {
    $rol = Rol::cargar($_GET['id']);

    if ($rol == null) {
        $_SESSION['errores'][] = "El rol que intentas eliminar ya no existe.";
        redirigir("/AppwebMVC/Seguridad/Roles/");
    }

    $rol->eliminar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Seguridad/Roles/");
}

$_SESSION['exitos'][] = "Se ha eliminado el rol correctamente.";
redirigir("/AppwebMVC/Seguridad/Roles/");
?>