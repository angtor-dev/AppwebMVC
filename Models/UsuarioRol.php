<?php
require_once "Models/Model.php";

class UsuarioRol extends Model
{
    public int $id;
    public int $idUsuario;
    public int $idRol;
}
?>