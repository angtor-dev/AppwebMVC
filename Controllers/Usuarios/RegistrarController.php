<?php
require_once "Models/Enums/EstadoCivil.php";
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $usuario = empty($_GET['id']) || $_GET['id'] == '0' ? new Usuario() : Usuario::cargar($_GET['id']);

    require_once "Views/Usuarios/_ModalUsuario.php";
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // TODO: Validar datos antes de registrar y toda esa vaina
    // TODO: Registrar usuario

    echo $alertas['exito'][] = "Usuario registrado con exito."; // Mensaje de prueba
}
else {
    http_response_code(405);
    die();
}
?>