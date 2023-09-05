<?php
require_once "Models/CelulaCrecimiento.php";

$CelulaCrecimiento = new CelulaCrecimiento();

if (isset($_POST['registrar'])) {   

    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $idCoLider = $_POST['idCoLider'];
    $idTerritorio = $_POST['idTerritorio'];

 
   $CelulaCrecimiento->registrar_CelulaCrecimiento($nombre, $idLider, $idCoLider, $idTerritorio);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaLideres'])) {  
    
    $ListaLideres = $CelulaCrecimiento->listar_lideres();

    echo json_encode($ListaLideres);
   
    die();
}

if (isset($_GET['listaTerritorio'])) {  
    
    $Listaterritorio = $CelulaCrecimiento->listar_territorios();

    echo json_encode($Listaterritorio);
   
    die();
}
   
renderView();
?>

