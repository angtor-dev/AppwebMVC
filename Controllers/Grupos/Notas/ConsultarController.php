<?php
require_once "Models/Nota.php";

/** @var Usuario */
$estudiante = Usuario::cargar($_GET['id']);
$grupo = $estudiante->getGrupoActivo();
$clases = Clase::cargarRelaciones($grupo->id, get_class($grupo), 1);

require_once "Views/Grupos/_ModalNotas.php";
exit;
?>