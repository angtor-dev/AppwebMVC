<?php
if (empty($_GET['tipo']) || empty($_GET['valor'])) {
    http_response_code(400);
    echo "Debe especificar un tipo y un valor para la busqueda";
    exit();
}

$tipo = $_GET['tipo'];
$valor = $_GET['valor'];
$usuario = null;

if ($tipo == "cedula") {
    $usuario = Usuario::cargarPorCedula($valor);
} elseif ($tipo == "correo") {
    $usuario = Usuario::cargarPorCorreo($valor);
} else {
    http_response_code(400);
    echo "El tipo de busqueda es invalido";
    exit();
}

echo is_null($usuario) ? "false" : "true";
?>