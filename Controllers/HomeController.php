<?php
require_once "Controllers/Controller.php";

class HomeController extends Controller
{
    function __construct()
    {
        // Esto cambiaria la plantilla para todas las vistas de este controlador
        // $this->layout = "otraPlantilla";
    }

    public function Index()
    {
        $this->renderView(); // Muestra la vista Home/Index.php
    }

    public function Sumar()
    {
        $result = 1 + 2;
        $this->viewData['result'] = $result;

        $this->renderView("Index");
    }
}
?>