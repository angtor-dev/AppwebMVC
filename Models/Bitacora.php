<?php
require_once "Models/Model.php";

class Bitacora extends Model
{
    public int $id;
    public ?int $idUsuario;
    public string $registro;
    public string $ruta;
    public string $fecha;

    public ?Usuario $usuario = null;

    function __construct()
    {
        parent::__construct();
        if (!empty($this->idUsuario)) {
            $this->usuario = Usuario::cargar($this->idUsuario);
        }
    }

    /**
     * Registra una accion en la bitacora
     * 
     * @param string $registro accion/actividad a registrar
     */
    public static function registrar(string $registro) : void
    {
        global $requestUri;

        $db = Database::getInstance();
        $idUsuario = !empty($_SESSION['usuario']->id) ? $_SESSION['usuario']->id : "NULL";
        $ruta = $requestUri."/";

        $query = "INSERT INTO bitacora(idUsuario, registro, ruta)
            VALUES($idUsuario, '$registro', '$ruta')";

        $db->pdo()->query($query);
    }

    // Override para impedir eliminar
    public function eliminar(bool $eliminadoLogico = true) : void
    {
        $_SESSION['errores'][] = "No se puede eliminar un registro de la bitacora";
        return;
    }
}
?>