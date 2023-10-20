<?php
require_once "Models/Model.php";
require_once "Models/Escuela.php";

class NivelCrecimiento extends Model
{
    public int $id;
    private int $idEscuela;
    private string $nombre;
    private int $nivel;
    private int $estatus;

    public Escuela $escuela;
    public array $subnivelesArray;

    private const NIVELES_INICIALES = [
        "Consolidacion" => ["Escuela para la consolidacion"],
        "Discipulado" => ["1. Crecimiento", "2. Desarrollo", "3. Madurez"],
        "Mentoria" => ["Escuela de mentoria"],
        "Paternidad" => ["Encuentro para hijos"]
    ];

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idEscuela)) {
            $this->escuela = Escuela::cargar($this->idEscuela);
        }
        if (!empty($this->id)) {
            $this->subnivelesArray = $this->getSubniveles();
        }
    }

    public static function crearIniciales(int $idEscuela) : void
    {
        $db = Database::getInstance();
        $queryNivel = "INSERT INTO nivelCrecimiento(idEscuela, nombre, nivel)
            VALUES(:idEscuela, :nombre, :nivel)";
        $querySubnivel = "INSERT INTO subnivel(idNivelCrecimiento, nombre, nivel)
            VALUES(:idNivelCrecimiento, :nombre, :nivel)";
        $i = 1;

        try {
            $db->pdo()->beginTransaction();
            
            $stmtNivel = $db->pdo()->prepare($queryNivel);
            $stmtSubnivel = $db->pdo()->prepare($querySubnivel);

            foreach (self::NIVELES_INICIALES as $nivel => $subniveles) {
                $stmtNivel->bindParam("idEscuela", $idEscuela);
                $stmtNivel->bindParam("nombre", $nivel);
                $stmtNivel->bindParam("nivel", $i);

                $stmtNivel->execute();

                $idNivel = $db->pdo()->lastInsertId();

                foreach ($subniveles as $key => $subnivel) {
                    $j = $key + 1;

                    $stmtSubnivel->bindParam("idNivelCrecimiento", $idNivel);
                    $stmtSubnivel->bindParam("nombre", $subnivel);
                    $stmtSubnivel->bindParam("nivel", $j);

                    $stmtSubnivel->execute();
                }

                $i++;
            }

            $db->pdo()->commit();
        } catch (\Throwable $th) {
            if ($db->pdo()->inTransaction()) {
                $db->pdo()->rollBack();
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al crear los niveles iniciales";
            throw $th;
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO nivelcrecimiento(idEscuela, nombre, nivel) VALUES(:idEscuela, :nombre, :nivel)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idEscuela", $this->idEscuela);
            $stmt->bindValue("nombre", $this->nombre);
            $stmt->bindValue("nivel", $this->nivel);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al crear el nivel.";
            throw $th;
        }
    }

    public function actualizar() : void
    {
        $query = "UPDATE nivelcrecimiento SET nombre = :nombre, nivel = :nivel WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("nombre", $this->nombre);
            $stmt->bindValue("nivel", $this->nivel);
            $stmt->bindValue("id", $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                echo $th->getMessage();
                die;
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el nivel.";
            throw $th;
        }
    }

    public function esValido() : bool
    {
        $errores = 0;

        if (empty($this->nombre)) {
            $_SESSION['errores'][] = "Se debe especificar un nombre.";
            $errores++;
        }
        if (!preg_match(REG_ALFABETICO, $this->nombre)) {
            $_SESSION['errores'][] = "El nombre solo puede contener caracteres alfabeticos.";
            $errores++;
        }
        if (empty($this->nivel)) {
            $_SESSION['errores'][] = "Se debe especificar un nivel.";
            $errores++;
        }
        if (empty($this->id) && $this->query("SELECT id FROM nivelcrecimiento WHERE nivel = $this->nivel AND estatus = 1")->rowCount() > 0) {
            $_SESSION['errores'][] = "Ya existe otro Nivel de Crecimiento con el nivel de grado <b>$this->nivel</b>.";
            $errores++;
        }

        if ($errores > 0) {
            return false;
        }
        return true;
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
    public function getSubniveles() : array
    {
        $query = "SELECT nivelcrecimiento.id, GROUP_CONCAT(subnivel.id SEPARATOR ',') AS 'ids',
            GROUP_CONCAT(subnivel.nombre SEPARATOR ',') AS 'nombres',
            GROUP_CONCAT(subnivel.nivel SEPARATOR ',') AS 'niveles'
            FROM nivelcrecimiento INNER JOIN subnivel ON nivelcrecimiento.id = subnivel.idNivelCrecimiento
            WHERE nivelcrecimiento.id = $this->id
            GROUP BY nivelcrecimiento.id
            LIMIT 1";
        
        $subniveles = $this->query($query)->fetch();
        if ($subniveles) {
            return $subniveles;
        }
        return ["id" => null, "ids" => null, "nombres" => null, "niveles" => null];
    }

    public function getNombre() : string {
        return $this->nombre;
    }
    public function getNivel() : int {
        return $this->nivel;
    }

    // Setters
    public function setIdEscuela(int $idEscuela) : void {
        $this->idEscuela = $idEscuela;
    }

    // Estadisticas
    public static function gruposPorNivel() : array
    {
        $db = Database::getInstance();
        $query = "SELECT nivelcrecimiento.nombre, COUNT(nivelcrecimiento.id) AS 'cantidad'
            FROM nivelcrecimiento, subnivel, grupo
            WHERE grupo.idSubnivel = subnivel.id && subnivel.idNivelCrecimiento = nivelcrecimiento.id && grupo.estado = 0 && grupo.estatus = 1
            GROUP BY nivelcrecimiento.id;";
        
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