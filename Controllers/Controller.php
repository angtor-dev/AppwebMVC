<?php
class Controller
{
    /** Arreglo que se usa para pasar información del controlador a la vista */
    protected array $viewData = [];

    /** La plantilla HTML donde se imprime la vista. puede asignarse en el
     * constructor para cambiar la plantilla de todas las acciones, o dentro
     * de una vista para cambiar la plantilla de la vista especifica.
     */
    protected string $layout = "_Layout";

    /** Cadena donde se almacena el buffer (salida) de la vista a imprimir */
    private string $viewOutput = "";

    /**
     * Imprime la vista indicada dentro de una plantilla. Si no se
     * especifica $viewName, se imprime la vista que coincida
     * con la accion que se esta ejecutando.
     * @param string $viewName El nombre de la vista a imprimir
     */
    protected function renderView(string $viewName = null)
    {
        $viewName = $viewName ?? debug_backtrace()[1]['function'] ?? "Index";
        $folderName = str_replace("Controller", "", get_class($this));
        $viewPath = "Views/".$folderName."/".$viewName.".php";
        $viewData = $this->viewData;
        
        if (!is_file($viewPath)) {
            echo "[Error] La vista <b>".$viewName."</b> no existe. "
            ."Valida que se encuetre en la ruta <b>".$viewPath."</b>";
            exit();
        }
        
        try {
            ob_start(array($this, 'saveViewBuffer'));
            require $viewPath;
            ob_end_clean();
        } catch (\Throwable $th) {
            ob_end_clean();
            throw $th;
        }

        if (empty($this->layout)) {
            $this->renderBody();
        } else {
            $layoutPath = "Views/_Shared/".$this->layout.".php";

            if (!is_file($layoutPath)) {
                echo "[Error] La platilla <b>".$this->layout."</b> no existe. "
                ."Valida que se encuetre en la ruta <b>".$layoutPath."</b>";
                exit();
            }
            require $layoutPath;
        }
    }

    /**
     * Imprime una vista parcial, las vistas parciales no requieren plantilla.
     * @param string $partialViewName El nombre de la vista parcial a imprimir
     */
    protected function renderPartialView($partialViewName)
    {
        if (empty($partialViewName)) {
            throw new \Exception("Se debe especificar el nombre de la vista parcial.");
        }
        
        $folderName = str_replace("Controller", "", get_class($this));

        if (is_file("Views/".$folderName."/".$partialViewName.".php")) {
            require "Views/".$folderName."/".$partialViewName.".php";
        } elseif (is_file("Views/_Shared/".$partialViewName.".php")) {
            require "Views/_Shared/".$partialViewName.".php";
        } else {
            throw new \Exception("No se encontro la vista parcial: ".$partialViewName);
        }
    }

    /** 
     * Almacena el buffer de la vista que sera impresa
     * @param string $outputBuffer Buffer a almacenar
     * @return string Cadena a imprimir luego de almacenar el buffer (Cadena vacia)
    */
    private function saveViewBuffer(string $outputBuffer)
    {
        $this->viewOutput .= $outputBuffer;
        return "";
    }

    /**
     * Imprime el buffer de la vista (Se usa para imprimir la vista en una plantilla)
     */
    private function renderBody()
    {
        echo $this->viewOutput;
    }
}
?>