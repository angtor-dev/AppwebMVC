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
$evento->setColor($color_evento);
$evento->setTitulo($evento_nombre);
$evento->setFechaInicio($fecha_fin);
$evento->setFechaFinal($fecha_fin);
$evento->setDescripcion("");

try {
    $evento->registrar();
} catch (\Throwable $th) {
    if (DEVELOPER_MODE) {
        die($th->getMessage());
    }
    $_SESSION['errores'][] = "Ha ocurrido un error";
}

$_SESSION['exitos'][] = "Se ha registrado el evento correctamente";
Bitacora::registrar("Evento registrado");
redirigir("/AppwebMVC/Agenda");

?>