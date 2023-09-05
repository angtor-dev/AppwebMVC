<?php


require_once "Models/Territorio.php";

$Territorio = new Territorio();

if (isset($_POST['registrar'])) {   

    $idSede = $_POST['idSede'];
    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $detalles = $_POST['detalles'];
 
   $Territorio->registrar_territorio($idSede, $nombre, $idLider, $detalles);



   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listaLideres'])) {  
    
     $ListaLideres = $Territorio->listar_lideres();

     echo json_encode($ListaLideres);
    
     die();
 }


 if (isset($_GET['listaSedes'])) {  
    
    $ListaSedes = $Territorio->listar_Sedes();

    $data = json_encode($ListaSedes);


    echo $data; 
   
    die();
}

   

renderView();
?>