<?php
require_once "Models/Model.php";

class Nota extends Model
{
    public int $id;
    public int $idClase;
    public int $idEstudiante;
    private float $calificacion;




    public function listarNotas($idClase)
    {

        try {

            $sql = "SELECT usuario.cedula, CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombres, usuario.id AS idUsuario, nota.* 
            FROM nota
            INNER JOIN usuario ON usuario.id = nota.idEstudiante
            WHERE idClase = :idClase";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idClase', $idClase);

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



    public function editarNota($idClase, $idEstudiante, $calificacion)
    {

        try {

            /** @var Clase **/
            $Clase = Clase::cargar($idClase);

            if ($calificacion > $Clase->getPonderacion()) {

                throw new Exception("La calificación que se intenta ingresar supera la ponderacion indicada para esta 
                clase. \n (Ponderación = " .$Clase->getPonderacion(). "%)", 422);

            }

            $sql = "UPDATE nota SET calificacion = :calificacion WHERE idClase = :idClase AND idEstudiante = :idEstudiante";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idClase', $idClase);
            $stmt->bindValue(':idEstudiante', $idEstudiante);
            $stmt->bindValue(':calificacion', $calificacion);

            $stmt->execute();

            http_response_code(200);
            echo json_encode(array('msj' => 'Calificacion asignada exitosamente'));
            die();
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
        
    }

    public function getIdClase()
    {
        return $this->idClase;
    }

    public function getIdEstudiante()
    {
        return $this->idEstudiante;
    }

    public function getcalificacion()
    {
        return $this->calificacion;
    }

}
