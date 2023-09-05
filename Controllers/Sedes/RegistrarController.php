<?php
require_once "Models/Sede.php";

$Sede = new Sede();

if (isset($_POST['registrar'])) {   

    $idPastor = $_POST['idPastor'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
 
   $Sede->registrar_Sede($idPastor, $nombre, $direccion, $estado);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaPastores'])) {  
    
    $ListaPastores = $Sede->listar_Pastores();

    echo json_encode($ListaPastores);
   
    die();
}
   
renderView();
?>