<?php
necesitaAutenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    renderView();
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /** @var Usuario */
    $usuario = Usuario::cargar($_SESSION['usuario']->id);
    $claveActual = trim($_POST['claveActual']);
    $claveNueva = trim($_POST['claveNueva']);
    $confirmacion = trim($_POST['confirmacion']);

    if ($claveNueva != $confirmacion) {
        $_SESSION['errores'][] = "Las claves no coinciden";
        redirigir("/AppwebMVC/Usuarios/CambiarClave");
    }
    if (!password_verify($claveActual, $usuario->getClaveEncriptada())) {
        $_SESSION['errores'][] = "La clave actual es incorrecta";
        redirigir("/AppwebMVC/Usuarios/CambiarClave");
    }
    if (!preg_match(REG_CLAVE, $claveNueva)) {
        $_SESSION['errores'][] = "La clave nueva debe poseer al menos una letra, un número y 6 caracteres de longitud.";
        redirigir("/AppwebMVC/Usuarios/CambiarClave");
    }

    try {
        $usuario->actualizarClave($claveNueva);
    } catch (\Throwable $th) {
        redirigir("/AppwebMVC/Usuarios/CambiarClave");
    }

    $_SESSION['exitos'][] = "Calve actualizada con exito";
    Bitacora::registrar("Actulizo su clave");
    redirigir("/AppwebMVC/Usuarios/CambiarClave");
}
?>