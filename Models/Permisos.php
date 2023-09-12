<?php
require_once "Models/Model.php";

class Permisos extends Model
{
    public int $id;
    public bool $consultarUsuarios = false;
    public bool $registrarUsuarios = false;
    public bool $actualizarUsuarios = false;
    public bool $eliminarUsuarios = false;
    public bool $registrarSede = false;
    public bool $listarSede = false;
    public bool $actualizarSede = false;
    public bool $eliminarSede = false;
    public bool $registrarTerritorio = false;
    public bool $listarTerritorio = false;
    public bool $actualizarTerritorio = false;
    public bool $eliminarTerritorio = false;
    public bool $registrarCelulaConsolidacion = false;
    public bool $listarCelulaConsolidacion = false;
    public bool $actualizarCelulaConsolidacion = false;
    public bool $eliminarCelulaConsolidacion = false;
    public bool $registrarCelulaCrecimiento = false;
    public bool $listarCelulaCrecimiento = false;
    public bool $actualizarCelulaCrecimiento = false;
    public bool $eliminarCelulaCrecimiento = false;
    public bool $registrarCelulaFamiliar = false;
    public bool $listarCelulaFamiliar = false;
    public bool $actualizarCelulaFamiliar = false;
    public bool $eliminarCelulaFamiliar = false;
    public bool $registrarDiscipulos = false;
    public bool $listarDiscipulos = false;
    public bool $actualizarDiscipulos = false;
    public bool $eliminarDiscipulos = false;
    public bool $consultarRoles = false;
    public bool $registrarRoles = false;
    public bool $actualizarRoles = false;
    public bool $eliminarRoles = false;
    public bool $gestionarPermisos = false;
    public bool $consultarBitacora = false;
    public bool $consultarNivelesCrecimiento = false;
    public bool $registrarNivelesCrecimiento = false;
    public bool $actualizarNivelesCrecimiento = false;
    public bool $eliminarNivelesCrecimiento = false;
    public bool $consultarNotas = false;
    public bool $gestionarNotas = false;
    public bool $consultarEventos = false;
    public bool $registrarEventos = false;
    public bool $actualizarEventos = false;
    public bool $eliminarEventos = false;
    public bool $cambiarSede = false;

    public Rol $rol;

    public function actualizar() : void
    {
        $query = "UPDATE permisos SET
            consultarUsuarios = :consultarUsuarios,
            registrarUsuarios = :registrarUsuarios,
            actualizarUsuarios = :actualizarUsuarios,
            eliminarUsuarios = :eliminarUsuarios,
            registrarSede = :registrarSede,
            listarSede = :listarSede,
            actualizarSede = :actualizarSede,
            eliminarSede = :eliminarSede,
            registrarTerritorio = :registrarTerritorio,
            listarTerritorio = :listarTerritorio,
            actualizarTerritorio = :actualizarTerritorio,
            eliminarTerritorio = :eliminarTerritorio,
            registrarCelulaConsolidacion = :registrarCelulaConsolidacion,
            listarCelulaConsolidacion = :listarCelulaConsolidacion,
            actualizarCelulaConsolidacion = :actualizarCelulaConsolidacion,
            eliminarCelulaConsolidacion = :eliminarCelulaConsolidacion,
            registrarCelulaCrecimiento = :registrarCelulaCrecimiento,
            listarCelulaCrecimiento = :listarCelulaCrecimiento,
            actualizarCelulaCrecimiento = :actualizarCelulaCrecimiento,
            eliminarCelulaCrecimiento = :eliminarCelulaCrecimiento,
            registrarCelulaFamiliar = :registrarCelulaFamiliar,
            listarCelulaFamiliar = :listarCelulaFamiliar,
            actualizarCelulaFamiliar = :actualizarCelulaFamiliar,
            eliminarCelulaFamiliar = :eliminarCelulaFamiliar,
            registrarDiscipulos = :registrarDiscipulos,
            listarDiscipulos = :listarDiscipulos,
            actualizarDiscipulos = :actualizarDiscipulos,
            eliminarDiscipulos = :eliminarDiscipulos,
            consultarRoles = :consultarRoles,
            registrarRoles = :registrarRoles,
            actualizarRoles = :actualizarRoles,
            eliminarRoles = :eliminarRoles,
            gestionarPermisos = :gestionarPermisos,
            consultarBitacora = :consultarBitacora,
            consultarNivelesCrecimiento = :consultarNivelesCrecimiento,
            registrarNivelesCrecimiento = :registrarNivelesCrecimiento,
            actualizarNivelesCrecimiento = :actualizarNivelesCrecimiento,
            eliminarNivelesCrecimiento = :eliminarNivelesCrecimiento,
            consultarNotas = :consultarNotas,
            gestionarNotas = :gestionarNotas,
            consultarEventos = :consultarEventos,
            registrarEventos = :registrarEventos,
            actualizarEventos = :actualizarEventos,
            eliminarEventos = :eliminarEventos,
            cambiarSede = :cambiarSede
            WHERE id = $this->id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('consultarUsuarios', $this->consultarUsuarios);
            $stmt->bindValue('registrarUsuarios', $this->registrarUsuarios);
            $stmt->bindValue('actualizarUsuarios', $this->actualizarUsuarios);
            $stmt->bindValue('eliminarUsuarios', $this->eliminarUsuarios);
            $stmt->bindValue('registrarSede', $this->registrarSede);
            $stmt->bindValue('listarSede', $this->listarSede);
            $stmt->bindValue('actualizarSede', $this->actualizarSede);
            $stmt->bindValue('eliminarSede', $this->eliminarSede);
            $stmt->bindValue('registrarTerritorio', $this->registrarTerritorio);
            $stmt->bindValue('listarTerritorio', $this->listarTerritorio);
            $stmt->bindValue('actualizarTerritorio', $this->actualizarTerritorio);
            $stmt->bindValue('eliminarTerritorio', $this->eliminarTerritorio);
            $stmt->bindValue('registrarCelulaConsolidacion', $this->registrarCelulaConsolidacion);
            $stmt->bindValue('listarCelulaConsolidacion', $this->listarCelulaConsolidacion);
            $stmt->bindValue('actualizarCelulaConsolidacion', $this->actualizarCelulaConsolidacion);
            $stmt->bindValue('eliminarCelulaConsolidacion', $this->eliminarCelulaConsolidacion);
            $stmt->bindValue('registrarCelulaCrecimiento', $this->registrarCelulaCrecimiento);
            $stmt->bindValue('listarCelulaCrecimiento', $this->listarCelulaCrecimiento);
            $stmt->bindValue('actualizarCelulaCrecimiento', $this->actualizarCelulaCrecimiento);
            $stmt->bindValue('eliminarCelulaCrecimiento', $this->eliminarCelulaCrecimiento);
            $stmt->bindValue('registrarCelulaFamiliar', $this->registrarCelulaFamiliar);
            $stmt->bindValue('listarCelulaFamiliar', $this->listarCelulaFamiliar);
            $stmt->bindValue('actualizarCelulaFamiliar', $this->actualizarCelulaFamiliar);
            $stmt->bindValue('eliminarCelulaFamiliar', $this->eliminarCelulaFamiliar);
            $stmt->bindValue('registrarDiscipulos', $this->registrarDiscipulos);
            $stmt->bindValue('listarDiscipulos', $this->listarDiscipulos);
            $stmt->bindValue('actualizarDiscipulos', $this->actualizarDiscipulos);
            $stmt->bindValue('eliminarDiscipulos', $this->eliminarDiscipulos);
            $stmt->bindValue('consultarRoles', $this->consultarRoles);
            $stmt->bindValue('registrarRoles', $this->registrarRoles);
            $stmt->bindValue('actualizarRoles', $this->actualizarRoles);
            $stmt->bindValue('eliminarRoles', $this->eliminarRoles);
            $stmt->bindValue('gestionarPermisos', $this->gestionarPermisos);
            $stmt->bindValue('consultarBitacora', $this->consultarBitacora);
            $stmt->bindValue('consultarNivelesCrecimiento', $this->consultarNivelesCrecimiento);
            $stmt->bindValue('registrarNivelesCrecimiento', $this->registrarNivelesCrecimiento);
            $stmt->bindValue('actualizarNivelesCrecimiento', $this->actualizarNivelesCrecimiento);
            $stmt->bindValue('eliminarNivelesCrecimiento', $this->eliminarNivelesCrecimiento);
            $stmt->bindValue('consultarNotas', $this->consultarNotas);
            $stmt->bindValue('gestionarNotas', $this->gestionarNotas);
            $stmt->bindValue('consultarEventos', $this->consultarEventos);
            $stmt->bindValue('registrarEventos', $this->registrarEventos);
            $stmt->bindValue('actualizarEventos', $this->actualizarEventos);
            $stmt->bindValue('eliminarEventos', $this->eliminarEventos);
            $stmt->bindValue('cambiarSede', $this->cambiarSede);

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