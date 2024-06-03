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
        } else {
            http_response_code(402);
            echo json_encode(array('msj' => 'Datos incorrectos'));
        }

        exit();
    }


    // Inicio sesion app movil
    /* if (isset($_POST['cedulaMovil']) && isset($_POST['claveMovil'])) {
        if ($usuario->login($_POST['cedulaMovil'], $_POST['claveMovil'])) {
            Bitacora::registrar("Inicio de sesión por aplicacion movil");
            http_response_code(200);

            echo json_encode(
                array(
                    'msj' => 'Has iniciado sesion correctamente',
                )
            );

        } else {
            http_response_code(402);
            $loginFails = true;
            echo json_encode(array('msj' => 'Datos incorrectos. Intente nuevamente'));
        }

        exit();
    } */


    if (isset($_POST['recovery'])) {
        $cedulaRecovery = $_POST['cedulaRecovery'];
        $datos = $usuario->recovery($cedulaRecovery);

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
        $cedulaRecovery = $_POST['cedulaRecovery'];
        $respuesta = $_POST['respuesta'];
        $correo = $_POST['correo'];

        $datos = $usuario->resetPassword($cedulaRecovery, $respuesta);

        if ($datos !== '') {
            http_response_code(200);
            $Correo->sendPassword($correo, $datos);
        } else {
            http_response_code(402);
            echo json_encode(array('msj' => 'La respuesta enviada es incorrecta'));
        }
        die();
    }

    if (isset($_POST['register'])) {

        $respuesta = $usuario->validarRegister($_POST);

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