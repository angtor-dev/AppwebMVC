<?php
require_once "Models/Model.php";

class Evento extends Model
{


    public int $id;
    private string $titulo;
    private string $descripcion;
    private string $fechaInicio;
    private string $fechaFinal;
    public function listar_Eventos()
    {

        try {
            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            if ($usuario->tienePermiso("agendaApostol", "consultar")) {
                /*$sql = "SELECT evento.id, evento.titulo AS `title`, evento.descripcion AS `descripcion`, evento.fechaInicio AS `start`, evento.fechaFinal AS `end`, eventosede.idSede AS `idSede`, eventosede.comentario FROM evento 
                INNER JOIN eventosede on eventosede.idEvento = evento.id";*/
                $sql = 'SELECT evento.id, evento.titulo AS `title`, evento.descripcion AS `descripcion`, evento.fechaInicio AS `start`, evento.fechaFinal AS `end` FROM evento';

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } else {
                $sql = 'SELECT evento.id, evento.titulo AS `title`, evento.descripcion AS `descripcion`, evento.fechaInicio AS `start`, evento.fechaFinal AS `end`, eventosede.idSede AS `idSede`, eventosede.comentario FROM evento 
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


    public function listar_Sedes()
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

    public function registrar_eventos($titulo, $fechaInicio, $fechaFinal, $descripcion, $sedes)
    {
        try {

            $sql = "INSERT INTO evento (titulo, fechaInicio, fechaFinal, descripcion)
           VALUES (:titulo, :fechaInicio, :fechaFinal, :descripcion)";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':fechaInicio', $fechaInicio);
            $stmt->bindValue(':fechaFinal', $fechaFinal);
            $stmt->bindValue(':descripcion', $descripcion);


            $stmt->execute();

            $sql2 = "SELECT MAX(id) AS idEvento FROM evento";
            $stmt2 = $this->db->pdo()->prepare($sql2);
            $stmt2->execute();
            $idEvento = $stmt2->fetch(PDO::FETCH_ASSOC);

            foreach ($sedes as $sede) {

                $sql3 = "INSERT INTO eventosede (idSede, idEvento) VALUES (:sede, :idEvento)";

                $stmt3 = $this->db->pdo()->prepare($sql3);

                $stmt3->bindValue(':sede', $sede);
                $stmt3->bindValue(':idEvento', $idEvento['idEvento']);

                $stmt3->execute();

                $id = $idEvento['idEvento'];


                /** @var Evento */
             $evento = Evento::cargar($id);
             /** @var Usuario[] */
             $usuarios = Usuario::listar(1);
             foreach ($usuarios as $usuario) {
                 if ($usuario->idSede == $sede) {
                     Notificacion::registrar(
                         $usuario->id,
                         "Nuevo evento",
                         "Titulo del evento: " . $evento->getTitulo() . ", Revisa tu agenda."
                     );
                 }}

                
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

    public function Editar_evento($titulo, $idEvento, $fechaInicio, $fechaFinal, $descripcion)
    {   
        try {
            
            $sql = "UPDATE evento SET titulo = :titulo,
             fechaInicio = :fechaInicio,
              fechaFinal = :fechaFinal,
               descripcion = :descripcion
            WHERE id = :idEvento";

            $stmt = $this->db->pdo()->prepare($sql);

            
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':idEvento', $idEvento);
            $stmt->bindValue(':fechaInicio', $fechaInicio);
            $stmt->bindValue(':fechaFinal', $fechaFinal);
            $stmt->bindValue(':descripcion', $descripcion);


            $stmt->execute();

            Bitacora::registrar("Actualizacion de Evento");

            http_response_code(200);
            echo json_encode(array('msj' => 'Actualizacion  exitosa', 'status' => 200));
            
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

    public function cargar_data_sedes($idEvento)
    {
        try {
            $sql = 'SELECT eventosede.id, sede.nombre, eventosede.comentario, eventosede.idEvento FROM eventosede 
            INNER JOIN sede on sede.id = eventosede.idSede WHERE eventosede.idEvento = :idEvento';

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':idEvento', $idEvento);
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


    public function sedes_sin_agregar($idEvento)
    {
        try {
            $sql = 'SELECT sede.id, sede.nombre, sede.codigo FROM sede WHERE sede.id NOT IN (SELECT idSede FROM eventosede WHERE idEvento = :idEvento)';

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':idEvento', $idEvento);
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


    public function eliminar_evento($id)
    {
        try {

            $sql = "DELETE FROM evento WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':id', $id);

            $stmt->execute();

            Bitacora::registrar("Eliminar Evento");
            http_response_code(200);
            echo json_encode(array('msj' => 'Evento eliminado exitosamente ', 'status' => 200));

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

    public function eliminar_evento_sede($id)
    {

        try {

            $sql = "DELETE FROM eventosede WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':id', $id);


            $stmt->execute();

            Bitacora::registrar("Eliminar Evento por Sede");
            http_response_code(200);
            echo json_encode(array('msj' => 'Evento vinculado a Sede eliminado exitosamente ', 'status' => 200));

            die();
        } catch (Exception $e) {

            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }


    }


    public function actualizarSedes($arraySedes, $idEvento)
    {

        try {


            foreach ($arraySedes as $sede) {

                $sql = "INSERT INTO eventosede (idSede, idEvento) VALUES (:sede, :idEvento)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':sede', $sede);
                $stmt->bindValue(':idEvento', $idEvento);

                $stmt->execute();
            }


            Bitacora::registrar("Actualizacion de  sedes por evento");

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

public function actualizarComentario($comentario, $id){

    try {

        /** @var Usuario */
        $usuario = $_SESSION['usuario'];

        $sql = "UPDATE eventosede SET comentario = :comentario
        WHERE idEvento = :id AND idSede = :idSede";

        $stmt = $this->db->pdo()->prepare($sql);

        $stmt->bindValue(':comentario', $comentario);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':idSede', $usuario->idSede);


        $stmt->execute();

         /** @var Evento */
         $evento= Evento::cargar($id);
         /** @var Sede */
         $sede = Sede::cargar($usuario->idSede);
                 Notificacion::registrar(
                     1,
                     "Se registro un Comentario",
                     "la Sede: " . $sede->getNombre() . ", En el vento: " . $evento->getTitulo() . "."
                 );


        Bitacora::registrar("Actualizacion de Comentario");

        http_response_code(200);
        echo json_encode(array('msj' => 'Se actualizo el comentario con exito', 'status' => 200));
        
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



    //Validaciones 
  public function valida_titulo_evento($titulos, $id)
    {


        try {

            $sql = "SELECT titulo FROM evento WHERE titulo = :titulos AND id NOT IN (:id)";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':titulos', $titulos);
            $stmt->bindValue(':id', $id);


            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $stmt->rowCount();

            if ($result > 0) {
                return true;
            } else {
                return false;
            }


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


    // Getters

    
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getFechaInicio(): string
    {
        return $this->fechaInicio;
    }

    public function getFechaFinal(): string
    {
        return $this->fechaFinal;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
}
