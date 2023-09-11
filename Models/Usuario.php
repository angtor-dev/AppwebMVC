<?php
require_once "Models/Model.php";
require_once "Models/Rol.php";
require_once "Models/Sede.php";

class Usuario extends Model
{
    public int $id;
    public int $idSede;
    public ?int $idCelulaFamiliar;
    public ?int $idCelulaCrecimiento;
    public ?int $idConsolidador;
    public string $cedula;
    public ?string $correo;
    public string $clave;
    public string $nombre;
    public string $apellido;
    public string $telefono;
    public string $direccion;
    public string $estadoCivil;
    public string $fechaNacimiento;
    public ?string $fechaConversion;
    public ?string $motivo;
    public int $estatus;

    /** @var ?array<Rol> */
    public ?array $roles;
    public Sede $sede;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSede)) {
            $this->sede = Sede::cargar($this->idSede);
        }
        if (!empty($this->id)) {
            $this->roles = Rol::cargarMultiplesRelaciones($this->id, get_class($this), "UsuarioRol");
        }
    }

    public function getEdad() : int
    {
        if (empty($this->fechaNacimiento)) {
            return 0;
        }
        $nacimiento = new DateTime($this->fechaNacimiento);
        $edad = (new DateTime())->diff($nacimiento)->y;
        return $edad;
    }

    public function login(string $cedula, string $clave) : bool
    {
        if (empty($cedula) || empty($clave)) {
            return false;
        }

        $query = "SELECT * FROM usuario WHERE cedula = :cedula LIMIT 1";
        
        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('cedula', $cedula);

            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            }

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($clave, $usuario['clave'])) {
                return false;
            }

            // Almacena usuario en sesion
            session_start();
            $_SESSION['usuario'] = Usuario::cargar($usuario['id']);

            return true;
        } catch (\Throwable $th) {
            http_response_code(500);
            throw $th;
        }
        return true;
    }

    public function registrar() : void
    {
        $sql = "INSERT INTO usuario(idSede, cedula, correo, clave, nombre, apellido, telefono, direccion, estadoCivil, fechaNacimiento)
            VALUES(:idSede, :cedula, :correo, :clave, :nombre, :apellido, :telefono, :direccion, :estadoCivil, :fechaNacimiento)";

        try {
            $this->db->pdo()->beginTransaction();

            // Registra al usuario
            $stmt = $this->prepare($sql);
            $stmt->bindValue('idSede', $this->idSede);
            $stmt->bindValue('cedula', $this->cedula);
            $stmt->bindValue('correo', $this->correo);
            $stmt->bindValue('clave', $this->clave);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('apellido', $this->apellido);
            $stmt->bindValue('telefono', $this->telefono);
            $stmt->bindValue('direccion', $this->direccion);
            $stmt->bindValue('estadoCivil', $this->estadoCivil);
            $stmt->bindValue('fechaNacimiento', $this->fechaNacimiento);

            $stmt->execute();

            // Registra los roles del usuario
            $idUsuario = $this->db->pdo()->lastInsertId();
            $idRol = null;

            $sql = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam('idUsuario', $idUsuario);
            $stmt->bindParam('idRol', $idRol);

            foreach ($this->roles as $rol) {
                $idRol = $rol->id;
                $stmt->execute();
            }

            // Guarda los cambios
            $this->db->pdo()->commit();

            return;
        } catch (\Throwable $th) {
            // Revierte los cambios en la bd
            if ($this->db->pdo()->inTransaction()) {
                $this->db->pdo()->rollBack();
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar el usuario.";
            throw $th;
        }
    }

    public function actualizar() : void
    {
        $sql = "UPDATE usuario SET idSede = :idSede, cedula = :cedula, correo = :correo,
            nombre = :nombre, apellido = :apellido, telefono = :telefono, direccion = :direccion,
            estadoCivil = :estadoCivil, fechaNacimiento = :fechaNacimiento WHERE id = :id";

        try {
            $this->db->pdo()->beginTransaction();
            
            // Actualiza el usuario
            $stmt = $this->prepare($sql);
            $stmt->bindValue('idSede', $this->idSede);
            $stmt->bindValue('cedula', $this->cedula);
            $stmt->bindValue('correo', $this->correo);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('apellido', $this->apellido);
            $stmt->bindValue('telefono', $this->telefono);
            $stmt->bindValue('direccion', $this->direccion);
            $stmt->bindValue('estadoCivil', $this->estadoCivil);
            $stmt->bindValue('fechaNacimiento', $this->fechaNacimiento);
            $stmt->bindValue('id', $this->id);

            $stmt->execute();

            // Actualiza los roles del usuario
            $this->query("DELETE FROM usuariorol WHERE idUsuario = $this->id");
            $idRol = null;

            $sql = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam('idUsuario', $this->id);
            $stmt->bindParam('idRol', $idRol);

            foreach ($this->roles as $rol) {
                $idRol = $rol->id;
                $stmt->execute();
            }

            // Guarda los cambios
            $this->db->pdo()->commit();
        } catch (\Throwable $th) {
            // Revierte los cambios en la bd
            if ($this->db->pdo()->inTransaction()) {
                $this->db->pdo()->rollBack();
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el usuario.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        if (empty($this->cedula) || empty($this->nombre) || empty($this->apellido)
            || empty($this->estadoCivil) || empty($this->fechaNacimiento)
            || empty($this->telefono) || empty($this->direccion))
        {
            $_SESSION['errores'][] = "Algunos campos obligatorios estan vacios.";
            return false;
        }

        if (empty($this->roles)) {
            $_SESSION['errores'][] = "Se debe seleccionar al menos un rol.";
            return false;
        }

        if (empty($this->id) && empty($this->clave)) {
            $_SESSION['errores'][] = "Debe especificar una contraseña.";
            return false;
        }

        return true;
    }

    /** Valida si el usuario tiene un rol especifico */
    public function tieneRol(string $nombreRol) : bool
    {
        if (empty($this->roles)) {
            return false;
        }

        foreach ($this->roles as $rol) {
            if (strcasecmp($rol->nombre, $nombreRol) == 0) {
                return true;
            }
        }
        return false;
    }

    /** Valida si el usuario tiene un permiso especifico */
    public function tienePermiso(string $nombrePermiso) : bool
    {
        if (empty($this->roles)) {
            return false;
        }

        foreach ($this->roles as $rol) {
            if ($rol->permisos->$nombrePermiso) {
                return true;
            }
        }
        return false;
    }
}
?>