<?php

// Logica del controlador

necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];

if (!$usuarioSesion->tienePermiso("listarDiscipulos")) {
    $_SESSION['errores'][] = "No posee permiso para listarDiscipulos.";
    redirigir("/AppwebMVC/Home/");
}

$Territorio = new Territorio();

if (isset($_GET['cargar_data'])) {  
   //Primero inicializamos las variables
    $Lista = $Territorio->listar_territorio();
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


if (isset($_GET['listaLideres'])) {  
    
    $ListaLideres = $Territorio->listar_lideres();

    echo json_encode($ListaLideres);
   
    die();
}


if (isset($_GET['listaSedes'])) {  
   
   $ListaSedes = $Territorio->listar_Sedes();

   echo json_encode($ListaSedes);
  
   die();
}

if (isset($_POST['editar'])) {   

    if (!$usuarioSesion->tienePermiso("actualizarDiscipulos")) {
        $_SESSION['errores'][] = "No posee permiso para actualizar Discipulos.";
        redirigir("/AppwebMVC/Home/");
    }

    $id = $_POST['id'];
    $idSede = $_POST['idSede'];
    $nombre = $_POST['nombre'];
    $idLider = $_POST['idLider'];
    $detalles = $_POST['detalles'];
 
   $Territorio->editar_territorio($id, $idSede, $nombre, $idLider, $detalles);

   echo json_encode('Lo logramos!!');
   die();

}

if (isset($_POST['eliminar'])) {   

    if (!$usuarioSesion->tienePermiso("eliminarDiscipulos")) {
        $_SESSION['errores'][] = "No posee permiso para eliminar Discipulos.";
        redirigir("/AppwebMVC/Home/");
    }

    $id = $_POST['id'];

   $Territorio->eliminar_territorio($id);

   echo json_encode('Lo logramos!!');
   die();

}
   
renderView();
?>
