<?php
necesitaAutenticacion();
requierePermiso("permisos", "gestionar");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die();
}

$permiso = new Permiso();
$permisos->mapFromPost();

try {
    $permiso->actualizar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Seguridad/Roles");
}

$_SESSION['exitos'][] = "Permisos actualizados correctamente.";
Bitacora::registrar("Actualizo los permisos del rol $rol->nombre");
redirigir("/AppwebMVC/Seguridad/Roles");
?>