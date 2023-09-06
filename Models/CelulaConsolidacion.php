<?php
require_once "Models/Model.php";

class CelulaConsolidacion extends Model
{   
    public int $id;
    public int $idLider;
    public int $idColider;
    public int $idTerritorio;
    public string $codigo;
    public string $nombre;
    public int $estatus;

    public  function registrar_CelulaConsolidacion($nombre, $idLider, $idCoLider, $idTerritorio){
        try {
            
        $sql = "INSERT INTO celulaconsolidacion (nombre, idLider, idCoLider, idTerritorio) 
        VALUES (:nombre, :idLider, :idCoLider, :idTerritorio)";
      
        $stmt = $this->db->pdo()->prepare($sql);

        
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':idLider', $idLider);
        $stmt->bindValue(':idCoLider', $idCoLider);
        $stmt->bindValue(':idTerritorio', $idTerritorio);
        
        $stmt->execute();
        } catch (Exception $e) {// Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            
            echo json_encode($error_data);
            die();
        }
    }  
    
    public  function listar_CelulaConsolidacion()
    {

        try {

            $sql = "SELECT Lider.id AS idLider,
            CoLider.id AS idCoLider,
            Lider.nombre AS nombreLider,
            CoLider.nombre AS nombreCoLider,
            Lider.apellido AS apellidoLider,
            CoLider.apellido AS apellidoCoLider,
            Lider.cedula AS cedulaLider,
            CoLider.cedula AS cedulaCoLider,
            celulaconsolidacion.id,
            celulaconsolidacion.idLider,
            celulaconsolidacion.idCoLider,
            celulaconsolidacion.idTerritorio,
            celulaconsolidacion.codigo,
            celulaconsolidacion.nombre,
            celulaconsolidacion.estatus
            FROM celulaconsolidacion
            INNER JOIN usuario AS Lider ON celulaconsolidacion.idLider = Lider.id
            INNER JOIN usuario AS CoLider ON celulaconsolidacion.idCoLider = CoLider.id
            WHERE celulaconsolidacion.estatus = '1'";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }


    public  function editar_CelulaConsolidacion($id, $nombre, $idLider, $idCoLider, $idTerritorio)
    {

        try {



            $sql = "UPDATE celulaconsolidacion SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider, idTerritorio = :idTerritorio WHERE celulaconsolidacion.id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':idLider', $idLider);
            $stmt->bindValue(':idCoLider', $idCoLider);
            $stmt->bindValue(':idTerritorio', $idTerritorio);

            $stmt->execute();
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }

    public  function eliminar_CelulaConsolidacion($id)
    {

        try {

            $sql = "UPDATE celulaconsolidacion SET estatus = '0' WHERE celulaconsolidacion.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }

    public function registrar_reunion($idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones)
    {
       
        try {
            
        $sql = "INSERT INTO reunionconsolidacion (idCelulaConsolidacion, fecha, tematica, semana, generosidad, actividad, observaciones) 
        VALUES (:idCelulaConsolidacion, :fecha, :tematica, :semana, :generosidad, :actividad, :observaciones)";
      
        $stmt = $this->db->pdo()->prepare($sql);

        
        $stmt->bindValue(':idCelulaConsolidacion', $idCelulaConsolidacion);
        $stmt->bindValue(':fecha', $fecha);
        $stmt->bindValue(':tematica', $tematica);
        $stmt->bindValue(':semana', $semana);
        $stmt->bindValue(':generosidad', $generosidad);
        $stmt->bindValue(':actividad', $actividad);
        $stmt->bindValue(':observaciones', $observaciones);
        
        
        $stmt->execute();
        } catch (Exception $e) {// Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            
            echo json_encode($error_data);
            die();
        }
    }  


    public  function listar_lideres()
    {

        try {

            //Esta consulta esta sujeto a cambios hasta que se consiga un mejor logica para traer los usuarios con el nivel requerido

            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario 
            WHERE usuariorol.idRol IN (1, 2, 3, 4)";

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
            echo json_encode($error_data);
            die();
        }
    }
      
    public  function listar_territorios()
    {

        try {

            $sql = "SELECT * FROM territorio WHERE territorio.estatus = '1'";

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
            echo json_encode($error_data);
            die();
        }
    }
        

    public  function listar_reuniones(){

        try {

            $sql = "SELECT
                    reunionconsolidacion.id,
                    reunionconsolidacion.fecha,
                    reunionconsolidacion.tematica,
                    reunionconsolidacion.semana,
                    reunionconsolidacion.generosidad,
                    reunionconsolidacion.actividad,
                    reunionconsolidacion.observaciones,
                    celulaconsolidacion.codigo,
                    celulaconsolidacion.nombre,
                    celulaconsolidacion.id AS idcelulaconsolidacion
                FROM reunionconsolidacion
                INNER JOIN celulaconsolidacion
                ON reunionconsolidacion.idcelulaconsolidacion = celulaconsolidacion.id
                ORDER BY reunionconsolidacion.fecha DESC;";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }


    public  function editar_reuniones($id, $idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones){
       
        try {
            
        $sql = "UPDATE reunionconsolidacion
                SET
                idCelulaConsolidacion = :idCelulaConsolidacion,
                fecha = :fecha,
                tematica = :tematica,
                semana = :semana,
                generosidad = :generosidad,
                actividad = :actividad,
                observaciones = :observaciones
                WHERE id = :id";
      
        $stmt = $this->db->pdo()->prepare($sql);

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':idCelulaConsolidacion', $idCelulaConsolidacion);
        $stmt->bindValue(':fecha', $fecha);
        $stmt->bindValue(':tematica', $tematica);
        $stmt->bindValue(':semana', $semana);
        $stmt->bindValue(':generosidad', $generosidad);
        $stmt->bindValue(':actividad', $actividad);
        $stmt->bindValue(':observaciones', $observaciones);
        
        
        $stmt->execute();
        } catch (Exception $e) {// Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            
            echo json_encode($error_data);
            die();
        }
    } 




    public  function eliminar_reuniones($id){

        try {

            $sql = "DELETE FROM reunionconsolidacion WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }


    public  function listar_celulas(){

        try {



            $sql = "SELECT * FROM celulaconsolidacion WHERE celulaconsolidacion.estatus = '1'";

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
            echo json_encode($error_data);
            die();
        }
    }
       



}
?>