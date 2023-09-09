<?php
require_once "Models/Model.php";

class NivelCrecimiento extends Model
{
    public int $id;
    public int $idEscuela;
    public string $nombre;
    public int $nivel;
    public int $estatus;

    public Escuela $escuela;

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
}
?>