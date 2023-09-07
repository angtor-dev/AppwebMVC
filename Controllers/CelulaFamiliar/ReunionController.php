
<?php

require_once "Models/CelulaFamiliar.php";

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];



$CelulaFamiliar = new CelulaFamiliar();


if (isset($_GET['cargar_data'])) {  
    //Primero inicializamos las variables
     $Lista = $CelulaFamiliar->listar_reuniones();
     //Variable json solamente para guardar el array de datos
     $json = array();
 
      //Comrpobamos primero que la lista no este vacia
     if (!empty($Lista)) {
         //Si no esta vacia, empezara a recorrer cada columna de la consulta sql, es decir, los datos obtenidos
         foreach ($Lista as $key) {
         //ese data es un indice que estoy creando para datatables, ya que ese es el indice que leera, fijate en el datatables para que veas que llama a los datos asi {data: 'example'}   
             $json['data'][] = $key;
         }
     } else {
        //Si el listado esta vacio, hara esto
         //Aqui esta guardando en esa variable llamada json un arreglo vacio porque obvio no hay nada si cayo aqui ok?
         //Si esto no se hace, el datatables dara error porque no se le esta enviado nada. Esto es como un feedback para el datatables
         $json['data'][] = null;
         
     
     }
     //Finalmente, aqui enviamos el listado
     echo json_encode($json);
     die();
 } 

if (isset($_POST['editar'])) { 

    if (!$usuarioSesion->tienePermiso("actualizarCelulaFamiliar")) {
        $_SESSION['errores'][] = "No seposee permiso para editar reunion.";
        redirigir("/AppwebMVC/Home/");
    }
    
    
    $id = $_POST['id'];
    $idCelulaFamiliar = $_POST['idCelulaFamiliar'];
    $fecha = $_POST['fecha'];
    $tematica = $_POST['tematica'];
    $semana = $_POST['semana'];
    $generosidad = $_POST['generosidad'];
    $infantil = $_POST['infantil'];
    $juvenil = $_POST['juvenil'];
    $adulto = $_POST['adulto'];
    $actividad = $_POST['actividad'];
    $observaciones = $_POST['observaciones'];

 
   $CelulaFamiliar->editar_reuniones($id, $idCelulaFamiliar, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_POST['eliminar'])) {  
    
    if (!$usuarioSesion->tienePermiso("eliminarCelulaFamiliar")) {
        $_SESSION['errores'][] = "No posee permiso para eliminar reunion.";
        redirigir("/AppwebMVC/Home/");
    }

    $id = $_POST['id'];

   $CelulaFamiliar->eliminar_reuniones($id);

   echo json_encode('Lo logramos!!');
   die();

}


if (isset($_GET['listarcelulas'])) {  
    
    $listaCelulas = $CelulaFamiliar->listar_celulas();


    echo json_encode($listaCelulas);
   
    die();
}





renderView();
?>