<?php
require_once "Models/Territorio.php";
require_once "Models/CelulaCrecimiento.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

if (!$usuarioSesion->tienePermiso("registrarCelulaCrecimiento")) {
    $_SESSION['errores'][] = "No posee permiso para registrar Celula de Crecimiento.";
    redirigir("/AppwebMVC/Home/");
}

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

