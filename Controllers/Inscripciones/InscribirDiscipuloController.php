<?php
require_once "Models/Discipulo.php";
necesitaAutenticacion();

if (empty($_POST || empty($_POST['id']))) {
    $_SESSION['errores'][] = "Se debe especificar el discipulo";
    redirigir("/AppwebMVC/Inscripciones");
}

/** @var Discipulo */
$discipulo = Discipulo::cargar($_POST['id']);
try {
    $usuario = new Usuario();
    $usuario->fromDiscipulo($discipulo);
    $usuario->registrar();

    $discipulo->marcarUsuarioCreado();
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/Inscripciones");
}

// TODO: Redirigir a asignación de grupo para el usuario
Bitacora::registrar("Inscribio al usuario ".$usuario->getNombreCompleto()." con exito.");
$_SESSION['exitos'][] = "Se inscribio al usuario ".$usuario->getNombreCompleto()." con exito.";
redirigir("/AppwebMVC/Inscripciones");
?>
