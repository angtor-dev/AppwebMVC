<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["agenda.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
$title = "Agenda"
?>

<style>

  #calendar {
    font-size: 14px;
    max-width: 1100px;
    margin: 0 auto;
  }

.fc-daygrid-day-number {
  color: black;
}

.fc-col-header-cell-cushion {
    color: black;
}

</style>  


<div id="calendar"></div>



   


