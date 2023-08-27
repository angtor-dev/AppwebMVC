<?php
require_once "Models/Model.php";
require_once "Models/Rol.php";

class Usuario extends Model
{
    public int $id;
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

    /** @var ?array<Rol> */
    public ?array $roles;

    public function __construct()
    {
        parent::__construct();
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

        $query = "SELECT * FROM usuario WHERE cedula = :cedula";
        
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

            foreach ($usuario as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            // Carga roles del usuario
            if (!empty($this->id)) {
                $this->roles = Rol::cargarMultiplesRelaciones($this->id, get_class($this), "UsuarioRol");
            }

            return true;
        } catch (\Throwable $th) {
            http_response_code(500);
            throw $th;
        }
        return true;
    }

    public function registrar() : void
    {
        $sql = "INSERT INTO usuario(cedula, correo, clave, nombre, apellido, telefono, direccion, estadoCivil, fechaNacimiento)
            VALUES(:cedula, :correo, :clave, :nombre, :apellido, :telefono, :direccion, :estadoCivil, :fechaNacimiento)";
        
        try {
            $stmt = $this->prepare($sql);
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

            return;
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar el usuario.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        if (empty($this->cedula) || empty($this->nombre) || empty($this->apellido)
            || empty($this->estadoCivil) || empty($this->fechaNacimiento)
            || empty($this->telefono) || empty($this->direccion))
        {
            $_SESSION['errores'][] = "Algunos campos obligatorios estan vacios";
            return false;
        }

        if (empty($this->id) && empty($this->clave)) {
            $_SESSION['errores'][] = "Debe especificar una contraseña";
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
            if (property_exists($rol->permisos, $nombrePermiso) && $rol->permisos->$nombrePermiso) {
                return true;
            }
        }
        return false;
    }
}
?>