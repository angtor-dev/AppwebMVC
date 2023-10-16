<?php
if (empty($_GET['id'])) {
    http_response_code(400);
    echo "Notificacion invalida";
    die;
}

/** @var Notificacion */
$notificacion = Notificacion::cargar($_GET['id']);
$usuario = $_SESSION['usuario'];

if ($notificacion->getIdUsuario() != $usuario->id) {
    http_response_code(403);
    echo "Acceso a notificacion denegado";
    die;
}

if ($notificacion->getVisto()) {
    http_response_code(400);
    echo "La notificacion ya ha sido marcada";
    die;
}

$notificacion->marcarVisto();
?>