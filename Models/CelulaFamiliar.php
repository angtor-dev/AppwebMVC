<?php
require_once "Models/Model.php";

class CelulaFamiliar extends Model
{   
    public int $id;
    public int $idLider;
    public int $idColider;
    public int $idTerritorio;
    public string $codigo;
    public string $nombre;
    public int $estatus;

    public  function registrar_CelulaFamiliar($nombre, $idLider, $idCoLider, $idTerritorio){
        try {
            
        $sql = "INSERT INTO celulafamiliar (nombre, idLider, idCoLider, idTerritorio) 
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
    
    public  function listar_CelulaFamiliar()
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
            celulafamiliar.id,
            celulafamiliar.idLider,
            celulafamiliar.idCoLider,
            celulafamiliar.idTerritorio,
            celulafamiliar.codigo,
            celulafamiliar.nombre,
            celulafamiliar.estatus
            FROM celulafamiliar
            INNER JOIN usuario AS Lider ON celulafamiliar.idLider = Lider.id
            INNER JOIN usuario AS CoLider ON celulafamiliar.idCoLider = CoLider.id
            WHERE celulafamiliar.estatus = '1'";


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


    public  function editar_CelulaFamiliar($id, $nombre, $idLider, $idCoLider, $idTerritorio)
    {

        try {



            $sql = "UPDATE celulafamiliar SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider, idTerritorio = :idTerritorio WHERE celulafamiliar.id = :id";


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




    public  function eliminar_CelulaFamiliar($id)
    {

        try {

            $sql = "UPDATE celulafamiliar SET estatus = '0' WHERE celulafamiliar.id = :id";

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

    public function registrar_reunion($idCelulaFamiliar, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones)
    {
       
        try {
            
        $sql = "INSERT INTO reunionfamiliar (idCelulaFamiliar, fecha, tematica, semana, generosidad, infantil, juvenil, adulto, actividad, observaciones) 
        VALUES (:idCelulaFamiliar, :fecha, :tematica, :semana, :generosidad, :infantil, :juvenil, :adulto, :actividad, :observaciones)";
      
        $stmt = $this->db->pdo()->prepare($sql);

        
        $stmt->bindValue(':idCelulaFamiliar', $idCelulaFamiliar);
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
                    reunionfamiliar.id,
                    reunionfamiliar.fecha,
                    reunionfamiliar.tematica,
                    reunionfamiliar.semana,
                    reunionfamiliar.generosidad,
                    reunionfamiliar.infantil,
                    reunionfamiliar.juvenil,
                    reunionfamiliar.adulto,
                    reunionfamiliar.actividad,
                    reunionfamiliar.observaciones,
                    celulafamiliar.codigo,
                    celulafamiliar.nombre,
                    celulafamiliar.id AS idcelulafamiliar
                FROM reunionfamiliar
                INNER JOIN celulafamiliar
                ON reunionfamiliar.idcelulafamiliar = celulafamiliar.id
                ORDER BY reunionfamiliar.fecha DESC;";


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


    public  function editar_reuniones($id, $idCelulaFamiliar, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones){
       
        try {
            
        $sql = "UPDATE reunionfamiliar
                SET
                idCelulaFamiliar = :idCelulaFamiliar,
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
        $stmt->bindValue(':idCelulaFamiliar', $idCelulaFamiliar);
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

            $sql = "DELETE FROM reunionfamiliar WHERE id = :id";

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



            $sql = "SELECT * FROM celulafamiliar WHERE celulafamiliar.estatus = '1'";

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

