<?php
require_once "Models/Model.php";

class Discipulo extends Model
{
   

    public  function registrar_discipulo($asisCrecimiento, $asisFamiliar, $idConsolidador, $idcelulaconsolidacion, $cedula, $nombre,
    $apellido, $telefono, $direccion, $estadoCivil, $motivo, $fechaNacimiento, $fechaConvercion){
        try {
            
        $sql = "INSERT INTO discipulo (
            asisCrecimiento,
            asisFamiliar,
            idConsolidador,
            idcelulaconsolidacion,
            cedula,
            nombre,
            apellido,
            telefono,
            direccion,
            estadoCivil,
            motivo,
            fechaNacimiento,
            fechaConvercion
          ) VALUES (
            :asisCrecimiento,
            :asisFamiliar,
            :idConsolidador,
            :idcelulaconsolidacion,
            :cedula,
            :nombre,
            :apellido,
            :telefono,
            :direccion,
            :estadoCivil,
            :motivo,
            :fechaNacimiento,
            :fechaConvercion
          )";
     
        $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':asisCrecimiento', $asisCrecimiento);
            $stmt->bindValue(':asisFamiliar', $asisFamiliar);
            $stmt->bindValue(':idConsolidador', $idConsolidador);
            $stmt->bindValue(':idcelulaconsolidacion', $idcelulaconsolidacion);
            $stmt->bindValue(':cedula', $cedula);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':apellido', $apellido);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':estadoCivil', $estadoCivil);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->bindValue(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindValue(':fechaConvercion', $fechaConvercion);
    
        //Ahora ejecutemos la consulta sql una vez ingresado todos los valores, es decir, los parametros que mencionamos arriba
        $stmt->execute();
        http_response_code(200);
            echo json_encode(array('msj'=>'Se registro el Discipulo correctamente', 'status' => 200));
            die();
        } catch (Exception $e) {// Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );

            echo json_encode($error_data);
            die();
        }
    }  


    public  function listar_Consolidador()
    {

        try {


            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuariorol.idRol IN (1, 2, 3, 4)";

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
    
    public  function listar_discipulo()
    {

        try {

            $sql = "SELECT Consolidador.id AS idConsolidador,
            Consolidador.nombre AS nombreConsolidador,
            Consolidador.apellido AS apellidoConsolidador,
            Consolidador.cedula AS cedulaConsolidador,
            celulaconsolidacion.id AS idcelulaconsolidacion,
            celulaconsolidacion.codigo,
            discipulo.id,
            discipulo.asisCrecimiento,
            discipulo.asisFamiliar,
            discipulo.idConsolidador,
            discipulo.idcelulaconsolidacion,
            discipulo.cedula,
            discipulo.nombre,
            discipulo.apellido,
            discipulo.telefono,
            discipulo.direccion,
            discipulo.estadoCivil,
            discipulo.motivo,
            discipulo.fechaNacimiento,
            discipulo.fechaConvercion,
            COUNT(asistencia.id) AS asistencias
          FROM
            discipulo
            INNER JOIN usuario AS Consolidador ON discipulo.idConsolidador = Consolidador.id
            INNER JOIN asistencia ON discipulo.id = asistencia.idDiscipulo
            INNER JOIN celulaconsolidacion ON discipulo.idcelulaconsolidacion = celulaconsolidacion.id";

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

    public  function editar_discipulo($id, $asisCrecimiento, $asisFamiliar, $idConsolidador, $idcelulaconsolidacion, $cedula, $nombre,
    $apellido, $telefono, $direccion, $estadoCivil, $motivo, $fechaNacimiento, $fechaConvercion)
    {

        try {

            


            $sql = "UPDATE discipulo SET
            asisCrecimiento = :asisCrecimiento,
            asisFamiliar = :asisFamiliar,
            idConsolidador = :idConsolidador,
            idcelulaconsolidacion = :idcelulaconsolidacion,
            cedula = :cedula,
            nombre = :nombre,
            apellido = :apellido,
            telefono = :telefono,
            direccion = :direccion,
            estadoCivil = :estadoCivil,
            motivo = :motivo,
            fechaNacimiento = :fechaNacimiento,
            fechaConvercion = :fechaConvercion
            WHERE
            id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':asisCrecimiento', $asisCrecimiento);
            $stmt->bindValue(':asisFamiliar', $asisFamiliar);
            $stmt->bindValue(':idConsolidador', $idConsolidador);
            $stmt->bindValue(':idcelulaconsolidacion', $idcelulaconsolidacion);
            $stmt->bindValue(':cedula', $cedula);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':apellido', $apellido);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':estadoCivil', $estadoCivil);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->bindValue(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindValue(':fechaConvercion', $fechaConvercion);


            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('msj'=>'Discipulo actualizado correctamente', 'status' => 200));
            die();
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

    public  function eliminar_discipulo($id)
    {

        try {

            $sql = "DELETE FROM discipulo WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('msj'=>'Discipulo eliminado correctamente', 'status' => 200));
            die();
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


    public function esMayorDeEdad($fechaNacimiento) {
        $hoy = new DateTime();
        $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
        $fecha->modify('+18 years');
    
        return $hoy >= $fecha;
    }


}
