<?php
necesitaAutenticacion();
requierePermiso("roles", "actualizar");

if (!$_SERVER['REQUEST_METHOD'] === 'POST')
{
    http_response_code(405);
    die();
}

$rol = new Rol();
$rol->mapFromPost();

if (Rol::cargar($rol->id) == null) {
    $_SESSION['errores'][] = "El rol que intentas actualizar no existe.";
    redirigir("/AppwebMVC/Seguridad/Roles/");
}
    
if (!$rol->esValido()) {
    redirigir("/AppwebMVC/Seguridad/Roles/");
}

try {
    $rol->actualizar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Seguridad/Roles/");
}

$_SESSION['exitos'][] = "Rol actualizado con exito.";
Bitacora::registrar("Actualizo el rol ".$rol->getNombre());
header("Location: /AppwebMVC/Seguridad/Roles/");
?>