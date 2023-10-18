<?php
require_once "Models/Nota.php";

/** @var Usuario */
$estudiante = Usuario::cargar($_GET['id']);
$grupo = $estudiante->getGrupo();
$clases = Clase::cargarRelaciones($grupo->id, get_class($grupo), 1);

require_once "Views/Notas/_ModalNotas.php";
exit;
?>