<?php
session_destroy();
$loginFails = false;

if (!empty($_POST)) {
    $usuario = new Usuario();

    if (isset($_POST['cedula']) && isset($_POST['clave'])) {
        if ($usuario->login($_POST['cedula'], $_POST['clave'])) {
            Bitacora::registrar("Inicio de sesión");
            http_response_code(200);
            header('location:' . LOCAL_DIR);
            exit();
        } else {
            http_response_code(402);
            $loginFails = true;
        }

    } else {
        http_response_code(403);
        $loginFails = true;
    }


    // Inicio sesion app movil
    if (isset($_POST['cedulaMovil']) && isset($_POST['claveMovil'])) {
        if ($usuario->login($_POST['cedulaMovil'], $_POST['claveMovil'])) {
            Bitacora::registrar("Inicio de sesión por aplicacion movil");
            http_response_code(200);
            $usuario = $_SESSION['usuario'];
            $usuario = Usuario::cargar($usuario->id);

            echo json_encode('Has iniciado sesion como: '.$usuario->getNombreCompleto());
            exit();
        } else {
            http_response_code(402);
            $loginFails = true;
        }

    } else {
        http_response_code(403);
        $loginFails = true;
    }

    if (isset($_POST['recovery'])) {
        $cedulaRecovery = $_POST['cedulaRecovery'];
        $datos = $usuario->recovery($cedulaRecovery);

        if ($datos == []) {
            http_response_code(402);
            echo json_encode(array('msj'=> 'La cedula ingresada no existe'));
        }else{
            http_response_code(200);
            echo json_encode($datos);
        }
        exit();
    }

    if (isset($_POST['sendRecoveryRespuesta'])) {
        $cedulaRecovery = $_POST['cedulaRecovery'];
        $respuesta = $_POST['respuesta'];
        $datos = $usuario->resetPassword($cedulaRecovery, $respuesta);

        if ($datos) {
            http_response_code(200);
            echo json_encode($datos);
        }else{
            http_response_code(402);
            echo json_encode(array('msj'=>'La respuesta enviada es incorrecta'));
        }
        exit();
    }
}

renderView();
?>