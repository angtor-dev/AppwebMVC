<?php
require_once "Models/Model.php";

class Moduloeid extends Model
{
    public int $id;
    public int $idEid;
    public int $nivel;
    private ?string $codigo;
    private string $nombre;


    public function registrarModuloEid($nombre, $idEid)
    {

        try {
            
            /** @var Eid **/
            $Eid = Eid::cargar($idEid);

            $query = "SELECT MAX(nivel) AS lastNivel FROM moduloeid WHERE idEid = :idEid AND estatus = '1'";
            $consultanivel = $this->db->pdo()->prepare($query);

            $consultanivel->bindValue(':idEid', $idEid);
            $consultanivel->execute();
            $datos = $consultanivel->fetch(PDO::FETCH_ASSOC);

            $nivel = '';
            $codigo = '';
            
        

            if ($datos['lastNivel'] == null) {
                $nivel = 1;
                $codigo = $Eid->getCodigo() . 'M' . $nivel;
            } else {
                $nivel = $datos['lastNivel'] + 1;
                $codigo = $Eid->getCodigo() . 'M' . $nivel;
            }

            $sql = "INSERT INTO moduloeid (idEid, nombre, nivel, codigo) VALUES (:idEid, :nombre, :nivel, :codigo)";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idEid', $idEid);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':nivel', $nivel);
            $stmt->bindValue(':codigo', $codigo);

            $stmt->execute();


            Bitacora::registrar("Registro de Modulo de Escuela de Impulso y desarrollo");

            http_response_code(200);
            echo json_encode(array('msj' => 'Modulo registrado exitosamente', 'status' => 200));
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



    public function listarModulos($idEid)
    {

        try {

            $sql = "SELECT moduloeid.*, COALESCE(COUNT(nivel.id), 0) AS niveles FROM moduloeid
            LEFT JOIN nivel ON moduloeid.id = nivel.idModuloEid AND nivel.estatus = '1'
             WHERE idEid = :idEid AND moduloeid.estatus = '1' GROUP BY moduloeid.id ORDER BY moduloeid.id ASC";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idEid', $idEid);

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

    

    public function editarModuloEid($id, $nombre)
    {

        try {
            $sql = "UPDATE moduloeid SET nombre = :nombre WHERE id = :id";

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

    public function eliminarModuloeid($id, $idEid)
    {

        try {

            $query = "SELECT MAX(nivel) AS lastNivel FROM moduloeid WHERE idEid = :idEid AND estatus = '1'";
            $consultanivel = $this->db->pdo()->prepare($query);

            $consultanivel->bindValue(':idEid', $idEid);
            $consultanivel->execute();
            $datos = $consultanivel->fetch(PDO::FETCH_ASSOC);

            $query2 = "SELECT id FROM moduloeid WHERE nivel = :nivel AND idEid = :idEid AND estatus = '1'";
            $consulta = $this->db->pdo()->prepare($query2);

            $consulta->bindValue(':idEid', $idEid);
            $consulta->bindValue(':nivel', $datos['lastNivel']);
            $consulta->execute();
            $dato = $consulta->fetch(PDO::FETCH_ASSOC);
            
           
            if ($dato['id'] == $id) {
$sql = "UPDATE moduloeid SET estatus = '0' WHERE moduloeid.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('msj' => 'Modulo eliminado correctamente', 'status' => 200));
            die();
                

            } else {
                throw new Exception("Este modulo no se puede eliminar porque existe uno de mayor nivel, esto afectaria la integridad de los datos", 422);
            }    
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function getIdEid(): ?string
    {
        return $this->idEid;
    }

    public function getNivel(): ?string
    {
        return $this->nivel;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

}