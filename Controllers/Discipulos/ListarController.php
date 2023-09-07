<?php
require_once "Models/Sede.php";
// Logica del controlador

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

if (!$usuarioSesion->tienePermiso("listarDiscipulos")) {
    $_SESSION['errores'][] = "No posee permiso para listar Discipulos.";
    redirigir("/AppwebMVC/Home/");
}

$Discipulo = new Discipulo();

if (isset($_GET['cargar_data'])) {  
   //Primero inicializamos las variables
    $Lista = $Discipulo->listar_discipulo();
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
        $json['data']['codigo'] = null;
        
    
    }
    //Finalmente, aqui enviamos el listado
    echo json_encode($json);
    die();
} 


if (isset($_GET['listaConsolidador'])) {  
    
    $ListaConsolidador = $Discipulo->listar_consolidador();

    echo json_encode($ListaConsolidador);
   
    die();
}



if (isset($_GET['listarcelulas'])) {  
   
   $listacelulas = $Discipulo->listar_celulas();


   echo json_encode($listacelulas);
  
   die();
}

if (isset($_POST['editar'])) {   


    $id = $_POST['id'];
    $idPastor = $_POST['idPastor'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
 
   $Sede->editar_discipulo($id, $idPastor, $nombre, $direccion, $estado);

   echo json_encode('Lo logramos!!');
   die(); 
    
}

if (isset($_POST['eliminar'])) {   

    if (!$usuarioSesion->tienePermiso("eliminarDiscipulos")) {
        $_SESSION['errores'][] = "No posee permiso para eliminar Sede.";
        redirigir("/AppwebMVC/Home/");
    }

    $id = $_POST['id'];

   $Sede->eliminar_discipulo($id);

   echo json_encode('Lo logramos!!');
   die();

}
   
renderView();
?>



