<?php
require_once "Models/Model.php";
require_once "Models/Nivel.php";
require_once "Models/Grupo.php";
require_once "Models/Moduloeid.php";


class Eid extends Model
{
    public int $id;
    private string $codigo;
    private string $nombre;
    private int $estatus;


    public function registrarEid($nombre, $selectedEid, $selectedRolR, $selectedRolA)
    {

        try {

            $query = "SELECT MAX(id) AS eidNumero FROM eid";
            $consultaid = $this->db->pdo()->prepare($query);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            $id = '';
            $codigo = '';

            if ($datos['eidNumero'] == null) {
                $id = 1;
                $codigo = 'EID' . $id;
            } else {
                $id = $datos['eidNumero'] + 1;
                $codigo = 'EID' . $id;
            }

            $sql = "INSERT INTO eid (id, codigo, nombre) VALUES (:id, :codigo, :nombre)";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':codigo', $codigo);

            $stmt->execute();

            $consulta = "SELECT id FROM eid ORDER BY id DESC LIMIT 1";
            $stmt2 = $this->db->pdo()->prepare($consulta);
            $stmt2->execute();
            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
            $idLastEid = $resultado['id'];

            if (!empty($selectedEid)) {
                foreach ($selectedEid as $values) {
                    $sql2 = "INSERT INTO controleid (idEid, idEidRequerido) VALUES (:idLastEid, :valor)";
                    $stmt3 = $this->db->pdo()->prepare($sql2);

                    $stmt3->bindValue(':idLastEid', $idLastEid);
                    $stmt3->bindValue(':valor', $values);

                    $stmt3->execute();
                }
            }

            if (!empty($selectedRolR)) {
                foreach ($selectedRolR as $values) {
                    $sql2 = "INSERT INTO controleid (idEid, idRolRequerido) VALUES (:idLastEid, :valor)";
                    $stmt3 = $this->db->pdo()->prepare($sql2);

                    $stmt3->bindValue(':idLastEid', $idLastEid);
                    $stmt3->bindValue(':valor', $values);

                    $stmt3->execute();
                }
            }

            if (!empty($selectedRolA)) {
                foreach ($selectedRolA as $values) {
                    $sql2 = "INSERT INTO controleid (idEid, idRolAdquirido) VALUES (:idLastEid, :valor)";
                    $stmt3 = $this->db->pdo()->prepare($sql2);

                    $stmt3->bindValue(':idLastEid', $idLastEid);
                    $stmt3->bindValue(':valor', $values);

                    $stmt3->execute();
                }
            }

            Bitacora::registrar("Registro de Escuela de Impulso y desarrollo");

            http_response_code(200);
            echo json_encode(array('msj' => 'EID registrada exitosamente', 'status' => 200));
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



    public function listarEid()
    {

        try {

            $sql = "SELECT eid.id, eid.nombre, eid.codigo, 
            COALESCE(COUNT(moduloeid.id), 0) AS modulos FROM eid
            LEFT JOIN moduloeid ON eid.id = moduloeid.idEid AND moduloeid.estatus = '1'
             WHERE eid.estatus = '1' GROUP BY eid.id ORDER BY eid.id ASC";

            $stmt = $this->db->pdo()->prepare($sql);

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

    public function listarRolesEid()
    {

        try {

            $sql = "SELECT * FROM rol WHERE nombre NOT IN ('Estudiante','Superusuario','Administrador','Usuario')";



            $stmt = $this->db->pdo()->prepare($sql);

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

    public function editarEid($id, $nombre)
    {

        try {
            $sql = "UPDATE eid SET nombre = :nombre WHERE id = :id";

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

    public function eliminarEid($id)
    {

        try {

            $sql = "UPDATE eid SET estatus = '0' WHERE eid.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('msj' => 'Sede eliminada correctamente', 'status' => 200));
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

    public function listarEidV($id, $tipo)
    {

        try {
            if ($tipo == 'eid') {
                $sql = "SELECT eid.id, eid.nombre, eid.codigo FROM eid 
            INNER JOIN controleid ON eid.id = controleid.idEidRequerido AND controleid.idEid = :id";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();

                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }

            if ($tipo == 'rolR') {
                $sql = "SELECT rol.id, rol.nombre FROM rol 
                INNER JOIN controleid ON rol.id = controleid.idRolRequerido AND controleid.idEid = :id";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();

                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }
            if ($tipo == 'rolA') {
                $sql = "SELECT rol.id, rol.nombre FROM rol 
                    INNER JOIN controleid ON rol.id = controleid.idRolAdquirido AND controleid.idEid = :id";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();

                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }
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

    public function eliminarControlEid($idRequisito, $idEid, $tipo)
    {

        try {
            $column = '';

            if ($tipo == 'eid') {
                $column = 'idEidRequerido';
            }

            if ($tipo == 'rolR') {
                $column = 'idRolRequerido';
            }

            if ($tipo == 'rolA') {
                $column = 'idRolAdquirido';
            }
            $sql = "DELETE FROM controleid WHERE idEid = :idEid AND $column = :idRequisito";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":idEid", $idEid);
            $stmt->bindValue(":idRequisito", $idRequisito);


            $stmt->execute();

            Bitacora::registrar("Eliminacion de asistencia en reunion de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Asistencia eliminada correctamente'));
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

    public function listadoSV($id, $tipo)
    {


        try {

            $tabla = '';
            $column = '';


            if ($tipo == 'eid') {

                $tabla = 'eid';
                $column = 'EidRequerido';

            }

            if ($tipo == 'rolR') {

                $tabla = 'rol';
                $column = 'RolRequerido';

            }

            if ($tipo == 'rolA') {

                $tabla = 'rol';
                $column = 'RolAdquirido';

            }



            $sql = "SELECT t.nombre AS nombre$tipo, t.* FROM $tabla AS t
              WHERE t.id NOT IN (SELECT id$column FROM controleid WHERE idEid = :id AND id$column IS NOT NULL) AND t.estatus = '1'" .
                (($tabla == 'rol') ? " AND t.nombre NOT IN ('Estudiante','Superusuario','Administrador','Usuario')" : "");


            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
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


    public function registrarControlEid($id, $array, $tipo)
    {

        try {

            $column = '';

            if ($tipo == 'eid') {

                $column = 'EidRequerido';

            }

            if ($tipo == 'rolR') {

                $column = 'RolRequerido';

            }

            if ($tipo == 'rolA') {

                $column = 'RolAdquirido';

            }

            foreach ($array as $values) {
                $sql2 = "INSERT INTO controleid (idEid, id$column) VALUES (:idEid, :valor)";
                $stmt3 = $this->db->pdo()->prepare($sql2);

                $stmt3->bindValue(':idEid', $id);
                $stmt3->bindValue(':valor', $values);

                $stmt3->execute();
            }

            Bitacora::registrar("Registro de Control de EID");

            http_response_code(200);
            echo json_encode(array('msj' => 'Control de EID registrado exitosamente', 'status' => 200));
            die();

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

    public function getValidarRolesRequeridos($idEstudiante)
    {
        try {

            /** @var Usuario */
            $Estudiante = Usuario::cargar($idEstudiante);


            $sql = "SELECT rol.id, rol.nombre FROM rol 
            INNER JOIN controleid ON rol.id = controleid.idRolRequerido AND controleid.idEid = :id";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":id", $this->id);
            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        if ($stmt->rowCount() > 0) {

            foreach ($resultado as $rol) {

                if (empty($Estudiante->tieneRol($rol['nombre']))) {
                    // El estudiante no tiene el rol especificado
                    throw new Exception("El estudiante no posee los roles requeridos para cursar esta EID", 422);
                }

            }}
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }

    }


    public function getValidarEidsRequerido($idEstudiante)
    {
        try {

            $sql = "SELECT eid.id, eid.nombre, eid.codigo FROM eid 
            INNER JOIN controleid ON eid.id = controleid.idEidRequerido AND controleid.idEid = :id";
            $stmt4 = $this->db->pdo()->prepare($sql);
            $stmt4->bindValue(":id", $this->id);
            $stmt4->execute();

            $resultado = $stmt4->fetchAll(PDO::FETCH_ASSOC);  
          
            if ($stmt4->rowCount() > 0) {
                        
                foreach ($resultado as $eid) {

                        /** @var Nivel */
                        $ultimoNivel = Nivel::cargarultimonivelEid(($eid['id'])); 

                 $query = "SELECT * FROM grupo
                 INNER JOIN matricula 
                 ON matricula.idEstudiante = :idEstudiante AND matricula.idGrupo = grupo.id AND matricula.estado = '2' 
                 WHERE grupo.idNivel = :idNivel";
 
                     $stmt = $this->db->pdo()->prepare($query);
 
                     $stmt->bindValue(':idEstudiante', $idEstudiante);
                     $stmt->bindValue(':idNivel', $ultimoNivel->id);
 
                     $stmt->execute();
                     $stmt->fetch(PDO::FETCH_ASSOC);
 
                     if ($stmt->rowCount() == 0) {
                       
                        throw new Exception("El estudiante no ha aprobado las EID requeridas para poder Cursar esta EID", 422);
                    
                     }
                
                
            }}
                        
            
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
        

    }



    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

}
