<?php
require_once "Models/Discipulo.php";

if (empty($_GET) || empty($_GET['id'])) {
    http_response_code(405);
    exit;
}

$discipulo = Discipulo::cargar($_GET['id']);

require "Views/Inscripciones/_ModalDiscipulo.php";
exit;
?>