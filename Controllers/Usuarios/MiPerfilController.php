<?php
    necesitaAutenticacion();
    /** @var Usuario */
    $usuario = Usuario::cargar($_SESSION['usuario']->id);

    if (isset($_GET['getDatos'])) {
        echo json_encode(array($usuario->getNombre(), $usuario->getApellido(), $usuario->getTelefono(), $usuario->getCedula(),
        $usuario->getFechaNacimiento(), $usuario->getEstadoCivil(), $usuario->getCorreo(), $usuario->sede->getNombre(), $usuario->getDireccion(),
        $usuario->getPreguntaSecurity(), $usuario->getRespuestaSecurity()));
        die();
    }

    if (isset($_POST['saveDatos'])) {
        $respuesta = $usuario->validarActualizarPerfil($_POST, 1);

        if ($respuesta) {
            $respuesta2 = $usuario->actualizarPerfil(1);
            if ($respuesta2) {
                http_response_code(200);
                Bitacora::registrar("El usuario ".$usuario->getNombreCompleto()." ha actualizado sus datos");
                echo json_encode(array('msj'=>'Actualizado correctamente', 'status'=>200));
            }else{
                http_response_code(402);
                echo json_encode(array('msj'=>'Error al actualizar'));
            }
        }else{
            http_response_code(402);
            echo json_encode(array('msj'=>'Validacion no lograda'));
        }

        die();
    }

    if (isset($_POST['savePregunta'])) {
        $respuesta = $usuario->validarActualizarPerfil($_POST, 2);

        if ($respuesta) {
            $respuesta2 = $usuario->actualizarPerfil(2);
            if ($respuesta2) {
                http_response_code(200);
                Bitacora::registrar("El usuario ".$usuario->getNombreCompleto()." ha actualizado su pregunta de seguridad");
                echo json_encode(array('msj'=>'Actualizado correctamente', 'status'=>200));
            }else{
                http_response_code(402);
                echo json_encode(array('msj'=>'Error al actualizar'));
            }
        }else{
            http_response_code(402);
            echo json_encode(array('msj'=>'Validacion no lograda'));
        }

        die();
    }



    renderView();
?>