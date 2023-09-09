<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaConsolidacion.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

$CelulaConsolidacion = new CelulaConsolidacion();


if (!$usuarioSesion->tienePermiso("registrarCelulaConsolidacion")) {
    $_SESSION['errores'][] = "No seposee permiso para registrar Sede.";
    redirigir("/AppwebMVC/Home/");
}

if (isset($_POST['registrar'])) {   

    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $idCoLider = $_POST['idCoLider'];
    $idTerritorio = $_POST['idTerritorio'];

 
   $CelulaConsolidacion->registrar_CelulaConsolidacion($nombre, $idLider, $idCoLider, $idTerritorio);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaLideres'])) {  
    
    $ListaLideres = $CelulaConsolidacion->listar_lideres();

    echo json_encode($ListaLideres);
   
    die();
}

if (isset($_GET['listaTerritorio'])) {  
    
    $Listaterritorio = $CelulaConsolidacion->listar_territorios();

    echo json_encode($Listaterritorio);
   
    die();
}
   
renderView();
?>

