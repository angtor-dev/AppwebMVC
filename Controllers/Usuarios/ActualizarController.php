<?php
necesitaAutenticacion();
requierePermisos("actualizarUsuarios");

if (!$_SERVER['REQUEST_METHOD'] === 'POST')
{
    http_response_code(405);
    die();
}

$usuario = new Usuario();
$usuario->mapFromPost();
$idRoles = $_POST['idRoles'];

foreach ($idRoles as $idRol) {
    $usuario->roles[] = Rol::cargar($idRol);
}

if (Usuario::cargar($usuario->id) == null) {
    $_SESSION['errores'][] = "El usuario que intentas actualizar no existe.";
    redirigir("/AppwebMVC/Usuarios/");
}
    
if (!$usuario->esValido()) {
    redirigir("/AppwebMVC/Usuarios/");
}

try {
    $usuario->actualizar();
} catch (\Throwable $th) {
    redirigir("/AppwebMVC/Usuarios/");
}

$_SESSION['exitos'][] = "Usuario actualizado con exito.";
Bitacora::registrar("Actualizo al usuario $usuario->nombre $usuario->apellido");
header("Location: /AppwebMVC/Usuarios/");
?>