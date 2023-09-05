<?php
necesitaAutenticacion();
requierePermisos("actualizarRoles");

if (!$_SERVER['REQUEST_METHOD'] === 'POST')
{
    http_response_code(405);
    die();
}

$rol = new Rol();
$rol->mapFromPost();

if (Rol::cargar($rol->id) == null) {
    $_SESSION['errores'][] = "El rol que intentas actualizar no existe.";
    header("Location: /AppwebMVC/Seguridad/Roles/");
    exit();
}
    
if (!$rol->esValido()) {
    header("Location: /AppwebMVC/Seguridad/Roles/");
    exit();
}

try {
    $rol->actualizar();
} catch (\Throwable $th) {
    header("Location: /AppwebMVC/Seguridad/Roles/");
    exit();
}

$_SESSION['exitos'][] = "Rol actualizado con exito.";
header("Location: /AppwebMVC/Seguridad/Roles/");
?>