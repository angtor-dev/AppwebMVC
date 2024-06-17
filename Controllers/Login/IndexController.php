<?php
session_destroy();
$loginFails = false;
require_once "Models/Correo.php";
$Correo = new Correo();

if (!empty($_POST)) {

    $usuario = new Usuario();

    if (isset($_POST['encryptedLogin'])) {

        if ($usuario->login($_POST['encryptedLogin'])) {
            Bitacora::registrar("Inicio de sesión");
            http_response_code(200);

            echo json_encode($_SESSION['jwt']);
        } else {
            http_response_code(402);
            echo json_encode(array('msj' => 'Datos incorrectos, intente nuevamente'));
        }

        exit();
    }

    if (isset($_POST['encryptedCedulaRecovery'])) {

        $datos = $usuario->recovery($_POST['encryptedCedulaRecovery']);

        if ($datos == []) {
            http_response_code(402);
            echo json_encode(array('msj' => 'La cedula ingresada no existe'));
        } else {
            http_response_code(200);
            echo json_encode($datos);
        }
        die();
    }


    if (isset($_POST['sendRecoveryRespuesta'])) {

        $datos = $usuario->resetPassword($_POST['sendRecoveryRespuesta']);

        if ($datos !== '') {
            http_response_code(200);
            $Correo->sendUrl($datos['correo'], $datos['url']);
        } else {
            http_response_code(402);
            echo json_encode(array('msj' => 'La respuesta enviada es incorrecta'));
        }
        die();
    }

    if (isset($_POST['encryptedRegister'])) {

        $respuesta = $usuario->validarRegister($_POST['encryptedRegister']);

        if ($respuesta) {
            $respuesta2 = $usuario->registerUser();
            if ($respuesta2) {
                http_response_code(200);
                Bitacora::registrar("Se ha regitrado el usuario " . $usuario->getNombreCompleto());
                echo json_encode(array('msj' => 'Registrado correctamente', 'status' => 200));
            } else {
                http_response_code(402);
                echo json_encode(array('msj' => 'Error al registrar'));
            }
        } else {
            http_response_code(402);
            echo json_encode(array('msj' => 'Validacion no lograda'));
        }

        die();
    }

}

if (isset($_GET['getKey'])) {
    echo json_encode(publicKey);
    die();
}

if (isset($_GET['getSedes'])) {

    $usuario = new Usuario();

    $response = $usuario->getSedes();

    if (!empty($response)) {
        http_response_code(200);
        echo json_encode($response);
    } else {
        http_response_code(402);
        echo json_encode(array("msj" => 'Hubo un problema al obtener las sedes'));
    }
    die();
}

renderView();
?>