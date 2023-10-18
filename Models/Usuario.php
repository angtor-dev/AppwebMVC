<?php
require_once "Models/Model.php";
require_once "Models/Rol.php";
require_once "Models/Sede.php";
require_once "Models/Notificacion.php";
require_once "Models/Nota.php";

class Usuario extends Model
{
    public int $id;
    public int $idSede;
    private ?int $idCelulaFamiliar;
    private ?int $idCelulaCrecimiento;
    private ?int $idConsolidador;
    private string $cedula;
    private ?string $correo;
    private string $clave;
    private string $nombre;
    private string $apellido;
    private string $telefono;
    private string $direccion;
    private string $estadoCivil;
    private string $fechaNacimiento;
    private ?string $fechaConversion;
    private ?string $motivo;
    private int $estatus;

    public Sede $sede;
    /** @var ?array<Rol> */
    public ?array $roles;
    /** @var ?array<Notificacion> */
    public ?array $notificaciones;
    /** @var array<Nota> */
    public array $notas;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSede)) {
            $this->sede = Sede::cargar($this->idSede);
        }
        if (!empty($this->id)) {
            $this->roles = Rol::cargarMultiplesRelaciones($this->id, get_class($this), "UsuarioRol");
        }
        if (!empty($this->id)) {
            $this->notificaciones = Notificacion::cargarRelaciones($this->id, get_class());
        }
        if (!empty($this->id)) {
            $this->notas = Nota::cargarRelaciones($this->id, "Estudiante");
        }
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

            $this->encriptarClave();

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
    
    private function encriptarClave() : void
    {
        $this->clave = password_hash($this->clave, PASSWORD_DEFAULT);
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

        if (empty($this->id) && !preg_match(REG_CLAVE, $this->clave)) {
            $_SESSION['errores'][] = "La clave debe poseer al menos una letra,"
                ." un número y 6 caracteres de longitud.";
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
            if (strcasecmp($rol->getNombre(), $nombreRol) == 0) {
                return true;
            }
        }
        return false;
    }

    /** 
     * Valida si el usuario tiene un permiso especifico en un modulo
     * 
     * @param string $modulo El modulo a consultar (en minuscula y plural).
     * @param string $permiso El permiso a validar. Los posibles valores son 
     * consultar, registrar, actualizar y eliminar.
     */
    public function tienePermiso(string $modulo, string $permiso) : bool
    {
        if (empty($this->roles)) {
            return false;
        }
        $permiso = "get".ucfirst($permiso);

        foreach ($this->roles as $rol) {
            foreach ($rol->permisos as $p) {
                if ($p->modulo->getNombre() == $modulo && $p->$permiso()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function cargarPorCedula(string|int $cedula) : null|Usuario
    {
        $bd = Database::getInstance();
        $query = "SELECT * FROM usuario WHERE cedula = :cedula AND estatus = 1 LIMIT 1";

        $stmt = $bd->pdo()->prepare($query);
        $stmt->bindValue("cedula", $cedula);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Usuario");

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
    }

    /** Retorna un arreglo con los usuarios que tengan alguno de los roles indicados */
    public static function listarPorRoles(string ...$roles) : array
    {
        /** @var Usuario[] */
        $usuarios = Usuario::listar(1);

        foreach ($usuarios as $key => $usuario) {
            foreach ($usuario->roles as $rol) {
                if (!in_array($rol->getNombre(), $roles)) {
                    unset($usuarios[$key]);
                    break;
                }
            }
        }

        return $usuarios;
    }

    /**
     * mapea las propiedades del usuario con las propiedades de un Discipulo
     *
     * @param Discipulo $discipulo El discipulo desde donde se mapeara el usuario
     **/
    public function fromDiscipulo(Discipulo $discipulo) : void
    {
        /** @var Usuario $usuarioSesion */
        $usuarioSesion = $_SESSION['usuario'];
        $idSede = $usuarioSesion->idSede;

        $this->idSede = $idSede;
        $this->idConsolidador = $discipulo->getIdConsolidador();
        $this->cedula = $discipulo->getCedula();
        $this->correo = null;
        $this->clave = $this->cedula;
        $this->nombre = $discipulo->getNombre();
        $this->apellido = $discipulo->getApellido();
        $this->telefono = $discipulo->getTelefono();
        $this->direccion = $discipulo->getDireccion();
        $this->estadoCivil = $discipulo->getEstadoCivil();
        $this->fechaNacimiento = $discipulo->getFechaNacimiento();
        $this->fechaConversion = $discipulo->getFechaConvercion();
        $this->motivo = $discipulo->getMotivo();
        $this->roles[] = Rol::tryFromNombre("Estudiante");
    }

    /** Mapea los valores de un formulario post a las propiedades del objeto */
    public function mapFromPost() : bool
    {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = trim($value);
                }
            }
            return true;
        }
        return false;
    }

    // Getters
    public function getGrupo() : ?Grupo
    {
        $sql = "SELECT grupo.* FROM matricula, grupo
            WHERE idEstudiante = $this->id AND matricula.idGrupo = grupo.id AND grupo.estado = 0 LIMIT 1";
        
        $stmt = $this->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Grupo");

        $grupo = $stmt->fetch();

        if ($grupo == false) {
            return null;
        }

        return $grupo;
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

    public function getNombreCompleto() : string {
        return $this->nombre." ".$this->apellido;
    }
    public function getNombre() : string {
        return $this->nombre ?? "";
    }
    public function getApellido() : string {
        return $this->apellido ?? "";
    }
    public function getCorreo() : ?string {
        return $this->correo ?? null;
    }
    public function getCedula() : string {
        return $this->cedula ?? "";
    }
    public function getTelefono() : string {
        return $this->telefono ?? "";
    }
    public function getDireccion() : string {
        return $this->direccion ?? "";
    }
    public function getEstadoCivil() : string {
        return $this->estadoCivil ?? "";
    }
    public function getFechaNacimiento() : string {
        return $this->fechaNacimiento ?? "";
    }
}
?>