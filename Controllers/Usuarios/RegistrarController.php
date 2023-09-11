<?php
require_once "Models/Sede.php";
require_once "Models/Enums/EstadoCivil.php";
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $usuario = empty($_GET['id']) || $_GET['id'] == '0' ? new Usuario() : Usuario::cargar($_GET['id']);
    $roles = Rol::listar(1);
    $sedes = Sede::listar(1);

    require_once "Views/Usuarios/_ModalUsuario.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    requierePermisos("registrarUsuarios");
    $usuario = new Usuario();
    $usuario->mapFromPost();
    $idRoles = $_POST['idRoles'];
    
    foreach ($idRoles as $idRol) {
        $usuario->roles[] = Rol::cargar($idRol);
    }
    
    if (!$usuario->esValido()) {
        header("Location: /AppwebMVC/Usuarios/");
        exit();
    }
    
    try {
        $usuario->clave = password_hash($usuario->clave, PASSWORD_DEFAULT);

        $usuario->registrar();
    } catch (\Throwable $th) {
        header("Location: /AppwebMVC/Usuarios/");
        exit();
    }

    $_SESSION['exitos'][] = "Usuario registrado con exito.";
    Bitacora::registrar("Registro al usuario $usuario->nombre $usuario->apellido");
    header("Location: /AppwebMVC/Usuarios/");
}
else {
    http_response_code(405);
    die();
}
?>