<?php
require_once "Models/Nota.php";

/** @var Usuario */
$estudiante = Usuario::cargar($_GET['id']);
$grupo = Grupo::cargar($_GET['idGrupo']);
$clases = Clase::cargarRelaciones($grupo->id, get_class($grupo), 1);

require_once "Views/Grupos/_ModalNotas.php";
exit;
?>