<?php
require_once "Models/Model.php";
require_once "Models/Eid.php";
require_once "Models/Grupo.php";
require_once "Models/Moduloeid.php";

class Nivel extends Model
{
    public int $id;
    private int $idModuloEid;
    private string $nombre;
    private int $nivel;
    private string $codigo;
    private int $estatus;




    public function registrarNivel($nombre, $idModuloEid)
    {

        try {

            /** @var Moduloeid **/
            $Moduloeid = Moduloeid::cargar($idModuloEid);

            $query = "SELECT MAX(nivel) AS lastNivel FROM nivel WHERE idModuloEid = :idModuloEid AND estatus = '1'";
            $consultanivel = $this->db->pdo()->prepare($query);

            $consultanivel->bindValue(':idModuloEid', $idModuloEid);
            $consultanivel->execute();
            $datos = $consultanivel->fetch(PDO::FETCH_ASSOC);

            $nivel = '';
            $codigo = '';



            if ($datos['lastNivel'] == null) {
                $nivel = 1;
                $codigo = $Moduloeid->getCodigo() . 'N' . $nivel;
            } else {
                $nivel = $datos['lastNivel'] + 1;
                $codigo = $Moduloeid->getCodigo() . 'N' . $nivel;
            }

            $sql = "INSERT INTO nivel (idModuloEid, nombre, nivel, codigo) VALUES (:idModuloEid, :nombre, :nivel, :codigo)";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idModuloEid', $idModuloEid);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':nivel', $nivel);
            $stmt->bindValue(':codigo', $codigo);

            $stmt->execute();


            Bitacora::registrar("Registro de Nivel en el modulo " . $Moduloeid->getCodigo() . ".");

            http_response_code(200);
            echo json_encode(array('msj' => 'Nivel registrado exitosamente', 'status' => 200));
            die();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );

            http_response_code(422);
            echo json_encode($error_data);
            die();
        }

    }



    public function listarNiveles($idModuloEid)
    {

        try {

            $sql = "SELECT * FROM nivel WHERE idModuloEid = :idModuloEid AND estatus = '1'";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idModuloEid', $idModuloEid);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }



    public function editarNivel($id, $nombre)
    {

        try {
            $sql = "UPDATE nivel SET nombre = :nombre WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nombre', $nombre);

            $stmt->execute();

            http_response_code(200);
            echo json_encode(array('msj' => 'Actualizado correctamente'));
            die();
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }

    public function eliminarNivel($id, $idModuloEid)
    {

        try {

            $this->validarEliminarNivel($id);

            $query = "SELECT MAX(nivel) AS lastNivel FROM nivel WHERE idModuloEid = :idModuloEid AND estatus = '1'";
            $consultanivel = $this->db->pdo()->prepare($query);

            $consultanivel->bindValue(':idModuloEid', $idModuloEid);
            $consultanivel->execute();
            $datos = $consultanivel->fetch(PDO::FETCH_ASSOC);

            $query2 = "SELECT id FROM nivel WHERE nivel = :nivel AND idModuloEid = :idModuloEid AND estatus = '1'";
            $consulta = $this->db->pdo()->prepare($query2);

            $consulta->bindValue(':idModuloEid', $idModuloEid);
            $consulta->bindValue(':nivel', $datos['lastNivel']);
            $consulta->execute();
            $dato = $consulta->fetch(PDO::FETCH_ASSOC);


            if ($dato['id'] == $id) {
                $sql = "UPDATE nivel SET estatus = '0' WHERE nivel.id = :id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(":id", $id);

                $stmt->execute();
                http_response_code(200);
                echo json_encode(array('msj' => 'Nivel eliminado correctamente', 'status' => 200));
                die();


            } else {
                throw new Exception("Este Nivel no se puede eliminar porque existe uno de mayor rango, esto afectaria la integridad de los datos", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    private function validarEliminarNivel($idNivel){

        try {

            $query = "SELECT * FROM grupo WHERE estatus = '1' AND idNivel = :idNivel";

                $stmt = $this->db->pdo()->prepare($query);

                $stmt->bindValue(':idNivel', $idNivel);

                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($stmt->rowCount() > 0) {
                  
                   throw new Exception("Este Modulo no se puede eliminar porque ya existen Niveles registrados", 422);
               
                }
                       
   } catch (Exception $e) {
       http_response_code($e->getCode());
       echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
       die();
   }}


    public static function cargarpornivelymodulo($idModuloEid, $nivelAnterior): null|Nivel
    {
        $db = Database::getInstance();

        $sql = "SELECT * FROM nivel
        WHERE idModuloEid = $idModuloEid AND nivel = $nivelAnterior AND estatus = '1'";

        $stmt = $db->pdo()->query($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, "Nivel");

        $nivel = $stmt->fetch();

        if ($nivel == false){
            return null;
        }

        return $nivel;
    }

    public static function cargarultimonivelEid(int $idEid): ?Nivel
    {
        $db = Database::getInstance();

        $sql = "SELECT * FROM nivel WHERE idModuloEid = (SELECT moduloeid.id FROM moduloeid WHERE idEid = $idEid AND estatus = '1' 
        AND nivel = (SELECT MAX(nivel) FROM moduloeid WHERE idEid = $idEid AND estatus = '1')) AND estatus = '1' 
        AND nivel = (SELECT MAX(nivel) FROM nivel WHERE estatus = '1' AND idModuloEid = (SELECT moduloeid.id FROM moduloeid WHERE idEid = $idEid AND estatus = '1' 
        AND nivel = (SELECT MAX(nivel) FROM moduloeid WHERE idEid = $idEid AND estatus = '1')))";

        $stmt = $db->pdo()->query($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, "Nivel");

        $nivel = $stmt->fetch();

        if ($nivel == false) {
            return null;
        }

        return $nivel;

    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getIdModuloEid()
    {
        return $this->idModuloEid;
    }

    public function getNivel()
    {
        return $this->nivel;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

}
