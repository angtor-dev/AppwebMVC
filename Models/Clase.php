<?php
require_once "Models/Model.php";

class Clase extends Model
{
    public int $id;
    public int $idGrupo;
    public string $titulo;
    private string $Objetivo;
    private float $ponderacion;


    public function registrarClase($idGrupo, $titulo, $objetivo, $ponderacion)
    {

        try {
            $disponible1 = $this->validarPonderacion($idGrupo, $ponderacion, $id = '');

            $sql = "INSERT INTO clase (idGrupo, titulo, objetivo, ponderacion, fecha) VALUES (:idGrupo, :titulo, :objetivo, :ponderacion, CURDATE())";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idGrupo', $idGrupo);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':objetivo', $objetivo);
            $stmt->bindValue(':ponderacion', $ponderacion);

            $stmt->execute();

            $idClase = $this->db->pdo()->lastInsertId();

            if ($ponderacion != 0.00) {

                $query = "SELECT idEstudiante FROM matricula WHERE idGrupo = :idGrupo";
                $stmt = $this->db->pdo()->prepare($query);

                $stmt->bindValue(':idGrupo', $idGrupo);
                $stmt->execute();
                $dato = $stmt->fetchAll(PDO::FETCH_ASSOC);


                foreach ($dato as $idEstudiante) {


                    $sql = "INSERT INTO nota (idClase, idEstudiante) VALUES (:idClase, :idEstudiante)";
                    $stmt = $this->db->pdo()->prepare($sql);

                    $stmt->bindValue(':idClase', $idClase);
                    $stmt->bindValue(':idEstudiante', $idEstudiante['idEstudiante']);

                    $stmt->execute();


                }
            }

            /** @var Grupo */
            $Grupo = Grupo::cargar($idGrupo);

            Bitacora::registrar("Registro ude clase en el grupo " . $Grupo->getCodigo() . " .");

            http_response_code(200);
            echo json_encode(array('msj' => 'Clase registrada exitosamente, nota evalutiva disponible: ' . $disponible1 . '%', 'status' => 200));
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



    public function listarClases($idGrupo)
    {

        try {

            $sql = "SELECT * FROM clase WHERE idGrupo = :idGrupo AND estatus = '1'";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idGrupo', $idGrupo);

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



    public function editarClase($id, $idGrupo, $titulo, $objetivo, $ponderacion)
    {

        try {


            $disponible1 = $this->validarPonderacion($idGrupo, $ponderacion, $id);
            $ponderacion1 = $this->validarExitenciaNotas($id, $ponderacion);

            $sql = "UPDATE clase SET titulo = :titulo, objetivo = :objetivo, ponderacion = :ponderacion WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':objetivo', $objetivo);
            $stmt->bindValue(':ponderacion', $ponderacion);

            $stmt->execute();


            if ($ponderacion != 0.00 && $ponderacion != $ponderacion1) {

                $query = "SELECT idEstudiante FROM matricula WHERE idGrupo = :idGrupo";
                $stmt = $this->db->pdo()->prepare($query);

                $stmt->bindValue(':idGrupo', $idGrupo);
                $stmt->execute();
                $dato = $stmt->fetchAll(PDO::FETCH_ASSOC);


                foreach ($dato as $idEstudiante) {


                    $sql = "INSERT INTO nota (idClase, idEstudiante) VALUES (:idClase, :idEstudiante)";
                    $stmt = $this->db->pdo()->prepare($sql);

                    $stmt->bindValue(':idClase', $id);
                    $stmt->bindValue(':idEstudiante', $idEstudiante['idEstudiante']);

                    $stmt->execute();


                }
            }

            http_response_code(200);
            echo json_encode(array('msj' => 'Clase actualizada correctamente, nota evaluativa disponible: ' . $disponible1 . '%'));
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

    public function eliminarClase($id)
    {



        try {
            $query = "SELECT * FROM nota 
            WHERE idClase = :id AND calificacion > '0.00'";

            $stmt = $this->db->pdo()->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() >= 1) {

                throw new Exception("No se puede eliminar, se encontro que al menos un estudiante ya posee calificacion en esta clase", 422);

            }

            $query1 = "SELECT * FROM contenido 
            WHERE idClase = :id AND estatus = '1'";

            $stmt1 = $this->db->pdo()->prepare($query1);
            $stmt1->bindValue(':id', $id);
            $stmt1->execute();
            $stmt1->fetchAll(PDO::FETCH_ASSOC);


            if ($stmt1->rowCount() >= 1) {

                throw new Exception("No se puede eliminar, se encontro que la clase ya posee contenido", 422);

            }

            $sql = "UPDATE clase SET estatus = '0' WHERE id = :id";

            $stmt1 = $this->db->pdo()->prepare($sql);

            $stmt1->bindValue(":id", $id);

            $stmt1->execute();

            $sql1 = "DELETE FROM nota WHERE idClase = :id";

            $stmt2 = $this->db->pdo()->prepare($sql1);

            $stmt2->bindValue(":id", $id);


            $stmt2->execute();
            http_response_code(200);
            echo json_encode(array('msj' => 'Clase eliminada correctamente', 'status' => 200));
            die();


        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    private function validarPonderacion($idGrupo, $ponderacion, $id)
    {
        try {



            $query = "SELECT idGrupo, SUM(ponderacion) AS total FROM clase 
            WHERE idGrupo = :idGrupo AND estatus = '1' AND (id NOT IN (:id)) GROUP BY idGrupo";

            $stmt = $this->db->pdo()->prepare($query);
            $stmt->bindValue(':idGrupo', $idGrupo);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $dato = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() > 0){

           
            $total = $dato['total'] + $ponderacion;
            $Disponible1 = 100 - $total;

            if ($ponderacion > 0 ) {

                if ($dato['total'] == 100) {

                    throw new Exception("La nota evaluada para este grupo ya ocupa el 100% disponible", 422);

                } elseif ($total > 100) {

                    $Disponible = 100 - $dato['total'];
                    throw new Exception("La ponderación asignada para esta clase es mayor a la nota evaluativa disponible para este grupo, se encuentra disponible para evaluar: " . $Disponible . "%", 422);

                } else {
                    return $Disponible1;
                }
            }

            return $Disponible1; 
        }else{ 

            $Disponible1 = 100 - $ponderacion;

            return $Disponible1;
        }

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    private function validarExitenciaNotas($id, $ponderacion)
    {
        try {


            $query = "SELECT * FROM clase 
            WHERE id = :id  AND estatus = '1'";

            $stmt = $this->db->pdo()->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $dato = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($ponderacion != $dato['ponderacion']) {

                $query = "SELECT * FROM nota 
            WHERE idClase = :id AND calificacion > '0.00'";

                $stmt = $this->db->pdo()->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);


                if ($stmt->rowCount() == 0) {

                    $query = "DELETE FROM nota WHERE idClase = :id";
                    $stmt = $this->db->pdo()->prepare($query);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    return $dato['ponderacion'];
                } else {

                    throw new Exception("No se puede cambiar la ponderacion, se encontro que al menos un estudiante ya posee calificacion en esta clase", 422);

                }
            }

            return $dato['ponderacion'];

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


    public function getIdGrupo()
    {
        return $this->idGrupo;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getObjetivo()
    {
        return $this->Objetivo;
    }

    public function getPonderacion()
    {
        return $this->ponderacion;
    }

}
