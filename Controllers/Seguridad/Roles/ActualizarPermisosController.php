<?php
necesitaAutenticacion();
requierePermisos("gestionarPermisos");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die();
}

$permisos = new Permisos();
$permisos->mapFromPost();

try {
    $permisos->actualizar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Seguridad/Roles");
}

$_SESSION['exitos'][] = "Permisos actualizados correctamente.";
Bitacora::registrar("Actualizo los permisos del rol $rol->nombre");
redirigir("/AppwebMVC/Seguridad/Roles");
?>