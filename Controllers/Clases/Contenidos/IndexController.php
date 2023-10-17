<?php
require_once "Models/Clase.php";
necesitaAutenticacion();
requierePermiso("clases", "consultar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "Se debe especificar una clase.";
    redirigir('/AppwebMVC/Clases');
}
/** @var Clase */
$clase = Clase::cargar($_GET['id']);

if (is_null($clase) || $clase->getEstatus() == 0) {
    $_SESSION['errores'][] = "La clase que intentas ver no existe.";
    redirigir('/AppwebMVC/Clases');
}

renderView();
?>