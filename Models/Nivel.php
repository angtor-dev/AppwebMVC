<?php
require_once "Models/Model.php";

class Nivel extends Model
{
    public int $id;
    public int $idModuloEid;
    public int $nivel;
    private ?string $codigo;
    private string $nombre;


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
                throw new Exception("Este Nivel no se puede eliminar porque existe uno de mayor, esto afectaria la integridad de los datos", 422);
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

    public function getIdModuloEid(): ?string
    {
        return $this->idModuloEid;
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
