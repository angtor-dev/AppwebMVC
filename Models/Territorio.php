<?php
require_once "Models/Model.php";

class Territorio extends Model

{   // variable local que nos servira para crear la conexion a la base de datos
   

    public  function registrar_territorio($idSede, $nombre, $idLider, $detalles){
        try {
            //Aqui puedes declarar una variable con el nombre que quieras. Puede ser $sql, $consulta, $query. Como desees
        //Lo unico que tienes que tomar en cuenta que hay nombras que si estan predefinidos, pero relah, ya el editor te avisa
        $sql = "INSERT INTO territorio (idSede, nombre, idLider, detalles) 
        VALUES (:idSede, :nombre, :idLider, :detalles)";
        //no se pueden enviar los valores por variables  parametrizacion y evita inyeccion de slq':nombrequetuquieres'
        //Todo lo que esta en VALUES() esta malo, preguntame el porque y despues quiero que escribas la respuesta aqui como comentario para que nunca se te olvide

        //Preparamos aqui la consulta sql que hicimos ahi arriba, es decir, preparamos la variable $sql
        $stmt = $this->db->pdo()->prepare($sql);

        //Ahora empezamos a ingresar los valores en la consulta sql. Es decir, ingresamos los valores parametrizados
        //Esto con la finalidad de evitar inyecciones SQL
        $stmt->bindValue(':idSede', $idSede);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':idLider', $idLider);
        $stmt->bindValue(':detalles', $detalles);
    
        //Ahora ejecutemos la consulta sql una vez ingresado todos los valores, es decir, los parametros que mencionamos arriba
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
    
    public  function listar_territorio()
    {

        try {

            $sql = "SELECT usuario.id, usuario.nombre AS nombreLider, usuario.cedula, usuario.apellido, territorio.idLider, territorio.id, territorio.idSede,
             territorio.detalles, territorio.codigo, territorio.nombre, territorio.estatus 
             FROM territorio INNER JOIN usuario ON usuario.id = territorio.idLider WHERE territorio.estatus = '1'";


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


    public  function editar_territorio($id, $idSede, $nombre, $idLider, $detalles)
    {

        try {



            $sql = "UPDATE territorio SET  idSede = :idSede, nombre = :nombre, idLider = :idLider, detalles = :detalles WHERE territorio.id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':idSede', $idSede);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':idLider', $idLider);
            $stmt->bindValue(':detalles', $detalles);

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




    public  function eliminar_territorio($id)
    {

        try {

            $sql = "UPDATE territorio SET estatus = '0' WHERE territorio.id = :id";

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


    public  function listar_lideres()
    {

        try {


            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuariorol.idRol = '3'";

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
            echo json_encode($error_data);
            die();
        }
    }
        


}
?>

