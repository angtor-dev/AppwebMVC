<?php

$usuario = new Usuario;



if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $token = $_GET['token'] ?? '';

    if (!empty($token)) {

        if (($usuario->tokenValidation($token))) {
            renderView();
        } else {

            renderView("AccesoDenegado", "PasswordRecovery/");
        }

    }

    die();
}

function enviarRespuesta($codigo, $mensaje) {
    http_response_code($codigo);
    echo json_encode(array('msj' => $mensaje));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recovery'])) {
    $nuevaPassword = trim($_POST['nuevaPassword1'] ?? '');
    $repetirPassword = trim($_POST['repetirPassword1'] ?? '');
    $token = trim($_POST['token1'] ?? '');

    if (empty($nuevaPassword) || empty($repetirPassword)) {
        enviarRespuesta(402, 'Los campos deben estar llenos');
    }

    if ($nuevaPassword != $repetirPassword) {
        enviarRespuesta(402, 'Las claves no coinciden');
    }

    if (!preg_match(REG_CLAVE, $nuevaPassword)) {
        enviarRespuesta(402, 'La clave nueva debe poseer al menos una letra, un número y 6 caracteres de longitud.');
    }

    if (!$usuario->tokenValidation($token)) {
        renderView("AccesoDenegado", "PasswordRecovery/");
        exit;
    }

    if (!$usuario->actualizarClaveToken($nuevaPassword)) {
        enviarRespuesta(402, 'Ocurrio un problema al recuperar la contraseña.');
        exit;
    }else{
        http_response_code(200);
    }
}

?>