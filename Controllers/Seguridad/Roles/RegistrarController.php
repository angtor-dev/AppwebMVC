<?php
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $rol = empty($_GET['id']) || $_GET['id'] == '0' ? new Rol() : Rol::cargar($_GET['id']);

    require_once "Views/Seguridad/Roles/_ModalRol.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $rol = new Rol();
    $rol->mapFromPost();
    
    if (!$rol->esValido()) {
        header("Location: /AppwebMVC/Seguridad/Roles/");
        exit();
    }

    try {
        $rol->registrar();
    } catch (\Throwable $th) {
        header("Location: /AppwebMVC/Seguridad/Roles/");
        exit();
    }

    $_SESSION['exitos'][] = "Rol registrado con exito.";
    header("Location: /AppwebMVC/Seguridad/Roles/");
}
else {
    http_response_code(405);
    die();
}
?>