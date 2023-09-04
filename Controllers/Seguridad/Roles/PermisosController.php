<?php
necesitaAutenticacion();

if (empty($_GET['id']) || $_GET['id'] == '0') {
    http_response_code(400);
    echo "<span class=\"ajaxError\">Se debe especificar un rol.</span>";
    exit();
}

$rol = Rol::cargar($_GET['id']);

require_once "Views/Seguridad/Roles/_ModalPermisos.php";
?>