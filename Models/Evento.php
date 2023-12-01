<?php
require_once "Models/Model.php";

class Evento extends Model
{
    
    public  function listar_Eventos()
    {

        try {

            $sql = "SELECT id AS id, titulo AS title,
             fechaInicio AS start, fechaFinal AS end,
             descripcion AS descripcion FROM Evento";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta Agenda de Eventos");

            return $resultado;
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


    public  function listar_Sedes()
    {
        try {

            $sql = "SELECT * FROM sede WHERE sede.estatus = '1'";

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

    public function registrar_eventos($titulo, $fechaInicio, $fechaFinal, $descripcion, $sedes){
        


try {

           $sql = "INSERT INTO evento (titulo, fechaInicio, fechaFinal, descripcion)
           VALUES (:titulo, :fechaInicio, :fechaFinal, :descripcion)";
           
           $stmt = $this->db->pdo()->prepare($sql);

           $stmt->bindValue(':titulo', $titulo);
           $stmt->bindValue(':fechaInicio', $fechaInicio);
           $stmt->bindValue(':fechaFinal', $fechaFinal);
           $stmt->bindValue(':descripcion', $descripcion);


           $stmt->execute();

           $sql2 ="SELECT MAX(id) AS idEvento FROM evento";
           $stmt2 = $this->db->pdo()->prepare($sql2);
           $stmt2->execute();
           $idEvento = $stmt2->fetch(PDO::FETCH_ASSOC);

           foreach($sedes as $sede){
            
            $sql3 = "INSERT INTO eventosede (idsede, idevento) VALUES (:sede, :idEvento)";

            $stmt3 = $this->db->pdo()->prepare($sql3);

            $stmt3->bindValue(':sede', $sede);
            $stmt3->bindValue(':idEvento', $idEvento['idEvento']);
        
            $stmt3->execute();
           }
           

           Bitacora::registrar("Registro de Evento");

           http_response_code(200);
           echo json_encode(array('msj' => 'Registro  exitoso', 'status' => 200));
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





}
?>