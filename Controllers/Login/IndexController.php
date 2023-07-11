<?php
require_once "Models/Usuario.php";

session_start();
session_destroy();
$loginFails = false;

if (!empty($_POST)) {
    $usuario = new Usuario();

    if ($usuario->login($_POST['correo'], $_POST['clave'])) {
        session_start();
        $_SESSION['usuario'] = $usuario;

        header('location:home');
        exit();
    }
    else {
        $loginFails = true;
    }
}

renderView();
?>