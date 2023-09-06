<?php
require_once "Models/Model.php";

class Permisos extends Model
{
    public int $id;
    public bool $consultarUsuarios = false;
    public bool $registrarUsuarios = false;
    public bool $actualizarUsuarios = false;
    public bool $eliminarUsuarios = false;
    public bool $consultarRoles = false;
    public bool $registrarRoles = false;
    public bool $actualizarRoles = false;
    public bool $eliminarRoles = false;
    public bool $gestionarPermisos = false;
    public bool $consultarBitacora = false;

    public Rol $rol;

    public function actualizar() : void
    {
        $query = "UPDATE permisos SET
            consultarUsuarios = :consultarUsuarios,
            registrarUsuarios = :registrarUsuarios,
            actualizarUsuarios = :actualizarUsuarios,
            eliminarUsuarios = :eliminarUsuarios,
            consultarRoles = :consultarRoles,
            registrarRoles = :registrarRoles,
            actualizarRoles = :actualizarRoles,
            eliminarRoles = :eliminarRoles,
            gestionarPermisos = :gestionarPermisos,
            consultarBitacora = :consultarBitacora
            WHERE id = $this->id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('consultarUsuarios', $this->consultarUsuarios);
            $stmt->bindValue('registrarUsuarios', $this->registrarUsuarios);
            $stmt->bindValue('actualizarUsuarios', $this->actualizarUsuarios);
            $stmt->bindValue('eliminarUsuarios', $this->eliminarUsuarios);
            $stmt->bindValue('consultarRoles', $this->consultarRoles);
            $stmt->bindValue('registrarRoles', $this->registrarRoles);
            $stmt->bindValue('actualizarRoles', $this->actualizarRoles);
            $stmt->bindValue('eliminarRoles', $this->eliminarRoles);
            $stmt->bindValue('gestionarPermisos', $this->gestionarPermisos);
            $stmt->bindValue('consultarBitacora', $this->consultarBitacora);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) echo $th->getMessage();
            die;
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar los permisos de rol.";
            throw $th;
        }
    }
}
?>