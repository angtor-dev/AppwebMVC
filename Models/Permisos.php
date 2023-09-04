<?php
require_once "Models/Model.php";

class Permisos extends Model
{
    public int $id;
    public bool $consultaUsuarios = false;
    public bool $registraUsuarios = false;
    public bool $actualizaUsuarios = false;
    public bool $eliminaUsuarios = false;

    public Rol $rol;

    public function actualizar() : void
    {
        $query = "UPDATE permisos SET
            consultaUsuarios = :consultaUsuarios,
            registraUsuarios = :registraUsuarios,
            actualizaUsuarios = :actualizaUsuarios,
            eliminaUsuarios = :eliminaUsuarios
            WHERE id = $this->id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('consultaUsuarios', $this->consultaUsuarios);
            $stmt->bindValue('registraUsuarios', $this->registraUsuarios);
            $stmt->bindValue('actualizaUsuarios', $this->actualizaUsuarios);
            $stmt->bindValue('eliminaUsuarios', $this->eliminaUsuarios);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar los permisos de rol.";
            throw $th;
        }
    }
}
?>