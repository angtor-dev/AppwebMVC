<?php
require_once "Models/Evento.php";

$evento_nombre            = ucwords($_REQUEST['evento']);
$f_inicio          = $_REQUEST['fecha_inicio'];
$fecha_inicio      = date('Y-m-d', strtotime($f_inicio)); 

$f_fin             = $_REQUEST['fecha_fin']; 
$seteando_f_final  = date('Y-m-d', strtotime($f_fin));  
$fecha_fin1        = strtotime($seteando_f_final."+ 1 days");
$fecha_fin         = date('Y-m-d', ($fecha_fin1));  
$color_evento      = $_REQUEST['color_evento'];

$evento = new Evento();
$evento->color = $color_evento;
$evento->titulo = $evento_nombre;
$evento->fechaInicio = $fecha_fin;
$evento->fechaFinal = $fecha_fin;
$evento->descripcion = "";

try {
    $evento->registrar();
} catch (\Throwable $th) {
    $_SESSION['errores'][] = "Ha ocurrido un error";
}

$_SESSION['exitos'][] = "Se ha registrado el evento correctamente";
Bitacora::registrar("Evento registrado");
redirigir("/AppwebMVC/Agenda");

?>