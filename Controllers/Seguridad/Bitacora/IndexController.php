<?php
require_once "Models/Bitacora.php";
necesitaAutenticacion();
requierePermiso("bitacora", "consultar");

$Bitacora = new Bitacora();

if (isset($_GET['cargar_data'])) {

    $Lista = $Bitacora->listar_Bitacora();
    $json = array();
  
    if (!empty($Lista)) {
        foreach ($Lista as $key) {
            $json['data'][] = $key;
        }
    } else {
        $json['data'] = array();
    }

    echo json_encode($json);
    die();
}



if (isset($_POST['getbd'])) {
   $Bitacora->backupDatabase();
    die();
}

renderView();
?>