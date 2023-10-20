<?php
require_once "Models/Model.php";
require_once "Models/Subnivel.php";
require_once "Models/Matricula.php";
require_once "Models/Enums/EstadosGrupo.php";

class Grupo extends Model
{
    public int $id;
    private int $idSubnivel;
    private int $idProfesor;
    private string $nombre;
    private int $estado;
    private int $estatus;
    
    public Subnivel $subnivel;
    public Usuario $profesor;
    /** @var Usuario[] */
    public array $estudiantes;
    /** @var Matricula[] */
    public array $matriculas;
    // TODO: refactorizar para eliminar propiedad estudiantes

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSubnivel)) {
            $this->subnivel = Subnivel::cargar($this->idSubnivel);
        }
        if (!empty($this->idProfesor)) {
            $this->profesor = Usuario::cargar($this->idProfesor);
        }
        if (!empty($this->id)) {
            $this->estudiantes = $this->ListarEstudiantes();
        }
        if (!empty($this->id)) {
            $this->matriculas = Matricula::cargarRelaciones($this->id, get_class());
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO grupo(idSubnivel, idProfesor, nombre) VALUES(:idSubnivel, :idProfesor, :nombre)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idSubnivel", $this->idSubnivel);
            $stmt->bindValue("idProfesor", $this->idProfesor);
            $stmt->bindValue("nombre", $this->nombre);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al crear el grupo.";
            throw $th;
        }
    }

    public function actualizar() : void
    {
        $query = "UPDATE grupo SET idSubnivel = :idSubnivel, idProfesor = :idProfesor, nombre = :nombre, estado = :estado WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idSubnivel", $this->idSubnivel);
            $stmt->bindValue("idProfesor", $this->idProfesor);
            $stmt->bindValue("nombre", $this->nombre);
            $stmt->bindValue("estado", $this->estado);
            $stmt->bindValue("id", $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el grupo.";
            throw $th;
        }
    }

    private function ListarEstudiantes() : array
    {
        $query = "SELECT usuario.* FROM usuario, matricula
            WHERE usuario.id = matricula.idEstudiante AND matricula.idGrupo = :idGrupo";

            try {
                $stmt = $this->prepare($query);
                $stmt->bindValue("idGrupo", $this->id);

                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_CLASS, "Usuario");
            } catch (\Throwable $th) {
                if (DEVELOPER_MODE) {
                    die($th->getMessage());
                }
                $_SESSION['errores'][] = "Ha ocurrido un error al listar los estudiantes del grupo.";
                throw $th;
            }
    }

    /** @return Grupo[] */
    public function listarConEstudiante(int $idEstudiante) : array
    {
        $query = "SELECT grupo.* FROM grupo, matricula WHERE matricula.idEstudiante = $idEstudiante && matricula.idGrupo = grupo.id";

        try {
            $stmt = $this->query($query);

            return $stmt->fetchAll(PDO::FETCH_CLASS, $this);
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al listar los estudiantes del grupo.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        $errores = 0;
        if (empty($this->idSubnivel)) {
            $_SESSION['errores'][] = "Debe seleccionar un subnivel de crecimiento.";
            $errores++;
        }
        if (empty($this->idProfesor)) {
            $_SESSION['errores'][] = "Se debe especificar un profesor.";
            $errores++;
        }
        if (!is_null($this::fromNombre($this->nombre))) {
            $_SESSION['errores'][] = "Ya existe un grupo con el nombre ingresado.";
            $errores++;
        }
        if (!preg_match(REG_ALFANUMERICO, $this->nombre)) {
            $_SESSION['errores'][] = "El nombre solo puede contener caracteres alfanúmericos.";
            $errores++;
        }
        if (!isset($this->estado) || is_null(EstadosGrupo::tryFrom($this->estado))) {
            $_SESSION['errores'][] = "El estado ingresado es invalido.";
            $errores++;
        }

        if ($errores > 0) {
            return false;
        }
        return true;
    }

    private static function fromNombre(string $nombre) : ?Grupo
    {
        $db = Database::getInstance();

        $query = "SELECT * FROM grupo WHERE nombre = :nombre AND estatus = 1";

        try {
            $stmt = $db->pdo()->prepare($query);

            $stmt->bindValue("nombre", $nombre);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Grupo");

            if ($stmt->rowCount() == 0) {
                return null;
            }

            return $stmt->fetch();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al cargar el grupo desde un nombre";
            throw $th;
        }
    }

    public function tieneEstudiante(int $idEstudiante) : bool
    {
        foreach ($this->estudiantes as $estudiante) {
            if ($estudiante->id == $idEstudiante) {
                return true;
            }
        }
        return false;
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
    public function getNombre() : string {
        return $this->nombre ?? "";
    }
    public function getEstado() : ?int {
        return $this->estado ?? null;
    }

    // Setters
    public function setEstado(int $estado) : void {
        $this->estado = $estado;
    }

    // Estadisticas
    public static function estudiantesPorGrupo() : array
    {
        $db = Database::getInstance();
        $query = "SELECT grupo.nombre, COUNT(matricula.idGrupo) AS cantidad FROM matricula, grupo
            WHERE matricula.idGrupo = grupo.id AND grupo.estatus = 1 AND grupo.estado = 0
            GROUP BY matricula.idGrupo;";
        
        try {
            $stmt = $db->pdo()->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['erorres'][] = "Ah ocurrido un error al listar las estadisticas.";
            throw $th;
        }
    }
    
    public static function gruposPorSede() : array
    {
        $db = Database::getInstance();
        $query = "SELECT sede.nombre, COUNT(sede.id) AS cantidad
            FROM sede, escuela, nivelcrecimiento, subnivel, grupo
            WHERE grupo.idSubnivel = subnivel.id AND subnivel.idNivelCrecimiento = nivelcrecimiento.id
                AND nivelcrecimiento.idEscuela = escuela.id AND escuela.idSede = sede.id
                AND grupo.estado = 0 AND grupo.estatus = 1
            GROUP BY sede.id;";
        
        try {
            $stmt = $db->pdo()->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                die($th->getMessage());
            }
            $_SESSION['erorres'][] = "Ah ocurrido un error al listar las estadisticas.";
            throw $th;
        }
    }
}
?>