<?php
require_once "Models/Model.php";

class Evento extends Model
{
    
    public  function listar_Eventos()
    {

        try {
            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            if ($usuario->tienePermiso("agendaApostol", "consultar")) {
                $sql = "SELECT evento.id, evento.titulo AS `title`, evento.descripcion AS `description`, evento.fechaInicio AS `start`, evento.fechaFinal AS `end`, eventosede.idSede as idSede, eventosede.comentario FROM evento 
                INNER JOIN eventosede on eventosede.idEvento = evento.id";
    
                $stmt = $this->db->pdo()->prepare($sql);
    
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $sql = 'SELECT evento.id, evento.titulo, evento.descripcion, evento.fechaInicio, evento.fechaFinal, eventosede.idSede as idSede, eventosede.comentario FROM evento 
                INNER JOIN eventosede on eventosede.idEvento = evento.id AND eventosede.idSede = :idSede';
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":idSede", $usuario->idSede);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

           
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