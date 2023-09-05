<?php
require_once "Models/Model.php";

class Sede extends Model
{
    public int $id;
    public ?int $idPastor;
    public ?string $codigo;
    public string $nombre;
    public string $estado;
    public string $direccion;
    public int $estatus;

    public  function registrar_Sede($idPastor, $nombre, $direccion, $estado){
        try {
            //Aqui puedes declarar una variable con el nombre que quieras. Puede ser $sql, $consulta, $query. Como desees
        //Lo unico que tienes que tomar en cuenta que hay nombras que si estan predefinidos, pero relah, ya el editor te avisa
        $sql = "INSERT INTO sede (idpastor, nombre, estado, direccion) 
        VALUES (:idPastor, :nombre, :estado, :direccion)";
        //no se pueden enviar los valores por variables  parametrizacion y evita inyeccion de slq':nombrequetuquieres'
        //Todo lo que esta en VALUES() esta malo, preguntame el porque y despues quiero que escribas la respuesta aqui como comentario para que nunca se te olvide

        //Preparamos aqui la consulta sql que hicimos ahi arriba, es decir, preparamos la variable $sql
        $stmt = $this->db->pdo()->prepare($sql);

        //Ahora empezamos a ingresar los valores en la consulta sql. Es decir, ingresamos los valores parametrizados
        //Esto con la finalidad de evitar inyecciones SQL
        $stmt->bindValue(':idPastor', $idPastor);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':direccion', $direccion);
    
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
    
    public  function listar_Sede()
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

    public  function editar_Sede($id, $idPastor, $nombre, $direccion, $estado)
    {

        try {

            $sql = "UPDATE sede SET idPastor = :idPastor, nombre = :nombre, estado = :estado, direccion = :direccion WHERE sede.id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':idPastor', $idPastor);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':direccion', $direccion);

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

    public  function eliminar_Sede($id)
    {

        try {

            $sql = "UPDATE sede SET estatus = '0' WHERE sede.id = :id";

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

    public  function listar_Pastores()
    {

        try {
            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuariorol.idRol = '4'";

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
























