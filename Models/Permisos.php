<?php
require_once "Models/Model.php";

class Permisos extends Model
{
    public int $id;
    public bool $consulaUsuarios;
    public bool $registraUsuarios;
    public bool $actualizaUsuarios;
    public bool $eliminaUsuarios;
}
?>