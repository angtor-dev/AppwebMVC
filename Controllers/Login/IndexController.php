<?php
session_destroy();
$loginFails = false;

if (!empty($_POST)) {
    $usuario = new Usuario();

    if ($usuario->login($_POST['cedula'], $_POST['clave'])) {
        session_start();
        $_SESSION['usuario'] = $usuario;

        header('location:'.LOCAL_DIR);
        exit();
    }
    else {
        $loginFails = true;
    }
}

renderView();
?>