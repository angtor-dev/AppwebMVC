<?php
require_once "Models/Nota.php";
necesitaAutenticacion();
requierePermiso("notas", "eliminar");

try {
    $id = $_POST['id'];
    $nota = Nota::cargar($id);
    $nota->eliminar(false);
} catch (\Throwable $th) {
    http_response_code(500);
    throw $th;
}
?>