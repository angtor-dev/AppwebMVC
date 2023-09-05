<?php
require_once "Models/CCrecimiento.php";

$CCrecimiento = new CCrecimiento();

if (isset($_POST['registrar'])) {   

    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $idCoLider = $_POST['idCoLider'];
    $idTerritorio = $_POST['idTerritorio'];

 
   $CCrecimiento->registrar_CCrecimiento($nombre, $idLider, $idCoLider, $idTerritorio);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaLideres'])) {  
    
    $ListaLideres = $CCrecimiento->listar_lideres();

    echo json_encode($ListaLideres);
   
    die();
}

if (isset($_GET['listaTerritorio'])) {  
    
    $Listaterritorio = $CCrecimiento->listar_territorios();

    echo json_encode($Listaterritorio);
   
    die();
}
   
renderView();
?>

