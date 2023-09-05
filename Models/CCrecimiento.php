<?php
require_once "Models/Model.php";

class CCrecimiento extends Model

{   
   

    public  function registrar_CCrecimiento($nombre, $idLider, $idCoLider, $idTerritorio){
        try {
            
        $sql = "INSERT INTO celulacrecimiento (nombre, idLider, idCoLider, idTerritorio) 
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
    
    public  function listar_CCrecimiento()
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
            celulacrecimiento.id,
            celulacrecimiento.idLider,
            celulacrecimiento.idCoLider,
            celulacrecimiento.idTerritorio,
            celulacrecimiento.codigo,
            celulacrecimiento.nombre,
            celulacrecimiento.estatus
            FROM celulacrecimiento
            INNER JOIN usuario AS Lider ON celulacrecimiento.idLider = Lider.id
            INNER JOIN usuario AS CoLider ON celulacrecimiento.idCoLider = CoLider.id
            WHERE celulacrecimiento.estatus = '1'";


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


    public  function editar_CCrecimiento($id, $nombre, $idLider, $idCoLider, $idTerritorio)
    {

        try {



            $sql = "UPDATE celulacrecimiento SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider, idTerritorio = :idTerritorio WHERE celulacrecimiento.id = :id";


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

    public  function eliminar_CCrecimiento($id)
    {

        try {

            $sql = "UPDATE celulacrecimiento SET estatus = '0' WHERE celulacrecimiento.id = :id";

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

    public function registrar_reunion($idCelulaCrecimiento, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones)
    {
       
        try {
            
        $sql = "INSERT INTO reunioncrecimiento (idCelulaCrecimiento, fecha, tematica, semana, generosidad, infantil, juvenil, adulto, actividad, observaciones) 
        VALUES (:idCelulaCrecimiento, :fecha, :tematica, :semana, :generosidad, :infantil, :juvenil, :adulto, :actividad, :observaciones)";
      
        $stmt = $this->db->pdo()->prepare($sql);

        
        $stmt->bindValue(':idCelulaCrecimiento', $idCelulaCrecimiento);
        $stmt->bindValue(':fecha', $fecha);
        $stmt->bindValue(':tematica', $tematica);
        $stmt->bindValue(':semana', $semana);
        $stmt->bindValue(':generosidad', $generosidad);
        $stmt->bindValue(':infantil', $infantil);
        $stmt->bindValue(':juvenil', $juvenil);
        $stmt->bindValue(':adulto', $adulto);
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
                    reunioncrecimiento.id,
                    reunioncrecimiento.fecha,
                    reunioncrecimiento.tematica,
                    reunioncrecimiento.semana,
                    reunioncrecimiento.generosidad,
                    reunioncrecimiento.infantil,
                    reunioncrecimiento.juvenil,
                    reunioncrecimiento.adulto,
                    reunioncrecimiento.actividad,
                    reunioncrecimiento.observaciones,
                    celulacrecimiento.codigo,
                    celulacrecimiento.nombre,
                    celulacrecimiento.id AS idcelulacrecimiento
                FROM reunioncrecimiento
                INNER JOIN celulacrecimiento
                ON reunioncrecimiento.idcelulacrecimiento = celulacrecimiento.id
                ORDER BY reunioncrecimiento.fecha DESC;";


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


    public  function editar_reuniones($id, $idCelulaCrecimiento, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones){
       
        try {
            
        $sql = "UPDATE reunioncrecimiento
                SET
                idCelulaCrecimiento = :idCelulaCrecimiento,
                fecha = :fecha,
                tematica = :tematica,
                semana = :semana,
                generosidad = :generosidad,
                infantil = :infantil,
                juvenil = :juvenil,
                adulto = :adulto,
                actividad = :actividad,
                observaciones = :observaciones
                WHERE id = :id";
      
        $stmt = $this->db->pdo()->prepare($sql);

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':idCelulaCrecimiento', $idCelulaCrecimiento);
        $stmt->bindValue(':fecha', $fecha);
        $stmt->bindValue(':tematica', $tematica);
        $stmt->bindValue(':semana', $semana);
        $stmt->bindValue(':generosidad', $generosidad);
        $stmt->bindValue(':infantil', $infantil);
        $stmt->bindValue(':juvenil', $juvenil);
        $stmt->bindValue(':adulto', $adulto);
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

            $sql = "DELETE FROM reunioncrecimiento WHERE id = :id";

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



            $sql = "SELECT * FROM celulacrecimiento WHERE celulacrecimiento.estatus = '1'";

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