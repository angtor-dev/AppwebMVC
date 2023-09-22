<?php
require_once "Models/Territorio.php";

necesitaAutenticacion();

requierePermiso("territorios", "registrar");

$usuarioSesion = $_SESSION['usuario'];

$Territorio = new Territorio();

if (isset($_POST['registrar'])) {   

    $idSede = trim($_POST['idSede']);
    $nombre = trim(strtolower($_POST['nombre']));
    $idLider = trim($_POST['idLider']);
    $detalles = trim(strtolower($_POST['detalles']));
    
    $Territorio->validacion_datos($idSede, $nombre, $idLider, $detalles);
    $Territorio->validacion_existencia($nombre, $idTerritorio = '');
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