<?php
require_once "Models/Subnivel.php";
necesitaAutenticacion();
requierePermiso("nivelesCrecimiento", "eliminar");

if (empty($_GET['id'])) {
    $_SESSION['errores'][] = "No se especificó ningún subnivel para eliminar.";
    redirigir("/AppwebMVC/NivelesCrecimiento/");
}

try {
    $subnivel = Subnivel::cargar($_GET['id']);

    if ($subnivel == null) {
        $_SESSION['errores'][] = "El subnivel que intentas eliminar ya no existe.";
        redirigir("/AppwebMVC/NivelCrecimiento/");
    }

    $subnivel->eliminar(false);
} catch (\Throwable $th) {
    if (empty($_SESSION['errores'])) {
        $_SESSION['errores'][] = $th->getMessage();
    }
    redirigir("/AppwebMVC/NivelesCrecimiento/");
}

$_SESSION['exitos'][] = "Se ha eliminado al subnivel correctamente.";
redirigir("/AppwebMVC/NivelesCrecimiento/");
?>