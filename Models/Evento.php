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





}
?>