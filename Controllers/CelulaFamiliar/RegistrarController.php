<?php
require_once "Models/CelulaFamiliar.php";

$CelulaFamiliar = new CelulaFamiliar();

if (isset($_POST['registrar'])) {   

    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $idCoLider = $_POST['idCoLider'];
    $idTerritorio = $_POST['idTerritorio'];

 
   $CelulaFamiliar->registrar_CelulaFamiliar($nombre, $idLider, $idCoLider, $idTerritorio);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaLideres'])) {  
    
    $ListaLideres = $CelulaFamiliar->listar_lideres();

    echo json_encode($ListaLideres);
   
    die();
}

if (isset($_GET['listaTerritorio'])) {  
    
    $Listaterritorio = $CelulaFamiliar->listar_territorios();

    echo json_encode($Listaterritorio);
   
    die();
}
   
renderView();
?>

