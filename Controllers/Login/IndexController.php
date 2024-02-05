<?php
session_destroy();
$loginFails = false;

if (!empty($_POST)) {
    $usuario = new Usuario();

    if ($usuario->login($_POST['cedula'], $_POST['clave'])) {
        Bitacora::registrar("Inicio de sesión");
        http_response_code(200);
        header('location:'.LOCAL_DIR);
        exit();
    }
    else {
        http_response_code(402);
        $loginFails = true;
    }
}

renderView();
?>