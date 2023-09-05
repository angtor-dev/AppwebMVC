<?php
require_once "Models/CFamiliar.php";

$CFamiliar = new CFamiliar();

if (isset($_POST['registrar'])) {   

    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $idCoLider = $_POST['idCoLider'];
    $idTerritorio = $_POST['idTerritorio'];

 
   $CFamiliar->registrar_CFamiliar($nombre, $idLider, $idCoLider, $idTerritorio);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaLideres'])) {  
    
    $ListaLideres = $CFamiliar->listar_lideres();

    echo json_encode($ListaLideres);
   
    die();
}

if (isset($_GET['listaTerritorio'])) {  
    
    $Listaterritorio = $CFamiliar->listar_territorios();

    echo json_encode($Listaterritorio);
   
    die();
}
   
renderView();
?>

